<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Exports\ExportableTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    use ExportableTrait;

    function __construct()
    {
        $this->middleware('permission:adjustments.view', ['only' => ['index']]);
        $this->middleware('permission:adjustments.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adjustments.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adjustments.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Adjustment::with(['product', 'user']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        // Type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Date filters
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('adjusted_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('adjusted_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'adjusted_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validasi field sorting untuk menghindari SQL injection
        $allowedSortColumns = ['id', 'product_id', 'type', 'quantity', 'user_id', 'adjusted_at', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'adjusted_at';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';

        // Handle sorting untuk relationship columns
        if ($sortBy === 'product_id') {
            $query->join('products', 'adjustments.product_id', '=', 'products.id')
                ->orderBy('products.name', $sortOrder)
                ->select('adjustments.*');
        } elseif ($sortBy === 'user_id') {
            $query->join('users', 'adjustments.user_id', '=', 'users.id')
                ->orderBy('users.first_name', $sortOrder)
                ->orderBy('users.last_name', $sortOrder)
                ->select('adjustments.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $adjustments = $query->paginate(10)->appends($request->query());

        return view('adjustments.index', compact('adjustments'));
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $adjustments = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'product_name' => 'Nama Produk',
            'type' => 'Tipe Adjustment',
            'quantity' => 'Jumlah',
            'reason' => 'Alasan',
            'user_name' => 'User',
            'adjusted_at' => 'Tanggal Adjustment',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToPdf($adjustments, $columns, 'Laporan Adjustment');
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $adjustments = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'product_name' => 'Nama Produk',
            'type' => 'Tipe Adjustment',
            'quantity' => 'Jumlah',
            'reason' => 'Alasan',
            'user_name' => 'User',
            'adjusted_at' => 'Tanggal Adjustment',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToCsv($adjustments, $columns, 'Laporan Adjustment');
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = Adjustment::with(['product', 'user']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        // Type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Date filters
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('adjusted_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('adjusted_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'adjusted_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validasi field sorting untuk menghindari SQL injection
        $allowedSortColumns = ['id', 'product_id', 'type', 'quantity', 'user_id', 'adjusted_at', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'adjusted_at';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';

        // Handle sorting untuk relationship columns
        if ($sortBy === 'product_id') {
            $query->join('products', 'adjustments.product_id', '=', 'products.id')
                ->orderBy('products.name', $sortOrder)
                ->select('adjustments.*');
        } elseif ($sortBy === 'user_id') {
            $query->join('users', 'adjustments.user_id', '=', 'users.id')
                ->orderBy('users.first_name', $sortOrder)
                ->orderBy('users.last_name', $sortOrder)
                ->select('adjustments.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Get all data (no pagination for export)
        $adjustments = $query->get();

        // Transform data untuk export
        return $adjustments->map(function ($adjustment) {
            return [
                'id' => $adjustment->id,
                'product_name' => $adjustment->product->name ?? 'Produk Tidak Ditemukan',
                'type' => $this->getTypeText($adjustment->type),
                'quantity' => $adjustment->quantity,
                'reason' => $adjustment->reason ?? '-',
                'user_name' => $adjustment->user ? $adjustment->user->getFullname() : 'User Tidak Ditemukan',
                'adjusted_at' => $adjustment->adjusted_at->format('d-m-Y H:i'),
                'created_at' => $adjustment->created_at->format('d-m-Y H:i')
            ];
        });
    }

    /**
     * Get type text for display
     */
    private function getTypeText($type)
    {
        return $type === 'increase' ? 'Increase' : 'Decrease';
    }

    public function create()
    {
        $products = Product::all();
        return view('adjustments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:191',
            'adjusted_at' => 'required|date'
        ]);

        DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'adjusted_at' => $request->adjusted_at,
            ]);

            // Update product stock
            $product = Product::find($request->product_id);
            if ($request->type === 'increase') {
                $product->increment('quantity', $request->quantity);
            } else {
                $product->decrement('quantity', $request->quantity);
            }
        });

        return redirect()->route('adjustments.index')
            ->with('success', __('adjustment.success_creating'));
    }

    public function edit(Adjustment $adjustment)
    {
        $products = Product::all();
        return view('adjustments.edit', compact('adjustment', 'products'));
    }

    public function update(Request $request, Adjustment $adjustment)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:191',
            'adjusted_at' => 'required|date'
        ]);

        DB::transaction(function () use ($request, $adjustment) {
            // Revert previous adjustment
            $product = Product::find($adjustment->product_id);
            if ($adjustment->type === 'increase') {
                $product->decrement('quantity', $adjustment->quantity);
            } else {
                $product->increment('quantity', $adjustment->quantity);
            }

            // Update adjustment
            $adjustment->update([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'adjusted_at' => $request->adjusted_at,
            ]);

            // Apply new adjustment
            $newProduct = Product::find($request->product_id);
            if ($request->type === 'increase') {
                $newProduct->increment('quantity', $request->quantity);
            } else {
                $newProduct->decrement('quantity', $request->quantity);
            }
        });

        return redirect()->route('adjustments.index')
            ->with('success', __('adjustment.success_updating'));
    }

    public function destroy(Adjustment $adjustment)
    {
        DB::transaction(function () use ($adjustment) {
            // Revert stock adjustment
            $product = Product::find($adjustment->product_id);
            if ($adjustment->type === 'increase') {
                $product->decrement('quantity', $adjustment->quantity);
            } else {
                $product->increment('quantity', $adjustment->quantity);
            }

            $adjustment->delete();
        });

        return redirect()->route('adjustments.index')
            ->with('success', __('adjustment.success_deleting'));
    }
}