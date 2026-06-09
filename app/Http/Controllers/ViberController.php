<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InfobipViberService;
use Illuminate\Support\Facades\Log;

class ViberController extends Controller
{
    public function send(Request $request, InfobipViberService $viber)
    {
        $validated = $request->validate([
            'mobile' => 'required|string',
            'message' => 'required|string',
            'event_type' => 'nullable|string',
            'reference_no' => 'nullable|string',
        ]);

        try {
            $response = $viber->send(
                $validated['mobile'],
                $validated['message']
            );

            // Log success (important for SAP audit trail)
            Log::info('Viber message sent successfully', [
                'mobile' => $validated['mobile'],
                'event_type' => $validated['event_type'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'response' => $response
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Viber message sent successfully',
                'data' => $response
            ]);

        } catch (\Exception $e) {

            // Log failure
            Log::error('Viber message failed', [
                'mobile' => $validated['mobile'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to send Viber message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}