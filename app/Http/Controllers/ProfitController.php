<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Adjustment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProfitController extends Controller
{
    public function index(Request $request)
    {
        // ===============================
        // DATE RANGE
        // ===============================
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // ===============================
        // ORDERS & ORDER ITEMS
        // ===============================
        $orders = Order::with('items')->whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59'
        ])->get();

        $orderItems = $orders->flatMap(fn($order) => $order->items);

        // ===============================
        // 1. REVENUE (PENDAPATAN) - UANG YANG DITERIMA
        // ===============================
        $revenue = $orders->sum(fn($order) => min($order->receivedAmount(), $order->total()));

        // ===============================
        // 2. COGS (HPP BARANG TERJUAL) BERDASARKAN AVERAGE COST
        // ===============================
        $cogs = 0;
        foreach ($orderItems as $item) {
            $avgCost = PurchaseItem::where('product_id', $item->product_id)->avg('price') ?? 0;
            $cogs += $avgCost * $item->quantity;
        }

        // ===============================
        // 3. STOCK LOSS (ADJUSTMENT DECREASE)
        // ===============================
        $stockLoss = 0;
        $adjustmentsDecrease = Adjustment::where('type', 'decrease')
            ->whereBetween('adjusted_at', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59'
            ])->get();

        foreach ($adjustmentsDecrease as $adj) {
            $avgCost = PurchaseItem::where('product_id', $adj->product_id)->avg('price') ?? 0;
            $stockLoss += $avgCost * $adj->quantity;
        }

        $totalCogs = $cogs + $stockLoss;

        // ===============================
        // 4. GROSS PROFIT
        // ===============================
        $grossProfit = $revenue - $totalCogs;
        $grossProfitMargin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

        // ===============================
        // 5. OPERATING EXPENSES
        // ===============================
        $operatingExpenses = Expense::whereBetween('expense_date', [
            $dateFrom,
            $dateTo
        ])->sum('amount');

        // ===============================
        // 6. NET PROFIT
        // ===============================
        $netProfit = $grossProfit - $operatingExpenses;
        $netProfitMargin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;

        // ===============================
        // 7. TOTAL PURCHASES
        // ===============================
        $totalPurchases = Purchase::whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59'
        ])->sum('total_amount');

        // ===============================
        // 8. PRODUCT PROFITS (TOP 10)
        // ===============================
        $productProfits = $orderItems
            ->groupBy('product_id')
            ->map(function ($items, $productId) {
                $product = $items->first()->product;
                $qty = $items->sum('quantity');
                $sales = $items->sum('subtotal');

                $avgCost = PurchaseItem::where('product_id', $productId)->avg('price') ?? 0;
                $cost = $avgCost * $qty;

                // Calculate profit and margin
                $profit = $sales - $cost;
                $margin = $sales > 0 ? (($profit < 0 ? 0 : $profit) / $sales) * 100 : 0;

                // Check for products where profit is negative or margin is zero
                if ($profit <= 0) {
                    $warningMessage = "Warning: Product {$product->name} has no profit margin due to high purchase cost.";
                    // You could log this warning or display it to the admin
                }

                return [
                    'product_name' => $product?->name ?? 'Unknown',
                    'qty_sold' => $qty,
                    'total_sales' => $sales,
                    'total_cost' => $cost,
                    'profit' => $profit,
                    'margin' => $margin
                ];
            })
            ->sortByDesc('profit')
            ->take(10)
            ->values();

        // ===============================
        // 9. STATS
        // ===============================
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $revenue / $totalOrders : 0;

        // ===============================
        // 10. DAILY CHART DATA
        // ===============================
        $dailyData = [];
        $current = Carbon::parse($dateFrom);
        $end = Carbon::parse($dateTo);

        while ($current <= $end) {
            $date = $current->format('Y-m-d');

            $dailyOrders = $orders->filter(fn($o) => $o->created_at->format('Y-m-d') === $date);
            $dailyItems = $dailyOrders->flatMap(fn($o) => $o->items);

            $dailyRevenue = $dailyOrders->sum(fn($o) => min($o->receivedAmount(), $o->total()));

            $dailyCogs = 0;
            foreach ($dailyItems as $item) {
                $avgCost = PurchaseItem::where('product_id', $item->product_id)->avg('price') ?? 0;
                $dailyCogs += $avgCost * $item->quantity;
            }

            $dailyStockLoss = 0;
            $dailyAdjustments = Adjustment::where('type', 'decrease')
                ->whereDate('adjusted_at', $date)
                ->get();

            foreach ($dailyAdjustments as $adj) {
                $avgCost = PurchaseItem::where('product_id', $adj->product_id)->avg('price') ?? 0;
                $dailyStockLoss += $avgCost * $adj->quantity;
            }

            $dailyData[] = [
                'date' => $current->format('d M'),
                'revenue' => $dailyRevenue,
                'cost' => $dailyCogs + $dailyStockLoss,
                'profit' => $dailyRevenue - ($dailyCogs + $dailyStockLoss)
            ];

            $current->addDay();
        }

        // ===============================
        // RETURN VIEW
        // ===============================
        return view('profit.index', compact(
            'dateFrom',
            'dateTo',
            'revenue',
            'cogs',
            'stockLoss',
            'totalCogs',
            'grossProfit',
            'grossProfitMargin',
            'operatingExpenses',
            'netProfit',
            'netProfitMargin',
            'totalPurchases',
            'productProfits',
            'totalOrders',
            'averageOrderValue',
            'dailyData'
        ));
    }
}
