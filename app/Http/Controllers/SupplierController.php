<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            return response(Supplier::with('products')->get());
        }
        $suppliers = Supplier::with('products')->latest()->paginate(10);
        return view('suppliers.index')->with('suppliers', $suppliers);
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