<?php

namespace App\Http\Controllers;

use App\Exports\ExportableTrait;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    use ExportableTrait;
    
    function __construct()
    {
        $this->middleware('permission:purchases.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:purchases.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchases.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchases.delete', ['only' => ['destroy']]);
    }

    /**
     * Tampilkan semua pembelian beserta itemnya.
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.product', 'user']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('items.product', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('purchase_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('purchase_date', '<=', $request->date_to);
        }

        // Expiry filter
        if ($request->has('expiry_filter') && $request->expiry_filter != '') {
            $today = now()->format('Y-m-d');
            $thirtyDaysFromNow = now()->addDays(30)->format('Y-m-d');

            switch ($request->expiry_filter) {
                case 'expired':
                    $query->whereHas('items', function ($q) use ($today) {
                        $q->where('expired_date', '<', $today);
                    });
                    break;
                case 'soon':
                    $query->whereHas('items', function ($q) use ($today, $thirtyDaysFromNow) {
                        $q->where('expired_date', '>=', $today)
                            ->where('expired_date', '<=', $thirtyDaysFromNow);
                    });
                    break;
                case 'valid':
                    $query->whereHas('items', function ($q) use ($thirtyDaysFromNow) {
                        $q->where('expired_date', '>', $thirtyDaysFromNow)
                            ->orWhereNull('expired_date');
                    });
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'purchase_date');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['id', 'purchase_date', 'created_at', 'invoice_number', 'total_amount'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'purchase_date';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $purchases = $query->paginate(10);
        $suppliers = Supplier::orderBy('first_name')->get();

        // Hitung total items untuk display
        $totalItems = 0;
        foreach ($purchases as $purchase) {
            $totalItems += $purchase->items->count();
        }

        return view('purchases.index', compact('purchases', 'suppliers', 'totalItems'));
    }

     /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $purchaseItems = $this->getExportData(new Request($filters));

        $columns = [
            'invoice_number' => 'No. Invoice',
            'purchase_date' => 'Tanggal Pembelian',
            'supplier_name' => 'Supplier',
            'product_name' => 'Nama Produk',
            'quantity' => 'Quantity',
            'price' => 'Harga',
            'subtotal' => 'Subtotal',
            'expired_date' => 'Tanggal Expired',
            'user_name' => 'User'
        ];

        return $this->exportToPdf($purchaseItems, $columns, 'Laporan Pembelian Per Item');
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $purchaseItems = $this->getExportData(new Request($filters));

        $columns = [
            'invoice_number' => 'No. Invoice',
            'purchase_date' => 'Tanggal Pembelian',
            'supplier_name' => 'Supplier',
            'product_name' => 'Nama Produk',
            'quantity' => 'Quantity',
            'price' => 'Harga',
            'subtotal' => 'Subtotal',
            'expired_date' => 'Tanggal Expired',
            'user_name' => 'User'
        ];

        return $this->exportToCsv($purchaseItems, $columns, 'Laporan Pembelian Per Item');
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = PurchaseItem::with(['purchase.supplier', 'purchase.user', 'product']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('purchase', function ($q) use ($search) {
                    $q->where('invoice_number', 'like', '%' . $search . '%')
                        ->orWhereHas('supplier', function ($q) use ($search) {
                            $q->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                })
                ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('purchase_date', '>=', $request->date_from);
            });
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('purchase_date', '<=', $request->date_to);
            });
        }

        // Expiry filter
        if ($request->has('expiry_filter') && $request->expiry_filter != '') {
            $today = now()->format('Y-m-d');
            $thirtyDaysFromNow = now()->addDays(30)->format('Y-m-d');

            switch ($request->expiry_filter) {
                case 'expired':
                    $query->where('expired_date', '<', $today);
                    break;
                case 'soon':
                    $query->where('expired_date', '>=', $today)
                          ->where('expired_date', '<=', $thirtyDaysFromNow);
                    break;
                case 'valid':
                    $query->where(function ($q) use ($thirtyDaysFromNow) {
                        $q->where('expired_date', '>', $thirtyDaysFromNow)
                          ->orWhereNull('expired_date');
                    });
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'purchase_date');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['id', 'purchase_date', 'created_at', 'invoice_number'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'purchase_date';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

        // Handle sorting
        if ($sortBy === 'purchase_date') {
            $query->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                  ->orderBy('purchases.purchase_date', $sortOrder)
                  ->select('purchase_items.*');
        } elseif ($sortBy === 'invoice_number') {
            $query->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                  ->orderBy('purchases.invoice_number', $sortOrder)
                  ->select('purchase_items.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Get all data (no pagination for export)
        $purchaseItems = $query->get();

        // Transform data untuk export
        return $purchaseItems->map(function ($item) {
            return [
                'invoice_number' => $item->purchase->invoice_number,
                'purchase_date' => Carbon::parse($item->purchase->purchase_date)->format('d-m-Y'),
                'supplier_name' => $item->purchase->supplier ? 
                    $item->purchase->supplier->first_name . ' ' . $item->purchase->supplier->last_name : 
                    'Tidak Ada Supplier',
                'product_name' => $item->product->name ?? 'Produk Tidak Ditemukan',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->quantity * $item->price,
                'expired_date' => $item->expired_date ? Carbon::parse($item->expired_date)->format('d-m-Y') : '-',
                'user_name' => $item->purchase->user ? $item->purchase->user->getFullname() : 'User Tidak Ditemukan'
            ];
        });
    }

    public function create()
    {
        $suppliers = Supplier::with('products')->get();
        $products = Product::all();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Simpan pembelian baru beserta itemnya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.expired_date' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => Auth::id(),
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => 0
            ]);

            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalAmount += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'expired_date' => $item['expired_date'] ?? null,
                ]);

                // update stok produk
                $product = Product::find($item['product_id']);
                $product->increment('quantity', $item['quantity']);
            }

            $purchase->update(['total_amount' => $totalAmount]);
            DB::commit();

            return redirect()->route('purchases.index')->with('success', __('purchase.success_creating'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', __('purchase.error_creating'));
        }
    }

    /**
     * Tampilkan 1 purchase.
     */
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product', 'user'])->findOrFail($id);
        return response()->json($purchase);
    }

    public function edit($id)
    {
        $purchase = Purchase::with(['items.product', 'supplier'])->findOrFail($id);
        $suppliers = Supplier::with('products')->get();
        $products = Product::all();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update purchase dan itemnya.
     */
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.expired_date' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            // Hapus item lama (stok bisa disesuaikan jika perlu rollback stok lama)
            foreach ($purchase->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                $product->decrement('quantity', $oldItem->quantity);
                $oldItem->delete();
            }

            // Update purchase
            $purchase->update([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'purchase_date' => $validated['purchase_date'],
            ]);

            // Tambah item baru
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalAmount += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'expired_date' => $item['expired_date'] ?? null,
                ]);

                $product = Product::find($item['product_id']);
                $product->increment('quantity', $item['quantity']);
            }

            $purchase->update(['total_amount' => $totalAmount]);
            DB::commit();

            return redirect()->route('purchases.index')->with('success', __('purchase.success_updating'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', __('purchase.error_updating'));
        }
    }

    /**
     * Hapus purchase (otomatis hapus item karena cascadeOnDelete).
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        // Kurangi stok produk terlebih dahulu
        foreach ($purchase->items as $item) {
            $product = Product::find($item->product_id);
            $product->decrement('quantity', $item->quantity);
        }

        $purchase->delete();
        return response()->json(['message' => 'Purchase deleted successfully']);
    }
}