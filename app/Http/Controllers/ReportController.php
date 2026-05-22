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

        $query = DB::table('zkteco_dtr_log as in_log')
            ->where('in_log.status', 'IN')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('zkteco_dtr_log as out_log')
                    ->whereColumn('out_log.employee_no', 'in_log.employee_no')
                    ->whereColumn('out_log.date_log', 'in_log.date_log')
                    ->where('out_log.status', 'OUT');
            });

        // ✅ DATE FILTER (IMPORTANT PART)
        if ($from && $to) {
            $query->whereBetween('in_log.date_log', [$from, $to]);
        }

        $data = $query->orderBy('in_log.date_log', 'desc')->get();

        return view('reports.no_time_out', compact('data', 'from', 'to'));
    }
    public function noTimeIn(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $logs = DB::table('zkteco_dtr_log')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('date_log', [$from, $to]);
            })
            ->orderBy('employee_no')
            ->orderBy('date_log')
            ->orderBy('time_log')
            ->get();

        $grouped = [];

        foreach ($logs as $log) {
            $key = $log->employee_no . '_' . $log->date_log;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'employee_no' => $log->employee_no,
                    'bio_name' => $log->bio_name,
                    'date_log' => $log->date_log,
                    'time_in' => null,
                    'time_out' => null,
                ];
            }

            if ($log->status === 'IN') {
                $grouped[$key]['time_in'] = $log->time_log;
            }

            if ($log->status === 'OUT') {
                $grouped[$key]['time_out'] = $log->time_log;
            }
        }

        // FILTER ONLY NO TIME IN
        $result = array_filter($grouped, function ($row) {
            return empty($row['time_in']);
        });

        return view('reports.no_time_in', [
            'data' => array_values($result)
        ]);
    }
}