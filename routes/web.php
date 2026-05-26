<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HRISReportController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/reports/daily', [HRISReportController::class, 'daily']);
Route::get('/reports/late', [ReportController::class, 'late']);
Route::get('/reports/notimeout', [HRISReportController::class, 'noTimeOut']);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/reports/no-time-out', [ReportController::class, 'noTimeOut']);
Route::get('/reports/no-time-in', [ReportController::class, 'noTimeIn']);