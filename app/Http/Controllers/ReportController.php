<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ================= GET RAW LOGS =================
    private function getLogs($from, $to)
    {
        return DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('b.date_log', [$from, $to]);
            })
            ->orderBy('b.employee_no')
            ->orderBy('b.date_log')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no',
                'b.date_log',
                'b.time_log',
                'b.status',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();
    }

    // ================= BUILD ATTENDANCE =================
    private function buildAttendance($logs)
    {
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

            $status = strtoupper(trim($log->status));

            if ($status === 'IN') {
                $data[$key]['time_in'] = $log->time_log;
            }

            if ($status === 'OUT') {
                $data[$key]['time_out'] = $log->time_log;
            }
        }

        return $data;
    }

    // ================= DAILY =================
    public function daily(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('reports.daily', [
                'data' => [],
                'from' => $from,
                'to'   => $to
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        return view('reports.daily', [
            'data' => array_values($data),
            'from' => $from,
            'to'   => $to
        ]);
    }

    // ================= NO TIME OUT =================
    public function noTimeOut(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('reports.no_time_out', [
                'data' => [],
                'from' => null,
                'to'   => null
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        $filtered = array_filter($data, function ($row) {
            return empty($row['time_out']);
        });

        return view('reports.no_time_out', [
            'data' => array_values($filtered),
            'from' => $from,
            'to'   => $to
        ]);
    }

    // ================= NO TIME IN =================
    public function noTimeIn(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('reports.no_time_in', [
                'data' => [],
                'from' => null,
                'to'   => null
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        $filtered = array_filter($data, function ($row) {
            return empty($row['time_in']);
        });

        return view('reports.no_time_in', [
            'data' => array_values($filtered),
            'from' => $from,
            'to'   => $to
        ]);
    }
}