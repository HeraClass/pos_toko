<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles.view', ['only' => ['index']]);
        $this->middleware('permission:roles.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $allowedSorts = ['id', 'name', 'created_at'];
        if (!in_array($sortBy, $allowedSorts))
            $sortBy = 'created_at';
        if (!in_array(strtolower($sortOrder), ['asc', 'desc']))
            $sortOrder = 'desc';

        $roles = Role::with('permissions')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('permissions', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        return view('roles.index', compact('roles', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        // Assign permissions ke role
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->name = $request->name;
        $role->save();

        // Update permissions
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
