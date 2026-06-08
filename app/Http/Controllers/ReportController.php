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

    // =========================
    // HALF DAY CHECK
    // =========================
    private function isHalfDay($timeIn)
    {
        return strtotime($timeIn) >= strtotime('12:00:00');
    }

    // =========================
    // GET LOGS
    // =========================
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

    // =========================
    // ATTENDANCE BUILDER
    // =========================
    private function buildAttendance($logs)
    {
        $data = [];

        foreach ($logs as $log) {

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

            if ($tag === 'IN' && !$data[$key]['time_in']) {
                $data[$key]['time_in'] = $log->time_log;
            }

            if ($tag === 'OUT') {
                $data[$key]['time_out'] = $log->time_log;
            }
        }

        return $data;
    }

    // =========================
    // LATE CALCULATION (FULL DAY)
    // =========================
    private function calculateLate($timeIn)
    {
        if (!$timeIn) return 0;

        $start = strtotime('08:00:00');
        $grace = $start + (self::GRACE_MINUTES * 60);
        $in = strtotime($timeIn);

        return ($in > $grace) ? ($in - $grace) : 0;
    }

    // =========================
    // BUILD LATE SUMMARY (MAIN RULE ENGINE)
    // =========================
    private function buildLateSummary($logs)
    {
        $summary = [];

        foreach ($logs as $log) {

            if (!$log->employee_no || !$log->date_log || !$log->time_log) {
                continue;
            }

            if (strtoupper($log->tag) !== 'IN') {
                continue;
            }

            $key = trim($log->employee_no);
            $timeIn = strtotime($log->time_log);

            $isHalfDay = $this->isHalfDay($log->time_log);

            $lateSeconds = 0;

            // ======================
            // FULL DAY RULE
            // ======================
            if (!$isHalfDay) {
                $lateSeconds = $this->calculateLate($log->time_log);
            }

            // ======================
            // HALF DAY RULE
            // ======================
            if ($isHalfDay) {

                $halfStart = strtotime('12:00:00');
                $graceEnd = strtotime('+90 minutes', $halfStart); // 13:30

                if ($timeIn >= $graceEnd) {
                    $lateMinutes = ceil(($timeIn - $graceEnd) / 60);
                    $lateSeconds = $lateMinutes * 60;
                }
            }

            // ======================
            // INIT
            // ======================
            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'employeeNo'   => $key,
                    'employeeName' => $log->employeeName ?? 'N/A',
                    'late_count'   => 0,
                    'late_days'    => [],
                    'halfday_count'=> 0,
                ];
            }

            // halfday counter
            if ($isHalfDay) {
                $summary[$key]['halfday_count']++;
            }

            // ======================
            // COUNT LATE OCCURRENCE
            // ======================
            if ($lateSeconds > 0) {

                $summary[$key]['late_count']++;

                // IMPORTANT: frequency = per DAY
                $summary[$key]['late_days'][$log->date_log] = true;
            }
        }

        // finalize
        foreach ($summary as &$row) {
            $row['late_days_count'] = count($row['late_days']);
        }

        return $summary;
    }

    // =========================
    // NTE GENERATION (FINAL RULE)
    // =========================
    public function generateNTE(Request $request, $employeeNo)
    {
        $logs = $this->getLogs($request->from, $request->to);
        $summary = $this->buildLateSummary($logs);

        if (!isset($summary[$employeeNo])) {
            return abort(404, 'No record found');
        }

        $employee = $summary[$employeeNo];

        // ======================
        // FINAL NTE RULE
        // ======================
        if ($employee['late_days_count'] < 5) {
            return response()->json([
                'success' => false,
                'message' => 'NTE not required. Must have at least 5 late occurrences.'
            ], 403);
        }

        $pdf = Pdf::loadView('hr.reports.nte', [
            'employee' => $employee,
            'from' => $request->from,
            'to' => $request->to
        ])->setPaper('A4');

        return $pdf->download("NTE-{$employeeNo}.pdf");
    }

    // =========================
    // LATE LIST VIEW
    // =========================
    public function late(Request $request)
    {
        if (!$request->from || !$request->to) {
            return view('hr.late', ['data' => [], 'from' => null, 'to' => null]);
        }

        $logs = $this->getLogs($request->from, $request->to);
        $summary = $this->buildLateSummary($logs);

        $summary = array_filter($summary, fn($row) => $row['late_days_count'] > 0);

        return view('hr.late', [
            'data' => array_values($summary),
            'from' => $request->from,
            'to' => $request->to
        ]);
    }

    // =========================
    // EXPORT PDF / EXCEL (UNCHANGED)
    // =========================
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

    public function index()
    {
        $logs = BiometricTemp::orderBy('date_log', 'desc')
            ->orderBy('time_log', 'desc')
            ->limit(200)
            ->get();

        return view('bio_dtr', compact('logs'));
    }
}