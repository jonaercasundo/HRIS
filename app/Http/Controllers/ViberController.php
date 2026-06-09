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

        return back()->with('success', 'Message sent successfully!');
    }
}