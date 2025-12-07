<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Exports\ExportableTrait;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ExportableTrait;
    function __construct()
    {
        $this->middleware('permission:orders.view', ['only' => ['index']]);
        $this->middleware('permission:orders.create', ['only' => ['store']]);
        $this->middleware('permission:orders.edit', ['only' => ['partialPayment']]);
        $this->middleware('permission:orders.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'payments', 'customer']);

        // Filter tanggal
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter customer type
        if ($request->customer_type == 'walk_in') {
            $query->whereNull('customer_id');
        } elseif ($request->customer_type == 'registered') {
            $query->whereNotNull('customer_id');
        }

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('customer', function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', "%{$request->search}%")
                            ->orWhere('last_name', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort columns
        $allowedSortColumns = ['id', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Filter status (harus dilakukan setelah query dasar)
        if ($request->status) {
            $orders = $query->get()->filter(function ($order) use ($request) {
                if ($request->status == 'not_paid') {
                    return $order->receivedAmount() == 0;
                } elseif ($request->status == 'partial') {
                    return $order->receivedAmount() > 0 && $order->receivedAmount() < $order->total();
                } elseif ($request->status == 'paid') {
                    return $order->receivedAmount() == $order->total();
                } elseif ($request->status == 'change') {
                    return $order->receivedAmount() > $order->total();
                }
                return true;
            });

            // Convert back to paginator
            $orders = new \Illuminate\Pagination\LengthAwarePaginator(
                $orders->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 10),
                $orders->count(),
                10,
                \Illuminate\Pagination\Paginator::resolveCurrentPage(),
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $orders = $query->paginate(10);
        }

        // Preserve query parameters for pagination
        $orders->appends($request->query());

        $total = $orders->sum(function ($i) {
            return $i->total();
        });
        $receivedAmount = $orders->sum(function ($i) {
            return $i->receivedAmount();
        });

        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
    }

    public function getInvoiceModal($id)
    {
        $order = Order::with(['items.product', 'payments', 'customer'])->findOrFail($id);
        return view('orders.invoice-modal', compact('order'));
    }

    public function printInvoice($id)
    {
        $order = Order::with(['items.product', 'payments', 'customer'])->findOrFail($id);
        return view('orders.invoice-print', compact('order'));
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = Order::with(['items.product', 'payments', 'customer', 'user']);

        // Filter tanggal
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter customer type
        if ($request->customer_type == 'walk_in') {
            $query->whereNull('customer_id');
        } elseif ($request->customer_type == 'registered') {
            $query->whereNotNull('customer_id');
        }

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('customer', function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', "%{$request->search}%")
                            ->orWhere('last_name', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortColumns = ['id', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Get all data (no pagination for export)
        $orders = $query->get();

        // Transform data untuk export
        return $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'created_at' => $order->created_at->format('d-m-Y H:i'),
                'customer_name' => $order->getCustomerName(), // Gunakan method getCustomerName() dari model
                'customer_type' => $order->customer ? 'Registered' : 'Walk-in',
                'total_amount' => $order->total(),
                'received_amount' => $order->receivedAmount(),
                'status' => $this->getOrderStatus($order),
                'cashier_name' => $order->user ? $order->user->getFullname() : 'N/A' // Pastikan ada fallback
            ];
        });
    }

    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $orders = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'Order ID',
            'created_at' => 'Tanggal Order',
            'customer_name' => 'Nama Customer', // Sesuaikan dengan key di array
            'customer_type' => 'Tipe Customer',
            'total_amount' => 'Total Amount',
            'received_amount' => 'Received Amount',
            'status' => 'Status',
            'cashier_name' => 'Cashier', // Sesuaikan dengan key di array
        ];

        return $this->exportToPdf($orders, $columns, 'Laporan Order');
    }

    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $orders = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'Order ID',
            'created_at' => 'Tanggal Order',
            'customer_name' => 'Nama Customer', // Sesuaikan dengan key di array
            'customer_type' => 'Tipe Customer',
            'total_amount' => 'Total Amount',
            'received_amount' => 'Received Amount',
            'status' => 'Status',
            'cashier_name' => 'Cashier' // Sesuaikan dengan key di array
        ];

        return $this->exportToCsv($orders, $columns, 'Laporan Order');
    }

    /**
     * Get order status text
     */
    private function getOrderStatus($order)
    {
        $received = $order->receivedAmount();
        $total = $order->total();

        if ($received == 0) {
            return 'Not Paid';
        } elseif ($received < $total) {
            return 'Partial';
        } elseif ($received == $total) {
            return 'Paid';
        } else {
            return 'Change';
        }
    }

    public function store(OrderStoreRequest $request)
    {
        $user = $request->user();
        $cart = $user->cart()->get();

        // Cek stok tiap item sebelum buat order
        foreach ($cart as $item) {
            if ($item->pivot->quantity > $item->quantity) {
                return response([
                    'message' => __('cart.available', ['quantity' => $item->quantity]),
                ], 400);
            }
        }

        // Jika semua valid, lanjut buat order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $user->id,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);

            // kurangi stok produk
            $item->decrement('quantity', $item->pivot->quantity);
        }

        // kosongkan cart setelah checkout
        $user->cart()->detach();

        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
        ]);
    }

    public function partialPayment(Request $request)
    {
        $orderId = $request->order_id;
        $amount = $request->amount;

        // Find the order
        $order = Order::findOrFail($orderId);

        // Check if the amount exceeds the remaining balance
        $remainingAmount = $order->total() - $order->receivedAmount();
        if ($amount > $remainingAmount) {
            return redirect()->route('orders.index')->withErrors('Amount exceeds remaining balance');
        }

        // Save the payment
        DB::transaction(function () use ($order, $amount) {
            $order->payments()->create([
                'amount' => $amount,
                'user_id' => auth()->user()->id,
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Partial payment of ' . config('settings.currency_symbol') . number_format($amount, 2) . ' made successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $order = Order::with(['items.product', 'payments'])->findOrFail($id);

        try {
            DB::transaction(function () use ($order) {
                // Kembalikan stok produk sesuai quantity item
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                // Hapus semua item terkait
                $order->items()->delete();

                // Hapus semua pembayaran terkait
                $order->payments()->delete();

                // Hapus order
                $order->delete();
            });

            return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus dan stok produk dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->withErrors('Gagal menghapus order: ' . $e->getMessage());
        }
    }
}