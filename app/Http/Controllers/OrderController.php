<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = new Order();

        // Filter tanggal
        if ($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter customer type
        if ($request->customer_type == 'walk_in') {
            $orders = $orders->whereNull('customer_id');
        } elseif ($request->customer_type == 'registered') {
            $orders = $orders->whereNotNull('customer_id');
        }

        $orders = $orders->with(['items.product', 'payments', 'customer'])->latest();

        // Filter status
        if ($request->status) {
            $orders = $orders->get()->filter(function ($order) use ($request) {
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
            $orders = $orders->paginate(10);
        }

        $total = $orders->sum(function ($i) {
            return $i->total();
        });
        $receivedAmount = $orders->sum(function ($i) {
            return $i->receivedAmount();
        });

        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
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
        // return $request;
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
}
