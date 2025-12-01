<?php

namespace App\Http\Controllers;

use App\Exports\ExportableTrait;
use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CustomerController extends Controller
{
    use ExportableTrait;
    
    function __construct()
    {
        $this->middleware('permission:customers.view', ['only' => ['index', 'orderHistory']]);
        $this->middleware('permission:customers.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customers.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customers.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort columns
        $allowedSortColumns = ['id', 'first_name', 'last_name', 'email', 'phone', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        if (request()->wantsJson()) {
            return response($query->get());
        }

        $customers = $query->paginate(10)->appends($request->query());
        return view('customers.index')->with('customers', $customers);
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $customers = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'full_name' => 'Nama Customer',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'total_orders' => 'Total Order',
            'total_spent' => 'Total Belanja',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToPdf($customers, $columns, 'Laporan Customer');
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $customers = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'full_name' => 'Nama Customer',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'total_orders' => 'Total Order',
            'total_spent' => 'Total Belanja',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToCsv($customers, $columns, 'Laporan Customer');
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = Customer::with(['orders', 'orders.items']);

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort columns
        $allowedSortColumns = ['id', 'first_name', 'last_name', 'email', 'phone', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Get all data (no pagination for export)
        $customers = $query->get();

        // Transform data untuk export
        return $customers->map(function ($customer) {
            $totalOrders = $customer->orders->count();
            $totalSpent = $customer->orders->sum(function ($order) {
                return $order->total();
            });

            return [
                'id' => $customer->id,
                'full_name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email ?? '-',
                'phone' => $customer->phone ?? '-',
                'address' => $customer->address ?? '-',
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'created_at' => $customer->created_at->format('d-m-Y H:i')
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request)
    {
        $avatar_path = '';

        if ($request->hasFile('avatar')) {
            $avatar_path = $request->file('avatar')->store('customers', 'public');
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatar_path,
            'user_id' => $request->user()->id,
        ]);

        if (!$customer) {
            return redirect()->back()->with('error', __('customer.error_creating'));
        }
        return redirect()->route('customers.index')->with('success', __('customer.succes_creating'));
    }

    /**
     * Show customer order history with filters
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    /**
     * Show customer order history with filters
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function orderHistory(Request $request, Customer $customer)
    {
        $query = $customer->orders()->with([
            'items.product',
            'user'
        ]);

        // Search functionality - search by order ID or product name
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('items.product', function ($productQuery) use ($request) {
                        $productQuery->where('name', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Date filter - perbaikan parameter name
        if ($request->date_from) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }
        if ($request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Status filter
        if ($request->status) {
            $query->whereHas('payments', function ($paymentQuery) use ($request) {
                switch ($request->status) {
                    case 'not_paid':
                        $paymentQuery->where('received_amount', 0);
                        break;
                    case 'partial':
                        $paymentQuery->where('received_amount', '>', 0)
                            ->whereRaw('received_amount < total');
                        break;
                    case 'paid':
                        $paymentQuery->whereRaw('received_amount = total');
                        break;
                    case 'change':
                        $paymentQuery->whereRaw('received_amount > total');
                        break;
                }
            });
        }

        // Sorting - tambahkan kolom yang boleh di-sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortColumns = ['id', 'created_at', 'total', 'received_amount'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(10)->appends($request->query());

        // Statistik - hitung ulang berdasarkan query yang sudah difilter
        $statsQuery = $customer->orders();

        // Apply same filters untuk statistics
        if ($request->date_from) {
            $statsQuery->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }
        if ($request->date_to) {
            $statsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $filteredOrders = $statsQuery->with('items')->get();

        $totalOrders = $filteredOrders->count();
        $totalSpent = $filteredOrders->sum(function ($order) {
            return $order->total();
        });
        $averageOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return view('customers.order-history', compact(
            'customer',
            'orders',
            'totalSpent',
            'totalOrders',
            'averageOrder'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($customer->avatar) {
                Storage::delete($customer->avatar);
            }
            // Store avatar
            $avatar_path = $request->file('avatar')->store('customers', 'public');
            // Save to Database
            $customer->avatar = $avatar_path;
        }

        if (!$customer->save()) {
            return redirect()->back()->with('error', __('customer.error_updating'));
        }
        return redirect()->route('customers.index')->with('success', __('customer.success_updating'));
    }

    public function destroy(Customer $customer)
    {
        if ($customer->avatar) {
            Storage::delete($customer->avatar);
        }

        $customer->delete();

        return response()->json([
            'success' => true
        ]);
    }
}