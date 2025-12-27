<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:dashboard.view', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::with(['items', 'payments'])->get();
        $customers_count = Customer::count();
        $products_count = Product::count();

        $low_stock_products = Product::where('quantity', '<', 10)->get();

        // Fixed queries - specify all columns needed or use aggregates
        $bestSellingProducts = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at',
                DB::raw('SUM(order_items.quantity) AS total_sold')
            )
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at'
            )
            ->havingRaw('SUM(order_items.quantity) > 10')
            ->get();

        $currentMonthBestSelling = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at',
                DB::raw('SUM(order_items.quantity) AS total_sold')
            )
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereYear('orders.created_at', date('Y'))
            ->whereMonth('orders.created_at', date('m'))
            ->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at'
            )
            ->havingRaw('SUM(order_items.quantity) > 500')
            ->get();

        $pastSixMonthsHotProducts = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at',
                DB::raw('SUM(order_items.quantity) AS total_sold')
            )
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', now()->subMonths(6))
            ->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.quantity',
                'products.description',
                'products.image',
                'products.barcode',
                'products.status',
                'products.created_at',
                'products.updated_at'
            )
            ->havingRaw('SUM(order_items.quantity) > 1000')
            ->get();

        $topProfitableProducts = OrderItem::with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items, $productId) {
                $product = $items->first()->product;
                $qty = $items->sum('quantity');
                $sales = $items->sum('subtotal');
                $avgCost = PurchaseItem::where('product_id', $productId)->avg('price') ?? 0;
                $cost = $avgCost * $qty;
                $profit = $sales - $cost;
                $margin = $sales > 0 ? ($profit / $sales) * 100 : 0;

                return [
                    'product_name' => $product?->name ?? 'Unknown',
                    'qty_sold' => $qty,
                    'total_sales' => $sales,
                    'total_cost' => $cost,
                    'profit' => $profit,
                    'margin' => $margin,
                    'image' => $product?->image,
                    'id' => $product?->id,
                ];
            })
            ->sortByDesc('profit')
            ->take(10)
            ->values();

        return view('home', [
            'orders_count' => $orders->count(),
            'income' => $orders->map(function ($i) {
                return $i->receivedAmount() > $i->total() ? $i->total() : $i->receivedAmount();
            })->sum(),
            'income_today' => $orders->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->map(function ($i) {
                return $i->receivedAmount() > $i->total() ? $i->total() : $i->receivedAmount();
            })->sum(),
            'customers_count' => $customers_count,
            'products_count' => $products_count,
            'low_stock_products' => $low_stock_products,
            'best_selling_products' => $bestSellingProducts,
            'current_month_products' => $currentMonthBestSelling,
            'past_months_products' => $pastSixMonthsHotProducts,
            'top_profitable_products' => $topProfitableProducts,
        ]);
    }
}
