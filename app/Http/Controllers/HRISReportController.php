<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('tbl_biometrics')
            ->whereDate('time_in', $date)
            ->get();

        return view('reports.daily', compact('data', 'date'));
    }
    public function late(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('tbl_biometrics')
            ->whereDate('time_in', $date)
            ->whereTime('time_in', '>', '08:00:00')
            ->get();

        return view('reports.late', compact('data', 'date'));
    }    
    public function noTimeOut()
    {
        $data = DB::table('tbl_biometrics')
            ->whereNull('time_out')
            ->orWhere('time_out', '')
            ->get();

        return view('reports.notimeout', compact('data'));
    }
}
