<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
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
                'b.tag',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName")
            )
            ->get();
    }

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

            $tag = strtoupper(trim($log->tag ?? ''));

            if ($tag === 'IN') {
                // keep earliest IN
                if (!$data[$key]['time_in']) {
                    $data[$key]['time_in'] = $log->time_log;
                }
            }

            if ($tag === 'OUT') {
                // keep latest OUT
                $data[$key]['time_out'] = $log->time_log;
            }
        }

        return $data;
    }

    public function daily(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('hr.daily', [
                'data' => [],
                'from' => $from,
                'to'   => $to
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        return view('hr.daily', [
            'data' => array_values($data),
            'from' => $from,
            'to'   => $to
        ]);
    }

    public function noTimeOut(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('hr.no_time_out', [
                'data' => [],
                'from' => null,
                'to'   => null
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        $filtered = array_filter($data, fn($row) => empty($row['time_out']));

        return view('hr.no_time_out', [
            'data' => array_values($filtered),
            'from' => $from,
            'to'   => $to
        ]);
    }

    public function noTimeIn(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('hr.no_time_in', [
                'data' => [],
                'from' => null,
                'to'   => null
            ]);
        }

        $logs = $this->getLogs($from, $to);
        $data = $this->buildAttendance($logs);

        $filtered = array_filter($data, fn($row) => empty($row['time_in']));

        return view('hr.no_time_in', [
            'data' => array_values($filtered),
            'from' => $from,
            'to'   => $to
        ]);
    }
    public function late(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return view('hr.late', [
                'data' => [],
                'from' => null,
                'to'   => null
            ]);
        }

        $query = DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', function ($join) {
                $join->on(DB::raw('TRIM(b.employee_no)'), '=', DB::raw('TRIM(e.employeeNo)'));
            })
            ->where('b.tag', 'IN')
            ->whereTime('b.time_log', '>', '08:00:00')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('b.date_log', [$from, $to]);
            })
            ->orderBy('b.employee_no')
            ->orderBy('b.date_log');

        $data = $query->select(
            'b.employee_no as employeeNo',
            'b.date_log',
            'b.time_log',
            DB::raw("
                COALESCE(
                    CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName),
                    b.employee_no
                ) as employeeName
            ")
        )->get();

        return view('hr.late', compact('data', 'from', 'to'));
    }
}