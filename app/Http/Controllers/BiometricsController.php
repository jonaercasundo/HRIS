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
            /** @var \ZKLib $zk */
            $zk = new \ZKLib(
                config('biometric.bio_ip_1'),
                config('biometric.bio_port_1')
            );

            $zk->connect();
            $zk->disableDevice();

            $attendance = $zk->getAttendance();

            // ✅ preload employees (performance boost)
            $employees = Biometric::pluck('employeeNo', 'accessNo');

            foreach ($attendance as $at) {

                $dateLog = date('Y-m-d', strtotime($at[3]));

                if (strtotime($dateLog) < strtotime(now()->subDays(210))) {
                    continue;
                }

                $tag = match ($at[2]) {
                    0,3,5 => 'IN',
                    1,2,4 => 'OUT',
                    default => ''
                };

                $employeeNo = $employees[$at[0]] ?? null;

                BiometricTemp::updateOrCreate(
                    [
                        'uid'      => $at[0],
                        'date_log' => $dateLog,
                        'time_log' => date('H:i', strtotime($at[3])),
                        'bio_name' => 'BIO-1'
                    ],
                    [
                        'employee_no' => $employeeNo,
                        'state'       => $at[2],
                        'tag'         => $tag,
                    ]
                );
            }

            // ✅ safely turn device back on
            $zk->enableDevice();
            $zk->disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Attendance downloaded successfully.'
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