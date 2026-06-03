<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'department_id' => 'nullable|integer',
            'role' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'role' => $request->role ?? 'employee',

        ]);

        return redirect('/login')->with('success', 'Registration successful.');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',        
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'hr' => redirect('/hr/dashboard'),
                default => redirect('/employee/dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}