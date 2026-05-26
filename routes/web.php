<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HRISReportController;
use App\Http\Controllers\ReportController;


Route::get('/reports/daily', [ReportController::class, 'daily']);
Route::get('/reports/late', [ReportController::class, 'late']);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/reports/no-time-out', [ReportController::class, 'noTimeOut']);
Route::get('/reports/no-time-in', [ReportController::class, 'noTimeIn']);