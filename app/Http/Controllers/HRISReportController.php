<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics')
            ->whereDate('biometricsDate', $date)
            ->orderBy('biometricsTimeIn')
            ->get();

        return view('reports.daily', compact('data', 'date'));
    }

    public function late(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics')
            ->whereDate('biometricsDate', $date)
            ->whereTime('biometricsTimeIn', '>', '08:00:00')
            ->orderBy('biometricsTimeIn')
            ->get();

        return view('reports.late', compact('data', 'date'));
    }

    public function noTimeOut(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics')
            ->whereDate('biometricsDate', $date)
            ->where(function ($q) {
                $q->whereNull('biometricsTimeOut')
                ->orWhere('biometricsTimeOut', '');
            })
            ->orderBy('biometricsTimeIn')
            ->get();

        return view('reports.notimeout', compact('data', 'date'));
    }
}