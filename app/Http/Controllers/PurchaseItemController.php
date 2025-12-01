<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseItemController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchases.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:purchases.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchases.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchases.delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $items = PurchaseItem::with(['purchase', 'product'])->latest()->paginate(10);
        return view('purchase_items.index', compact('items'));
    }

    public function create()
    {
        $purchases = Purchase::all();
        $products = Product::all();
        return view('purchase_items.create', compact('purchases', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'expired_date' => 'nullable|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        PurchaseItem::create($request->all());

        return redirect()->route('purchase-items.index')->with('success', 'Purchase item created successfully.');
    }

    public function show(PurchaseItem $purchaseItem)
    {
        $purchaseItem->load(['purchase', 'product']);
        return view('purchase_items.show', compact('purchaseItem'));
    }

    public function edit(PurchaseItem $purchaseItem)
    {
        $purchases = Purchase::all();
        $products = Product::all();
        return view('purchase_items.edit', compact('purchaseItem', 'purchases', 'products'));
    }

    public function update(Request $request, PurchaseItem $purchaseItem)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'expired_date' => 'nullable|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $purchaseItem->update($request->all());

        return redirect()->route('purchase-items.index')->with('success', 'Purchase item updated successfully.');
    }

    public function destroy(PurchaseItem $purchaseItem)
    {
        $purchaseItem->delete();
        return redirect()->route('purchase-items.index')->with('success', 'Purchase item deleted successfully.');
    }
}
