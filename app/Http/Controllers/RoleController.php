<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index', [
            'roles' => Role::all()
        ]);
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:50|unique:roles,role_name',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_create', true)
                ->with('form_context', 'create');
        }

        Role::create([
            'role_name' => $request->role_name,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:50|unique:roles,role_name,' . $role->id,
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit', true)
                ->with('form_context', 'edit');
        }

        $role->update([
            'role_name' => $request->role_name,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return back()->withErrors([
                'error' => 'Role is still used by users'
            ]);
        }

        $role->delete();

        return back()->with('success', 'Role deleted');
    }
}
