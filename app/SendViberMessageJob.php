<?php

namespace App\Jobs;

use App\Services\InfobipViberService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendViberMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mobile;
    public $message;
    public $eventType;
    public $referenceNo;

    /**
     * Control retries
     */
    public $tries = 3;
    public $backoff = 10; // seconds

    /**
     * Optional queue timeout
     */
    public $timeout = 15;

    public function __construct($mobile, $message, $eventType = null, $referenceNo = null)
    {
        $this->mobile = $mobile;
        $this->message = $message;
        $this->eventType = $eventType;
        $this->referenceNo = $referenceNo;
    }

    public function handle(InfobipViberService $viberService)
    {
        Log::info('Viber Job Started', [
            'mobile' => $this->mobile,
            'event_type' => $this->eventType,
            'reference_no' => $this->referenceNo,
        ]);

        $response = $viberService->send(
            $this->mobile,
            $this->message
        );

        if (($response['status'] ?? null) !== 'SUCCESS') {
            Log::warning('Viber Job Failed API Response', [
                'mobile' => $this->mobile,
                'response' => $response,
            ]);

            // Force retry if Infobip failed
            throw new \Exception('Viber message failed to send');
        }

        Log::info('Viber Job Completed Successfully', [
            'mobile' => $this->mobile,
            'response' => $response,
        ]);
    }

    /**
     * Runs when all retries fail
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Viber Job Permanently Failed', [
            'mobile' => $this->mobile,
            'message' => $this->message,
            'event_type' => $this->eventType,
            'reference_no' => $this->referenceNo,
            'error' => $exception->getMessage(),
        ]);
    }
}