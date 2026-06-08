<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Models\BiometricTemp;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
    private const GRACE_MINUTES = 15;
    private const NTE_THRESHOLD_SECONDS = 14400; // 4 hours
    public function generateNTE(Request $request, $employeeNo)
    {
        $logs = $this->getLogs($request->from, $request->to);

        $summary = $this->buildLateSummary($logs);

        if (!isset($summary[$employeeNo])) {
            return abort(404, 'No late record found');
        }

        $employee = $summary[$employeeNo];

        // ❌ BLOCK IF BELOW 4 HOURS
        if ($employee['late_seconds'] < self::NTE_THRESHOLD_SECONDS) {
            return response()->json([
                'success' => false,
                'message' => 'NTE not required. Employee has less than 4 hours total late.'
            ], 403);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('hr.reports.nte', [
            'employee' => $employee,
            'from' => $request->from,
            'to' => $request->to
        ])->setPaper('A4');

        return $pdf->download("NTE-{$employeeNo}.pdf");
    }
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
        if (!$request->from || !$request->to) {
            return view('hr.late', [
                'data' => [],
                'from' => null,
                'to' => null
            ]);
        }

        $logs = $this->getLogs($request->from, $request->to);

        $summary = $this->buildLateSummary($logs);
        // REMOVE zero late
        $summary = array_filter($summary, function ($row) {
            return $row['late_seconds'] > 0;
        });

        return view('hr.late', [
            'data' => array_values($summary),
            'from' => $request->from,
            'to' => $request->to
        ]);
    }
public function lateDetails(Request $request, $employeeNo)
{
    $logs = $this->getLogs($request->from, $request->to);

    $data = $logs->filter(function ($log) use ($employeeNo) {
        return $log->employee_no == $employeeNo
            && strtoupper($log->tag) === 'IN'
            && $this->calculateLateSeconds($log->time_log) > 0;
    })->map(function ($log) {
        return [
            'date' => $log->date_log,
            'time' => $log->time_log,
            'late' => gmdate('H:i:s', $this->calculateLateSeconds($log->time_log)),
        ];
    })->values();

    return response()->json([
        'data' => $data
    ]);
}
    private function calculateLateSeconds($timeIn)
    {
        if (!$timeIn) return 0;

        $start = strtotime('08:00:00');
        $grace = $start + (self::GRACE_MINUTES * 60);
        $in = strtotime($timeIn);

        if ($in <= $grace) {
            return 0;
        }

        return $in - $grace;
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
        $logs = $this->getLogs($request->from, $request->to);

        $summary = $this->buildLateSummary($logs);

        // ❌ REMOVE employees with 0 late
        $summary = array_filter($summary, function ($row) {
            return $row['late_seconds'] > 0;
        });

        $grandTotalLates = array_sum(array_column($summary, 'late_seconds'));

        return view('hr.late-report', [
            'summary' => array_values($summary),
            'grandTotalLates' => gmdate('H:i:s', $grandTotalLates),
            'from' => $request->from,
            'to' => $request->to
        ]);
    }
    private function isWorkingDay($date)
    {
        $dayOfWeek = date('N', strtotime($date)); 
        // 6 = Saturday, 7 = Sunday

        if ($dayOfWeek >= 6) {
            return false;
        }

        // OPTIONAL: holiday check (table-based)
        $isHoliday = DB::table('holidays')
            ->where('holiday_date', $date)
            ->exists();

        if ($isHoliday) {
            return false;
        }

        return true;
    }
    private function calculateLateSecondsDynamic($timeIn, $graceMinutes)
    {
        if (!$timeIn) return 0;

        $start = strtotime('08:00:00');
        $grace = $start + ($graceMinutes * 60);
        $in = strtotime($timeIn);

        if ($in <= $grace) {
            return 0;
        }

        return $in - $grace;
    }
private function buildLateSummary($logs)
{
    $summary = [];

    foreach ($logs as $log) {

        if (!$log->employee_no || !$log->time_log || !$log->date_log) {
            continue;
        }

        if (strtoupper($log->tag) !== 'IN') {
            continue;
        }

        if (!$this->isWorkingDay($log->date_log)) {
            continue;
        }

        $key = trim($log->employee_no);

        $scheduleType = $log->schedule_type ?? 'FULL';
        $isHalfDay = ($scheduleType === 'HALF');

        $graceMinutes = $isHalfDay ? 90 : self::GRACE_MINUTES;

        $lateSeconds = $this->calculateLateSecondsDynamic(
            $log->time_log,
            $graceMinutes
        );

        if (!isset($summary[$key])) {
            $summary[$key] = [
                'employeeNo'    => $key,
                'employeeName'  => $log->employeeName ?? 'N/A',
                'late_seconds'  => 0,
                'late_count'    => 0,
                'halfday_count' => 0,
                'gracePeriod'   => self::GRACE_MINUTES,
            ];
        }

        // ✅ count half-day days
        if ($isHalfDay) {
            $summary[$key]['halfday_count']++;
        }

        // ✅ HALF DAY RULE:
        // ignore late if less than 1h30 (5400 sec)
        if ($isHalfDay && $lateSeconds < 5400) {
            continue;
        }

        // ✅ FULL DAY OR VALID HALF DAY LATE
        if ($lateSeconds > 0) {
            $summary[$key]['late_seconds'] += $lateSeconds;
            $summary[$key]['late_count']++;
        }
    }

    foreach ($summary as &$row) {
        $row['late_hms'] = gmdate('H:i:s', $row['late_seconds']);
    }

    return $summary;
}
}