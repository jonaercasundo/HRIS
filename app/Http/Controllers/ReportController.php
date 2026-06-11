<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Models\BiometricTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    private const GRACE_MINUTES = 15;
    private const NTE_THRESHOLD_SECONDS = 14400; // 4 hours
    private function isHalfDayByTime($timeIn)
    {
        return strtotime($timeIn) >= strtotime('12:00:00');
    }
    public function generateNTE(Request $request, $employeeNo)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return response()->json([
                'success' => false,
                'message' => 'Date range (from/to) is required.'
            ], 422);
        }

        $logs = $this->getLogs($from, $to);
        $summary = $this->buildLateSummary($logs);

        if (!isset($summary[$employeeNo])) {
            return abort(404, 'No late record found');
        }

        $employee = $summary[$employeeNo];

        if ($employee['late_count'] < 5) {
            return response()->json([
                'success' => false,
                'message' => 'NTE not required. Employee must have at least 5 late occurrences.'
            ], 403);
        }

        // ✅ instead of PDF: send email
        return $this->sendNTEEmail($employee, $from, $to);
    }
    public function emailNTE(Request $request, $employeeNo)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!$from || !$to) {
            return response()->json([
                'success' => false,
                'message' => 'Date range (from/to) is required.'
            ], 422);
        }

        $logs = $this->getLogs($from, $to);
        $summary = $this->buildLateSummary($logs);

        if (!isset($summary[$employeeNo])) {
            return abort(404, 'No late record found');
        }

        $employee = $summary[$employeeNo];

        if ($employee['late_count'] < 5) {
            return response()->json([
                'success' => false,
                'message' => 'NTE not required. Employee must have at least 5 late occurrences.'
            ], 403);
        }

        if (!$employee['email']) {
            return response()->json([
                'success' => false,
                'message' => 'Employee email not found in database.'
            ], 422);
        }

        Mail::raw(
            "Dear {$employee['employeeName']},\n\nThis is your Notice to Explain (NTE) regarding your attendance record.\n\nRegards,\nHR",
            function ($message) use ($employee, $employeeNo) {
                $message->to($employee['email']) // ✅ REAL EMAIL FROM DB
                        ->subject("NTE Notice - {$employeeNo}");
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'NTE sent successfully to employee email.'
        ]);
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

    $pdf = app('dompdf.wrapper');

    $pdf->loadView('hr.reports.attendance_pdf', [
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

            // ❌ EXCLUDE weekends + holidays
            if (!$this->isWorkingDay($log->date_log)) {
                return false;
            }

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
        $carbonDate = Carbon::parse($date);

        // 6 = Saturday, 7 = Sunday
        if ($carbonDate->isWeekend()) {
            return false;
        }

        $isHoliday = DB::table('holidays')
            ->whereDate('holiday_date', $carbonDate->toDateString())
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
    $lateDaysTracker = [];

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

        $emp  = trim($log->employee_no);
        $date = $log->date_log;

        $timeIn = strtotime($log->time_log);
        $isHalfDay = $this->isHalfDayByTime($log->time_log);

        // =========================
        // COMPUTE LATE SECONDS (FOR REPORT ONLY)
        // =========================
        $lateSeconds = 0;

        if (!$isHalfDay) {
            $lateSeconds = $this->calculateLateSecondsDynamic(
                $log->time_log,
                self::GRACE_MINUTES
            );
        }

        if ($isHalfDay) {
            $halfStart = strtotime('12:00:00');
            $halfGraceEnd = $halfStart + (90 * 60);

            if ($timeIn > $halfGraceEnd) {
                $lateSeconds = $timeIn - $halfGraceEnd;
            }
        }

        // =========================
        // INIT
        // =========================
        if (!isset($summary[$emp])) {
            $summary[$emp] = [
                'employeeNo'    => $emp,
                'employeeName'  => $log->employeeName ?? 'N/A',
                'late_seconds'  => 0,   // ✔ KEEP (REPORTING)
                'late_count'    => 0,   // ✔ NTE BASIS
                'halfday_count' => 0,
                'gracePeriod'   => self::GRACE_MINUTES,
            ];
        }

        if ($isHalfDay) {
            $summary[$emp]['halfday_count']++;
        }

        // =========================
        // FREQUENCY (NTE RULE)
        // =========================
        if ($lateSeconds > 0) {

            $dayKey = $emp . '_' . $date;

            if (!isset($lateDaysTracker[$dayKey])) {
                $lateDaysTracker[$dayKey] = true;
                $summary[$emp]['late_count']++; // ONLY ONCE PER DAY
            }

            // =========================
            // TIME TOTAL (REPORTING ONLY)
            // =========================
            $summary[$emp]['late_seconds'] += $lateSeconds;
        }
    }

    // format for UI
    foreach ($summary as &$row) {
        $row['late_hms'] = gmdate('H:i:s', $row['late_seconds']);
    }

    return $summary;
}
}