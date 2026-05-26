<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $logs = DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereDate('b.date_log', $date)
            ->orderBy('b.employee_no')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no',
                'b.date_log',
                'b.time_log',
                'b.status',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();

        $data = [];

        foreach ($logs as $log) {

            $key = $log->employee_no . '_' . $log->date_log;

            if (!isset($data[$key])) {
                $data[$key] = [
                    'employeeNo'   => $log->employee_no,
                    'employeeName' => $log->employeeName ?? 'N/A',
                    'date_log'     => $log->date_log,
                    'time_in'      => null,
                    'time_out'     => null,
                ];
            }

            // Normalize status (important fix)
            $status = strtoupper(trim($log->status));

            if ($status === 'IN') {
                $data[$key]['time_in'] = $log->time_log;
            }

            if ($status === 'OUT') {
                $data[$key]['time_out'] = $log->time_log;
            }

            // fallback if no status exists
            if ($log->status === null || $log->status === '') {
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

        $data = DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereDate('b.date_log', $date)
            ->where('b.status', 'IN')
            ->whereTime('b.time_log', '>', '08:00:00')
            ->orderBy('b.employee_no')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no',
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
        $date = $request->date ?? date('Y-m-d');

        $data = DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereDate('b.date_log', $date)
            ->groupBy('b.employee_no', 'b.date_log', 'e.firstName', 'e.middleName', 'e.lastName')
            ->select(
                'b.employee_no',
                'b.date_log',
                DB::raw("MAX(CASE WHEN b.status='IN' THEN b.time_log END) as time_in"),
                DB::raw("MAX(CASE WHEN b.status='OUT' THEN b.time_log END) as time_out"),
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->havingRaw('time_out IS NULL OR time_out = ""')
            ->get();

        return view('reports.no_time_out', [
            'data' => $data,
            'date' => $date
        ]);
    }
}