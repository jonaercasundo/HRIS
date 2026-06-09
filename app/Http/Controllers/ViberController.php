<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InfobipViberService;

class ViberController extends Controller
{
    public function send(Request $request, InfobipViberService $viber)
{
    $response = $viber->send($request->mobile, $request->message);

    dd($response);
}
}