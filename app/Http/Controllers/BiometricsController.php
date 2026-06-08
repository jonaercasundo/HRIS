<?php

namespace App\Http\Controllers;

use App\Models\Biometric;
use App\Models\BiometricTemp;
class BiometricsController extends Controller
{
    public function syncBio1()
    {
        try {
            require_once app_path('Services/ZKTeco/zklib.php');

            $zk = new \ZKLib(
                config('biometric.bio_ip_1'),
                config('biometric.bio_port_1')
            );

            $zk->connect();
            $zk->disableDevice();

            $attendance = $zk->getAttendance();
            if (!is_iterable($attendance)) {
                $attendance = [];
            }
            $employees = Biometric::pluck('employeeNo', 'accessNo');

            $nowLimit = now()->subDays(210)->format('Y-m-d');

            foreach ($attendance as $at) {

                $dateLog = date('Y-m-d', strtotime($at[3]));

                if ($dateLog < $nowLimit) {
                    continue;
                }

                $timeLog = date('H:i', strtotime($at[3]));

                // check FIRST if already exists
                $exists = BiometricTemp::where('uid', $at[0])
                    ->where('date_log', $dateLog)
                    ->where('time_log', $timeLog)
                    ->where('bio_name', 'BIO-1')
                    ->exists();

                // ❗ skip if already synced
                if ($exists) {
                    continue;
                }

                $tag = match ($at[2]) {
                    0,3,5 => 'IN',
                    1,2,4 => 'OUT',
                    default => ''
                };

                $employeeNo = $employees[$at[0]] ?? null;

                BiometricTemp::create([
                    'uid'         => $at[0],
                    'employee_no' => $employeeNo,
                    'date_log'    => $dateLog,
                    'time_log'    => $timeLog,
                    'state'       => $at[2],
                    'tag'         => $tag,
                    'bio_name'    => 'BIO-1',
                ]);
            }

            $zk->enableDevice();
            $zk->disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Attendance synced successfully (no duplicates).'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $logs = BiometricTemp::orderBy('date_log', 'desc')
            ->orderBy('time_log', 'desc')
            ->limit(200)
            ->get();

        return view('hr.bio_dtr', compact('logs'));
    }
}