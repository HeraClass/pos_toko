<?php

namespace App\Http\Controllers;

use App\Exports\ExportableTrait;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ExportableTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::all();

        if (request()->wantsJson()) {
            // Tambahkan full image URL untuk response JSON
            $products->getCollection()->transform(function ($product) {
                $product->image_url = $product->image ? Storage::url($product->image) : null;
                return $product;
            });

            return ProductResource::collection($products);
        }

        return view('products.index')->with([
            'products' => $products,
            'categories' => $categories
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
            'price' => 'Harga',
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
            'price' => 'Harga',
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
            return [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode ?? '-',
                'category_name' => $product->category ? $product->category->name : 'Tidak Ada Kategori',
                'price' => $product->price,
                'quantity' => $product->quantity,
                'status' => $product->status ? 'Aktif' : 'Tidak Aktif',
                'created_at' => $product->created_at->format('d-m-Y H:i')
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
        //
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
        return view('products.edit', compact('product', 'categories'));
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

}