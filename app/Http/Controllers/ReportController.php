<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function noTimeOut(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $query = DB::table('t_biometrics')
            ->whereNull('biometricsTimeOut');

        if (!empty($from) && !empty($to)) {
            $query->whereBetween('biometricsDate', [$from, $to]);
        }

        $data = $query
            ->select(
                'employeeNo',
                'biometricsDate',
                'biometricsTimeIn',
                'biometricsTimeOut'
            )
            ->orderBy('biometricsDate', 'desc')
            ->orderBy('biometricsTimeIn', 'desc')
            ->get();

        return view('reports.no_time_out', compact('data', 'from', 'to'));
    }

    public function noTimeIn(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $query = DB::table('t_biometrics')
            ->whereNull('biometricsTimeIn');

        if (!empty($from) && !empty($to)) {
            $query->whereBetween('biometricsDate', [$from, $to]);
        }

        $data = $query
            ->select(
                'employeeNo',
                'biometricsDate',
                'biometricsTimeIn',
                'biometricsTimeOut'
            )
            ->orderBy('biometricsDate', 'desc')
            ->get();

        return view('reports.no_time_in', compact('data', 'from', 'to'));
    }
}