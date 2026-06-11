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

    private $holidaysCache = null;

    /* =========================
     | HOLIDAY CACHE (OPTIMIZED)
     ========================= */
    private function getHolidays()
    {
        if ($this->holidaysCache === null) {
            $this->holidaysCache = DB::table('holidays')
                ->pluck('holiday_date')
                ->map(fn($d) => date('Y-m-d', strtotime($d)))
                ->flip()
                ->all();
        }

        return $this->holidaysCache;
    }

    private function isWorkingDay($date)
    {
        $carbon = Carbon::parse($date);

        if ($carbon->isWeekend()) {
            return false;
        }

        return !isset($this->getHolidays()[$carbon->toDateString()]);
    }

    private function isHalfDay($timeIn)
    {
        return Carbon::createFromFormat('H:i:s', $timeIn)
            ->gte(Carbon::createFromTime(12, 0, 0));
    }

    /* =========================
     | CORE LOG FETCH
     ========================= */
    private function getLogs($from, $to)
    {
        return DB::table('zkteco_dtr_tag_temp as b')
            ->leftJoin('e_basicinfo as e', 'e.employeeNo', '=', 'b.employee_no')
            ->whereBetween('b.date_log', [$from, $to])
            ->orderBy('b.employee_no')
            ->orderBy('b.date_log')
            ->orderBy('b.time_log')
            ->select(
                'b.employee_no',
                'b.date_log',
                'b.time_log',
                'b.tag',
                DB::raw("CONCAT(e.firstName,' ',COALESCE(e.middleName,''),' ',e.lastName) as employeeName"),
                'e.email'
            )
            ->get();
    }

    /* =========================
     | LATE COMPUTATION (UNIFIED)
     ========================= */
    private function calculateLateSeconds($timeIn)
    {
        if (!$timeIn) return 0;

        $in = Carbon::createFromFormat('H:i:s', $timeIn);

        $start = Carbon::createFromTime(8, 0, 0);
        $grace = $start->copy()->addMinutes(self::GRACE_MINUTES);

        if ($in->lte($grace)) {
            return 0;
        }

        return $in->diffInSeconds($grace);
    }

    /* =========================
     | BUILD LATE SUMMARY
     ========================= */
    private function buildLateSummary($logs)
    {
        $summary = [];
        $lateTracker = [];

        foreach ($logs as $log) {

            if (!$log->employee_no || !$log->date_log || strtoupper($log->tag ?? '') !== 'IN') {
                continue;
            }

            if (!$this->isWorkingDay($log->date_log)) {
                continue;
            }

            $emp = trim($log->employee_no);
            $date = $log->date_log;

            $timeIn = $log->time_log;
            $isHalfDay = $this->isHalfDay($timeIn);

            $lateSeconds = 0;

            if (!$isHalfDay) {
                $lateSeconds = $this->calculateLateSeconds($timeIn);
            } else {
                $in = Carbon::createFromFormat('H:i:s', $timeIn);
                $halfGrace = Carbon::createFromTime(12, 0, 0)->addMinutes(90);

                if ($in->gt($halfGrace)) {
                    $lateSeconds = $in->diffInSeconds($halfGrace);
                }
            }

            if (!isset($summary[$emp])) {
                $summary[$emp] = [
                    'employeeNo'   => $emp,
                    'employeeName' => $log->employeeName ?? 'N/A',
                    'email'        => $log->email ?? null,
                    'late_seconds' => 0,
                    'late_count'   => 0,
                    'halfday_count'=> 0,
                ];
            }

            if ($isHalfDay) {
                $summary[$emp]['halfday_count']++;
            }

            if ($lateSeconds > 0) {

                $key = $emp . '_' . $date;

                if (!isset($lateTracker[$key])) {
                    $lateTracker[$key] = true;
                    $summary[$emp]['late_count']++;
                }

                $summary[$emp]['late_seconds'] += $lateSeconds;
            }
        }

        foreach ($summary as &$row) {
            $row['late_hms'] = gmdate('H:i:s', $row['late_seconds']);
        }

        return $summary;
    }

    /* =========================
     | REUSABLE EMPLOYEE SUMMARY
     ========================= */
    private function getEmployeeSummary($employeeNo, $from, $to)
    {
        $logs = $this->getLogs($from, $to);
        $summary = $this->buildLateSummary($logs);

        return $summary[$employeeNo] ?? null;
    }

    /* =========================
     | NTE EMAIL
     ========================= */
    private function sendNTEEmail($employee)
    {
        Mail::raw(
            "Dear {$employee['employeeName']},\n\nThis is your Notice to Explain (NTE) regarding your attendance record.\n\nRegards,\nHR",
            function ($message) use ($employee) {
                $message->to($employee['email'])
                        ->subject("NTE Notice - {$employee['employeeNo']}");
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'NTE sent successfully.'
        ]);
    }

    /* =========================
     | NTE GENERATION
     ========================= */
    public function generateNTE(Request $request, $employeeNo)
    {
        $employee = $this->getEmployeeSummary($employeeNo, $request->from, $request->to);

        if (!$employee) {
            return abort(404, 'No record found');
        }

        if ($employee['late_count'] < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum 5 late occurrences required.'
            ], 403);
        }

        return $this->sendNTEEmail($employee);
    }

    /* =========================
     | EMAIL NTE (DIRECT)
     ========================= */
    public function emailNTE(Request $request, $employeeNo)
    {
        $employee = $this->getEmployeeSummary($employeeNo, $request->from, $request->to);

        if (!$employee) {
            return abort(404, 'No record found');
        }

        if (!$employee['email']) {
            return response()->json([
                'success' => false,
                'message' => 'Employee email not found.'
            ], 422);
        }

        if ($employee['late_count'] < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum 5 late occurrences required.'
            ], 403);
        }

        return $this->sendNTEEmail($employee);
    }

    /* =========================
     | EXPORT EXCEL
     ========================= */
    public function exportExcel(Request $request)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $data = $this->buildLateSummary($logs);

        return Excel::download(
            new AttendanceExport($data),
            'attendance.xlsx'
        );
    }

    /* =========================
     | EXPORT PDF
     ========================= */
    public function exportPdf(Request $request)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $data = $this->buildLateSummary($logs);

        $pdf = app('dompdf.wrapper');

        $pdf->loadView('hr.reports.attendance_pdf', [
            'data' => array_values($data),
            'from' => $request->from,
            'to'   => $request->to
        ])->setPaper('A4', 'landscape');

        return $pdf->download('attendance-report.pdf');
    }

    /* =========================
     | LATE REPORT
     ========================= */
    public function lateReport(Request $request)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $summary = array_filter(
            $this->buildLateSummary($logs),
            fn($r) => $r['late_seconds'] > 0
        );

        $total = array_sum(array_column($summary, 'late_seconds'));

        return view('hr.late-report', [
            'summary' => array_values($summary),
            'grandTotalLates' => gmdate('H:i:s', $total),
            'from' => $request->from,
            'to' => $request->to
        ]);
    }
}