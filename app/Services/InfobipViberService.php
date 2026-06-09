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
        $this->baseUrl = rtrim(config('services.infobip.base_url'), '/');
        $this->apiKey  = config('services.infobip.api_key');
        $this->sender  = config('services.infobip.sender');
    }

    public function send(string $mobile, string $message): array
    {
        try {
            $response = Http::timeout(10)
                ->retry(2, 500)
                ->withHeaders([
                    'Authorization' => 'App ' . $this->apiKey,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl . '/messages-api/1/messages', [
                    'messages' => [
                        [
                            'from' => $this->sender,
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

            $result = [
                'status' => $response->successful() ? 'SUCCESS' : 'FAILED',
                'http_code' => $response->status(),
                'response' => $response->json(),
            ];

            // Log everything for SAP audit / debugging
            Log::info('Infobip Viber Response', [
                'mobile' => $mobile,
                'status' => $result['status'],
                'http_code' => $result['http_code'],
            ]);

            if (!$response->successful()) {
                Log::warning('Infobip Viber failed request', [
                    'mobile' => $mobile,
                    'body' => $response->body(),
                ]);
            }

            return $result;

        } catch (\Throwable $e) {

            Log::error('Infobip Viber exception', [
                'mobile' => $mobile,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'ERROR',
                'http_code' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}