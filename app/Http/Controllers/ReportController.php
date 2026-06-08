<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Models\BiometricTemp;
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

            // SAFE CHECK (prevents ghost rows)
            if (!$log->employee_no || !$log->date_log) {
                continue;
            }

            $key = trim($log->employee_no) . '_' . $log->date_log;

            if (!isset($data[$key])) {
                $data[$key] = [
                    'employeeNo'   => trim($log->employee_no),
                    'employeeName' => $log->employeeName ?? 'N/A',
                    'date_log'     => $log->date_log,
                    'time_in'      => null,
                    'time_out'     => null,
                ];
            }

            $tag = strtoupper(trim($log->tag ?? ''));

            if ($tag === 'IN') {
                if (!$data[$key]['time_in']) {
                    $data[$key]['time_in'] = $log->time_log;
                }
            }

            if ($tag === 'OUT') {
                $data[$key]['time_out'] = $log->time_log;
            }
        }

        return $data;
    }
    public function exportExcel(Request $request)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $data = $this->buildAttendance($logs);

        return Excel::download(
            new AttendanceExport($data),
            'attendance.xlsx'
        );
    }
    public function exportPdf(Request $request)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $data = $this->buildAttendance($logs);

        $pdf = Pdf::loadView('hr.reports.attendance_pdf', [
            'data' => array_values($data),
            'from' => $request->from,
            'to' => $request->to
        ])->setPaper('A4', 'landscape');

        return $pdf->download('attendance-report.pdf');
    }
    public function daily(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        $search = $request->search;

        if (!$from || !$to) {
            return view('hr.daily', [
                'data' => [],
                'from' => $from,
                'to'   => $to,
                'search' => $search
            ]);
        }

        $logs = $this->getLogs($from, $to);

        // ✅ SEARCH FILTER (employee ID or name)
        if ($search) {
            $searchLower = strtolower($search);

            $logs = $logs->filter(function ($log) use ($searchLower) {
                return str_contains(strtolower($log->employee_no), $searchLower)
                    || str_contains(strtolower($log->employeeName ?? ''), $searchLower);
            });
        }

        $data = $this->buildAttendance($logs);

        return view('hr.daily', [
            'data' => array_values($data),
            'from' => $from,
            'to'   => $to,
            'search' => $search
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
    public function index()
    {
        $logs = BiometricTemp::orderBy('date_log', 'desc')
            ->orderBy('time_log', 'desc')
            ->limit(200)
            ->get();

        return view('bio_dtr', compact('logs'));
    }
    public function lateReport(Request $request)
    {
        $query = DB::table('attendances')
            ->where('status', 'Late');

        if ($request->filled('from')) {
            $query->whereDate('date_log', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date_log', '<=', $request->to);
        }

        // Detailed records
        $data = (clone $query)
            ->orderBy('date_log', 'desc')
            ->get();

        // Summary per employee
        $summary = (clone $query)
            ->select(
                'employeeNo',
                'employeeName',
                DB::raw('COUNT(*) as total_lates')
            )
            ->groupBy('employeeNo', 'employeeName')
            ->orderByDesc('total_lates')
            ->get();

        $grandTotalLates = $summary->sum('total_lates');

        return view('hr.late-report', compact(
            'data',
            'summary',
            'grandTotalLates'
        ));
    }
}