<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics as b')
            ->leftJoin('tbl_masterlist as e', 'e.employeeNo', '=', 'b.employeeNo')
            ->whereDate('b.biometricsDate', $date)
            ->orderBy('b.biometricsTimeIn')
            ->select(
                'b.employeeNo',
                'b.biometricsDate',
                'b.biometricsTimeIn',
                'b.biometricsTimeOut',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        return view('reports.daily', compact('data', 'date'));
    }

    public function late(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics as b')
            ->leftJoin('tbl_masterlist as e', 'e.employeeNo', '=', 'b.employeeNo')
            ->whereDate('b.biometricsDate', $date)
            ->whereTime('b.biometricsTimeIn', '>', '08:00:00')
            ->orderBy('b.biometricsTimeIn')
            ->select(
                'b.employeeNo',
                'b.biometricsDate',
                'b.biometricsTimeIn',
                'b.biometricsTimeOut',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        return view('reports.late', compact('data', 'date'));
    }

    public function noTimeOut(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('t_biometrics as b')
            ->leftJoin('tbl_masterlist as e', 'e.employeeNo', '=', 'b.employeeNo')
            ->whereDate('b.biometricsDate', $date)
            ->where(function ($q) {
                $q->whereNull('b.biometricsTimeOut')
                  ->orWhere('b.biometricsTimeOut', '');
            })
            ->orderBy('b.biometricsTimeIn')
            ->select(
                'b.employeeNo',
                'b.biometricsDate',
                'b.biometricsTimeIn',
                'b.biometricsTimeOut',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        return view('reports.notimeout', compact('data', 'date'));
    }
}