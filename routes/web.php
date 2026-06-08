<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BiometricsController;
use App\Http\Controllers\hr\Employee201Controller;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |-------------------------
    | DASHBOARD REDIRECT
    |-------------------------
    */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'hr' => redirect('/hr/dashboard'),
            default => redirect('/employee/dashboard'),
        };
    })->name('dashboard');

    /*
    |-------------------------
    | HR ROUTES
    |-------------------------
    */
    Route::middleware('role:hr')->group(function () {

        Route::get('/hr/dashboard', function () {
            return view('hr.dashboard');
        });

        Route::get('/reports/daily', [ReportController::class, 'daily']);
        Route::get('/reports/no-time-out', [ReportController::class, 'noTimeOut']);
        Route::get('/reports/no-time-in', [ReportController::class, 'noTimeIn']);
        Route::get('/bio-dtr', [BiometricsController::class, 'index']);
        Route::get('/bio-dtr-sync', [BiometricsController::class, 'syncBio1']);
        Route::get('/reports', [ReportController::class, 'lateReport'])->name('reports.late');
        Route::post('/reports/filter', [ReportController::class, 'filter']);
        Route::get('/reports/nte/{employeeNo}', [ReportController::class, 'generateNTE']);
        Route::get('/reports/late/details/{employeeNo}', [ReportController::class, 'lateDetails']);
        Route::get('/employee-201', [Employee201Controller::class, 'index']);
        Route::post('/employee-201/save-email', [Employee201Controller::class, 'saveEmail']);
    });

    /*
    |-------------------------
    | ADMIN ROUTES
    |-------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Admin dashboard (CORRECT)
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        });

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });
    /*
    |-------------------------
    | EMPLOYEE ROUTES
    |-------------------------
    */
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/dashboard', function () {
            return view('employee.dashboard');
        });
    });
});