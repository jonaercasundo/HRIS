@extends('layouts.app')

@section('content')

<style>
    body {
        background: #f4f6fb;
    }

    .header {
        background: #111827;
        color: white;
        padding: 20px;
        border-radius: 12px;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .section-title {
        font-weight: 700;
        font-size: 13px;
        color: #374151;
        margin-bottom: 8px;
    }

    .config-box {
        background: #0f172a;
        color: #e2e8f0;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
    }

    .small-note {
        font-size: 12px;
        color: #6b7280;
    }

    .badge-sap { background: #0ea5e9; }
    .badge-viber { background: #8b5cf6; }
    .badge-api { background: #10b981; }
</style>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="header shadow-sm mb-4">
        <h4 class="mb-1">SAP → Viber Business Messages Integration</h4>
        <small>Enterprise Middleware Configuration Console (SAP → Laravel → Infobip)</small>
    </div>

    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-8">

            <!-- SAP CONFIG -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    SAP Connection Configuration
                </div>

                <div class="card-body">

                    <div class="section-title">HTTP Destination (SAP SM59 / CPI)</div>
                    <div class="config-box">
                        Method: HTTPS POST <br>
                        Endpoint: https://your-domain.com/api/sap/viber/send <br>
                        Port: 443 <br>
                        SSL: ENABLED
                    </div>

                    <hr>

                    <div class="section-title">Authentication</div>
                    <div class="config-box">
                        Type: API Token <br>
                        Header: X-SAP-TOKEN <br>
                        Status: Secured
                    </div>

                    <p class="small-note mt-2">
                        SAP triggers this endpoint via ABAP, CPI, or workflow event.
                    </p>

                </div>
            </div>

            <!-- EVENT MAPPING -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    SAP Event → Viber Mapping
                </div>

                <div class="card-body">

                    <div class="section-title">Sales Order Event</div>
                    <div class="config-box">
                        Event: SALES_ORDER_CREATED <br>
                        Message: Order {SO_NUMBER} has been confirmed.
                    </div>

                    <br>

                    <div class="section-title">Payment Event</div>
                    <div class="config-box">
                        Event: PAYMENT_RECEIVED <br>
                        Message: Payment for Invoice {INV_NO} has been received.
                    </div>

                    <br>

                    <div class="section-title">Delivery Event</div>
                    <div class="config-box">
                        Event: DELIVERY_DISPATCHED <br>
                        Message: Order {SO_NUMBER} is out for delivery.
                    </div>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-4">

            <!-- VIBER CONFIG -->
            <div class="card shadow-sm mb-3">
                <div class="card-header" style="background:#6d28d9;color:white;">
                    Viber (Infobip) Configuration
                </div>

                <div class="card-body">

                    <div class="section-title">API Endpoint</div>
                    <div class="config-box">
                        https://api.infobip.com/messages-api/1/messages
                    </div>

                    <br>

                    <div class="section-title">Sender</div>
                    <div class="config-box">
                        Metro-Mobilia SAP Bot
                    </div>

                    <br>

                    <div class="section-title">Auth</div>
                    <div class="config-box">
                        App API Key (Bearer Token)
                    </div>

                    <p class="small-note mt-2">
                        Managed via Infobip Business Messages account.
                    </p>

                </div>
            </div>

            <!-- FLOW -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    Integration Flow
                </div>

                <div class="card-body small">

                    <span class="badge badge-sap">SAP</span> Event Trigger <br><br>

                    ↓ <br><br>

                    <span class="badge badge-api">Laravel Middleware</span><br>
                    - Validate request<br>
                    - Map SAP event to template<br><br>

                    ↓ <br><br>

                    Queue Job (Retry Enabled)<br><br>

                    ↓ <br><br>

                    <span class="badge badge-viber">Infobip Viber API</span><br><br>

                    ↓ <br><br>

                    End User Viber App

                </div>
            </div>

        </div>

    </div>
</div>

@endsection