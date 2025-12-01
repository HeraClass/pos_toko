<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:permissions.view')->only(['index']);
        $this->middleware('permission:permissions.create')->only(['create', 'store']);
        $this->middleware('permission:permissions.edit')->only(['edit', 'update']);
        $this->middleware('permission:permissions.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $allowedSort = ['name', 'created_at'];
        $sort_by = $request->get('sort_by', 'created_at');
        $sort_order = $request->get('sort_order', 'asc');

        if (!in_array($sort_by, $allowedSort)) {
            $sort_by = 'created_at';
        }

        if (!in_array($sort_order, ['asc', 'desc'])) {
            $sort_order = 'desc';
        }

        $permissions = Permission::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy($sort_by, $sort_order)
            ->paginate(10)
            ->withQueryString();

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission created');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permission updated');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted');
    }
}
