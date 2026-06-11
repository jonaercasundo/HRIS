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
    .badge-layer { background: #f59e0b; }
</style>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="header shadow-sm mb-4">
        <h4 class="mb-1">SAP ↔ Laravel Middleware ↔ Infobip Viber Integration</h4>
        <small>Enterprise Communication Architecture & Configuration Console</small>
    </div>

    <div class="row">

        <!-- LEFT SIDE: SAP CONFIG -->
        <div class="col-md-6">

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    SAP Configuration (Inbound System)
                </div>

                <div class="card-body">

                    <div class="section-title">1. SAP HTTP Destination (SM59 / CPI)</div>
                    <div class="config-box">
                        Method: HTTPS POST <br>
                        Target: Laravel API Gateway <br>
                        Endpoint: /api/sap/viber/send <br>
                        Port: 443 (SSL Enabled)
                    </div>

                    <br>

                    <div class="section-title">2. SAP Event Triggers</div>
                    <div class="config-box">
                        SALES_ORDER_CREATED → Order Confirmation <br>
                        PAYMENT_RECEIVED → Payment Notification <br>
                        DELIVERY_DISPATCHED → Delivery Update
                    </div>

                    <br>

                    <div class="section-title">3. SAP Payload Structure</div>
                    <div class="config-box">
                        {
                            mobile, <br>
                            event_type, <br>
                            reference_no, <br>
                            customer_data, <br>
                            message
                        }
                    </div>

                    <p class="small-note mt-2">
                        SAP sends business events via ABAP, CPI, or workflow triggers.
                    </p>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDE: INFIBIP CONFIG -->
        <div class="col-md-6">

            <div class="card shadow-sm mb-3">
                <div class="card-header" style="background:#6d28d9;color:white;">
                    Infobip Viber Configuration (Outbound System)
                </div>

                <div class="card-body">

                    <div class="section-title">1. API Endpoint</div>
                    <div class="config-box">
                        https://api.infobip.com/viber/1/messages
                    </div>

                    <br>

                    <div class="section-title">2. Authentication</div>
                    <div class="config-box">
                        Type: Application API Key <br>
                        Header: Authorization: App {API_KEY}
                    </div>

                    <br>

                    <div class="section-title">3. Sender Identity</div>
                    <div class="config-box">
                        Viber Sender: IBSelfServe (Temporary / Testing) <br>
                        Status: Pending Approval (Production Sender Not Yet Active)
                    </div>
                    <br>

                    <div class="section-title">4. Message Format</div>
                    <div class="config-box">
                        TEXT MESSAGE ONLY (Approved Template Based) <br>
                        Supports dynamic placeholders from SAP
                    </div>

                    <p class="small-note mt-2">
                        Managed via Infobip Business Messages dashboard.
                    </p>

                </div>
            </div>

        </div>

    </div>

    <!-- MIDDLE LAYER -->
    <div class="row">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-header bg-dark text-white">
                    Middleware Layer (Laravel Integration Engine)
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="section-title">API Gateway</div>
                            <div class="config-box">
                                Receives SAP HTTP Request <br>
                                Validates Token (X-SAP-TOKEN)
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="section-title">Event Mapper</div>
                            <div class="config-box">
                                Converts SAP Event → Message Template <br>
                                Example: SALES_ORDER → Template A
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="section-title">Queue System</div>
                            <div class="config-box">
                                Async Processing <br>
                                Retry Enabled (3x) <br>
                                Fail Logging Enabled
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FLOW -->
    <div class="row mt-3">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-header bg-secondary text-white">
                    End-to-End Integration Flow
                </div>

                <div class="card-body small">

                    <span class="badge badge-sap">SAP</span> Business Event Trigger<br><br>

                    ↓<br><br>

                    <span class="badge badge-layer">Laravel Middleware</span><br>
                    Validate → Map → Transform<br><br>

                    ↓<br><br>

                    Queue Worker (Retry + Logging)<br><br>

                    ↓<br><br>

                    <span class="badge badge-viber">Infobip Viber API</span><br><br>

                    ↓<br><br>

                    End User Viber App

                </div>

            </div>

        </div>

    </div>

</div>

@endsection