<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InfobipViberService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $sender;

    public function __construct()
    {
        $this->baseUrl = config('services.infobip.base_url');
        $this->apiKey  = config('services.infobip.api_key');
        $this->sender  = config('services.infobip.sender');
    }

    public function send(string $mobile, string $message)
    {
        $response = Http::withOptions([
            'verify' => storage_path('cacert.pem'),
        ])->withHeaders([
            'Authorization' => 'App ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/messages-api/1/messages', [
            'from' => $this->sender,
            'to' => $mobile,
            'content' => [
                'text' => $message
            ]
        ]);

        return $response->json();
    }
}