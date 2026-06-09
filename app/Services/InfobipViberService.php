<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($this->baseUrl . '/viber/2/messages', [
            'messages' => [
                [
                    'sender' => $this->sender,
                    'destinations' => [
                        [
                            'to' => $mobile
                        ]
                    ],
                    'content' => [
                        'type' => 'TEXT',
                        'text' => $message
                    ]
                ]
            ]
        ]);

        return $response->json();
    }
}