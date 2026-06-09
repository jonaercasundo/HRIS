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
        try {
            $response = Http::withHeaders([
                'verify' => storage_path('ssl/cacert.pem'),
                'Authorization' => 'App ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post($this->baseUrl . '/messages-api/1/messages', [
                'messages' => [
                    [
                        'from' => $this->sender,

                        'destinations' => [
                            [
                                'to' => $mobile
                            ]
                        ],

                        'content' => [
                            'text' => $message
                        ]
                    ]
                ]
            ]);

            return [
                'status' => $response->successful() ? 'OK' : 'FAILED',
                'code'   => $response->status(),
                'body'   => $response->json()
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }
}