<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InfobipViberService;

class ViberController extends Controller
{
    public function send(Request $request, InfobipViberService $viber)
    {
        $request->validate([
            'mobile' => 'required',
            'message' => 'required'
        ]);

        $response = $viber->send(
            $request->mobile,
            $request->message
        );

        // 🔥 CHECK RESULT
        if (isset($response['status']) && $response['status'] === 'FAILED') {
            return back()->with('error', 'Message failed to send. Check logs.');
        }

        return back()->with('success', 'Message sent to Infobip successfully!');
    }
}