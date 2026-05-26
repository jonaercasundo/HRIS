<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $logs = DB::table('zkteco_dtr_log as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereDate('b.date_log', $date)
            ->orderBy('b.employee_no')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no as employeeNo',
                'b.date_log',
                'b.time_log',
                'b.status',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        $data = [];

        foreach ($logs as $log) {

            $key = $log->employeeNo . '_' . $log->date_log;

            if (!isset($data[$key])) {
                $data[$key] = [
                    'employeeNo' => $log->employeeNo,
                    'employeeName' => $log->employeeName,
                    'date_log' => $log->date_log,
                    'time_in' => null,
                    'time_out' => null,
                ];
            }

            // IF STATUS EXISTS
            if ($log->status === 'IN') {
                $data[$key]['time_in'] = $log->time_log;
            } elseif ($log->status === 'OUT') {
                $data[$key]['time_out'] = $log->time_log;
            }

            // IF STATUS IS NULL → fallback logic
            if ($log->status === null) {
                if (!$data[$key]['time_in']) {
                    $data[$key]['time_in'] = $log->time_log;
                } else {
                    $data[$key]['time_out'] = $log->time_log;
                }
            }
        }

        return view('reports.daily', [
            'data' => array_values($data),
            'date' => $date
        ]);
    }
    public function late(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('zkteco_dtr_log as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereDate('b.date_log', $date)
            ->where('b.status', 'IN')
            ->whereTime('b.time_log', '>', '08:00:00')
            ->orderBy('b.employee_no')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no as employeeNo',
                'b.date_log',
                'b.time_log',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        return view('reports.late', [
            'data' => $data,
            'date' => $date
        ]);
    }

    public function noTimeOut(Request $request)
    {
        // This method is now handled by ReportController
        return redirect()->route('reports.no-time-out');
    }
}