<?php

namespace App\Http\Controllers;

use App\Exports\ExportableTrait;
use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    use ExportableTrait;
    
    function __construct()
    {
        $this->middleware('permission:suppliers.view', ['only' => ['index']]);
        $this->middleware('permission:suppliers.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:suppliers.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:suppliers.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Supplier::with('products');

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('products', function ($productQuery) use ($request) {
                        $productQuery->where('name', 'LIKE', "%{$request->search}%");
                    });
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

        $suppliers = $query->paginate(10)->appends($request->query());
        return view('suppliers.index')->with('suppliers', $suppliers);
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $suppliers = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'full_name' => 'Nama Supplier',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'products_count' => 'Jumlah Produk',
            'product_names' => 'Nama Produk', // Tambahkan kolom nama produk
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToPdf($suppliers, $columns, 'Laporan Supplier');
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $suppliers = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'full_name' => 'Nama Supplier',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'products_count' => 'Jumlah Produk',
            'product_names' => 'Nama Produk', // Tambahkan kolom nama produk
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToCsv($suppliers, $columns, 'Laporan Supplier');
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = Supplier::with('products');

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('products', function ($productQuery) use ($request) {
                        $productQuery->where('name', 'LIKE', "%{$request->search}%");
                    });
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
        $suppliers = $query->get();

        // Transform data untuk export
        return $suppliers->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'full_name' => $supplier->first_name . ' ' . $supplier->last_name,
                'email' => $supplier->email ?? '-',
                'phone' => $supplier->phone ?? '-',
                'address' => $supplier->address ?? '-',
                'products_count' => $supplier->products->count(),
                'product_names' => $this->getProductNames($supplier->products), // Tambahkan nama produk
                'created_at' => $supplier->created_at->format('d-m-Y H:i')
            ];
        });
    }

    /**
     * Get product names as comma separated string
     */
    private function getProductNames($products)
    {
        if ($products->isEmpty()) {
            return 'Tidak ada produk';
        }

        return $products->pluck('name')->implode(', ');
    }
    
    public function create()
    {
        $products = Product::where('status', true)->get();
        return view('suppliers.create', compact('products'));
    }

    public function store(SupplierStoreRequest $request)
    {
        $avatar_path = '';

        if ($request->hasFile('avatar')) {
            $avatar_path = $request->file('avatar')->store('suppliers', 'public');
        }

        $supplier = Supplier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatar_path,
        ]);

        // Attach products to supplier
        if ($request->has('product_ids')) {
            $supplier->products()->attach($request->product_ids);
        }

        if (!$supplier) {
            return redirect()->back()->with('error', __('supplier.error_creating'));
        }
        return redirect()->route('suppliers.index')->with('success', __('supplier.success_creating'));
    }

    public function edit(Supplier $supplier)
    {
        $products = Product::where('status', true)->get();
        $supplier->load('products');
        return view('suppliers.edit', compact('supplier', 'products'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->first_name = $request->first_name;
        $supplier->last_name = $request->last_name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($supplier->avatar) {
                Storage::delete($supplier->avatar);
            }
            // Store avatar
            $avatar_path = $request->file('avatar')->store('suppliers', 'public');
            // Save to Database
            $supplier->avatar = $avatar_path;
        }

        // Sync products
        if ($request->has('product_ids')) {
            $supplier->products()->sync($request->product_ids);
        } else {
            $supplier->products()->detach();
        }

        if (!$supplier->save()) {
            return redirect()->back()->with('error', __('supplier.error_updating'));
        }
        return redirect()->route('suppliers.index')->with('success', __('supplier.success_updating'));
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->avatar) {
            Storage::delete($supplier->avatar);
        }

        // Detach all products before deleting supplier
        $supplier->products()->detach();

        $supplier->delete();

        return response()->json([
            'success' => true
        ]);
    }
}