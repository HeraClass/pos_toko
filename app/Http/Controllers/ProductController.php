<?php

namespace App\Http\Controllers;

use App\Exports\ExportableTrait;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ExportableTrait;
    
    function __construct()
    {
        $this->middleware('permission:products.view', ['only' => ['index', 'show', 'priceInfo', 'priceStatistics']]);
        $this->middleware('permission:products.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products.delete', ['only' => ['destroy']]);    
    }

    // Helper untuk menghindari pengulangan filter di index dan export
    private function applyFilters($query, Request $request)
    {
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('barcode', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->status && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status === 'active' ? 1 : 0);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSort = ['id', 'name', 'barcode', 'price', 'quantity', 'created_at'];

        $query->orderBy(
            in_array($sortBy, $allowedSort) ? $sortBy : 'created_at',
            in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc'
        );

        return $query;
    }

    public function index(Request $request)
    {
        // Optimasi: Ambil cost_price via Subquery (Hanya 1 Query ke Database)
        $query = Product::with('category')->addSelect([
            'cost_price' => PurchaseItem::select('price')
                ->whereColumn('product_id', 'products.id')
                ->latest()
                ->limit(1)
        ]);

        $products = $this->applyFilters($query, $request)->paginate(10)->appends($request->query());

        // Hitung margin secara dinamis tanpa query tambahan di loop
        $products->getCollection()->transform(function ($product) {
            $product->profit_margin = $product->cost_price > 0
                ? (($product->price - $product->cost_price) / $product->cost_price * 100)
                : 0;
            return $product;
        });

        if (request()->wantsJson()) {
            return ProductResource::collection($products);
        }

        return view('products.index', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $products = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'name' => 'Nama Produk',
            'barcode' => 'Barcode',
            'category_name' => 'Kategori',
            'cost_price' => 'Harga Beli',
            'price' => 'Harga Jual',
            'profit_margin' => 'Margin (%)',
            'quantity' => 'Stok',
            'status' => 'Status',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToPdf($products, $columns, 'Laporan Produk');
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        // Handle both POST and GET requests
        $filters = $request->isMethod('post') ? $request->all() : $request->query();

        $products = $this->getExportData(new Request($filters));

        $columns = [
            'id' => 'ID',
            'name' => 'Nama Produk',
            'barcode' => 'Barcode',
            'category_name' => 'Kategori',
            'cost_price' => 'Harga Beli',
            'price' => 'Harga Jual',
            'profit_margin' => 'Margin (%)',
            'quantity' => 'Stok',
            'status' => 'Status',
            'created_at' => 'Dibuat Pada'
        ];

        return $this->exportToCsv($products, $columns, 'Laporan Produk');
    }

    /**
     * Get data for export (reuse the index query logic)
     */
    private function getExportData(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('barcode', 'LIKE', "%{$request->search}%");
        }

        // Status filter
        if ($request->status && in_array($request->status, ['active', 'inactive'])) {
            $statusValue = $request->status === 'active' ? 1 : 0;
            $query->where('status', $statusValue);
        }

        // Category filter
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort columns to prevent SQL injection
        $allowedSortColumns = ['id', 'name', 'barcode', 'price', 'quantity', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Get all data (no pagination for export)
        $products = $query->get();

        // Transform data untuk export
        return $products->map(function ($product) {
            // Hitung harga beli rata-rata berdasarkan pembelian terakhir
            $latestPurchase = PurchaseItem::where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $costPrice = $latestPurchase ? $latestPurchase->price : 0;
            $profitMargin = $latestPurchase ?
                (($product->price - $latestPurchase->price) / $latestPurchase->price * 100) : 0;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode ?? '-',
                'category_name' => $product->category ? $product->category->name : 'Tidak Ada Kategori',
                'cost_price' => number_format($costPrice, 2),
                'price' => $product->price,
                'profit_margin' => number_format($profitMargin, 2) . '%',
                'quantity' => $product->quantity,
                'status' => $product->status ? 'Aktif' : 'Tidak Aktif',
                'created_at' => $product->created_at->format('d-m-Y H:i')
            ];
        });
    }

    /**
     * Show detailed price information for a product
     */
    public function priceInfo($id)
    {
        $product = Product::findOrFail($id);

        // Get purchase history for this product
        $purchases = PurchaseItem::where('product_id', $id)
            ->with('purchase')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate average cost price
        $averageCost = $purchases->avg('price');

        // Get lowest and highest purchase price
        $lowestCost = $purchases->min('price');
        $highestCost = $purchases->max('price');

        // Calculate profit margin
        $profitMargin = $averageCost > 0 ?
            (($product->price - $averageCost) / $averageCost * 100) : 0;

        return response()->json([
            'product' => $product,
            'purchases' => $purchases,
            'price_info' => [
                'cost_price' => $averageCost,
                'selling_price' => $product->price,
                'profit_margin' => $profitMargin,
                'lowest_cost' => $lowestCost,
                'highest_cost' => $highestCost,
                'total_purchases' => $purchases->count(),
                'total_quantity' => $purchases->sum('quantity')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'category_id' => $request->category_id
        ]);

        if (!$product) {
            return redirect()->back()->with('error', __('product.error_creating'));
        }
        return redirect()->route('products.index')->with('success', __('product.success_creating'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Ambil semua pembelian produk, urut terbaru, pakai pagination
        $purchases = PurchaseItem::where('product_id', $product->id)
            ->with(['purchase', 'purchase.supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Ambil harga beli terakhir langsung dari DB (bukan dari paginated collection)
        $latestPurchase = PurchaseItem::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $latestCost = $latestPurchase ? $latestPurchase->price : 0;

        // Harga beli rata-rata, min, max
        $averageCost = PurchaseItem::where('product_id', $product->id)->avg('price');
        $lowestCost = PurchaseItem::where('product_id', $product->id)->min('price');
        $highestCost = PurchaseItem::where('product_id', $product->id)->max('price');

        // Margin berdasarkan harga beli terakhir
        $profitMargin = $latestCost ? ($product->price - $latestCost) / $latestCost * 100 : 0;

        // Flag alert jika harga beli terakhir lebih tinggi dari harga jual
        $isCostHigherThanPrice = $latestCost > $product->price;

        // Siapkan data untuk view
        $priceStats = [
            'latest_cost' => $latestCost,
            'average_cost' => $averageCost,
            'lowest_cost' => $lowestCost,
            'highest_cost' => $highestCost,
            'selling_price' => $product->price,
            'profit_margin' => $profitMargin,
            'is_cost_higher_than_price' => $isCostHigherThanPrice,
            'total_purchases' => PurchaseItem::where('product_id', $product->id)->count(),
            'total_quantity' => PurchaseItem::where('product_id', $product->id)->sum('quantity')
        ];

        return view('products.show', compact('product', 'purchases', 'priceStats'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        // Get last purchase price as cost price reference
        $lastPurchase = PurchaseItem::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $cost_price = $lastPurchase ? $lastPurchase->price : 0;

        return view('products.edit', compact('product', 'categories', 'cost_price'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->name = $request->name;
        $product->description = $request->description;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->status = $request->status;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete($product->image);
            }
            // Store image
            $image_path = $request->file('image')->store('products', 'public');
            // Save to Database
            $product->image = $image_path;
        }

        if (!$product->save()) {
            return redirect()->back()->with('error', __('product.error_updating'));
        }
        return redirect()->route('products.index')->with('success', __('product.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Products Successfully Deleted');
    }
    
    /**
     * Get product price statistics
     */
    public function priceStatistics()
    {
        $products = Product::all();
        $stats = [];

        foreach ($products as $product) {
            $purchases = PurchaseItem::where('product_id', $product->id)->get();
            $avgCost = $purchases->avg('price');

            if ($avgCost > 0) {
                $stats[] = [
                    'product' => $product->name,
                    'cost_price' => $avgCost,
                    'selling_price' => $product->price,
                    'profit' => $product->price - $avgCost,
                    'margin' => (($product->price - $avgCost) / $avgCost) * 100,
                    'quantity' => $product->quantity
                ];
            }
        }

        return response()->json(['price_statistics' => $stats]);
    }
}