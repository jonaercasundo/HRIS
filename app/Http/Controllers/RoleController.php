<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }
        public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        // TEMP: Replace with DB insert or Spatie Role model later
        // Role::create([...]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }
}
