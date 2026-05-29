<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentMiddleware
{
    public function handle(Request $request, Closure $next, $department)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Safe check (prevents null error)
        if (!$user->department_id || $user->department->id != $department) {
            abort(403);
        }
        return $next($request);
    }
}