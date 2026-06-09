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

    try {
        $response = $viber->send(
            $request->mobile,
            $request->message
        );

        dd($response); // 🔥 TEMP DEBUG (IMPORTANT)

    } catch (\Exception $e) {
        dd($e->getMessage()); // 🔥 SHOW REAL ERROR
    }
}
}