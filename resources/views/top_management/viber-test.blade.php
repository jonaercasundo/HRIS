<!DOCTYPE html>
<html>
<head>
    <title>SAP → Viber Business Messaging Gateway</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .top-header {
            background: #1f2a44;
            color: white;
            padding: 20px;
            border-radius: 12px;
        }

        .status-bar {
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .label-title {
            font-weight: 600;
            font-size: 13px;
            color: #555;
        }

        .counter {
            font-size: 12px;
            float: right;
        }

        textarea {
            resize: none;
        }

        .preview-box {
            background: #0f172a;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 10px;
            min-height: 180px;
            white-space: pre-wrap;
        }

        .btn-primary {
            background: #4f46e5;
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .badge-sap {
            background: #0ea5e9;
        }

        .badge-viber {
            background: #8b5cf6;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="top-header shadow-sm mb-4">
        <h4 class="mb-1">SAP → Viber Business Messaging Gateway</h4>
        <small>Enterprise Integration Layer (SAP Events → Infobip Viber API)</small>
    </div>

    <!-- STATUS -->
    <div class="alert alert-warning status-bar">
        <strong>Sender Status:</strong> Pending Approval (Viber Business Sender)
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        <!-- FORM -->
        <div class="col-md-7">

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    SAP Event Trigger Input
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ url('/viber/send') }}" id="viberForm">
                        @csrf

                        <!-- MOBILE -->
                        <div class="mb-3">
                            <label class="label-title">Recipient Mobile (E.164 Format)</label>
                            <input type="text"
                                   name="mobile"
                                   class="form-control"
                                   placeholder="639171234567"
                                   required>
                            <small class="text-muted">
                                Example: 63 + 9XXXXXXXXX
                            </small>
                        </div>

                        <!-- SAP EVENT TYPE -->
                        <div class="mb-3">
                            <label class="label-title">SAP Event Type</label>
                            <select name="event_type" class="form-control">
                                <option value="SALES_ORDER">Sales Order</option>
                                <option value="PAYMENT">Payment</option>
                                <option value="DELIVERY">Delivery</option>
                                <option value="INVENTORY">Inventory</option>
                                <option value="CUSTOM">Custom Message</option>
                            </select>
                        </div>

                        <!-- MESSAGE -->
                        <div class="mb-3">
                            <label class="label-title">Message Payload</label>

                            <textarea name="message"
                                      id="message"
                                      class="form-control"
                                      rows="6"
                                      maxlength="1000"
                                      required>Order update from SAP:

Your transaction has been processed successfully.

Thank you for trusting Metro-Mobilia.</textarea>

                            <span class="counter text-muted">
                                <span id="charCount">0</span>/1000
                            </span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="sendBtn">
                            Send to Viber via Infobip
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <!-- PREVIEW -->
        <div class="col-md-5">

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    Live Message Preview
                </div>

                <div class="card-body">
                    <div id="preview" class="preview-box"></div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    Integration Flow
                </div>

                <div class="card-body small">
                    <span class="badge badge-sap">SAP</span> Event Trigger<br><br>
                    ↓<br><br>
                    Laravel Middleware API<br><br>
                    ↓<br><br>
                    Queue Job (Retry Enabled)<br><br>
                    ↓<br><br>
                    <span class="badge badge-viber">Infobip Viber API</span><br><br>
                    ↓<br><br>
                    End User Viber App
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    const message = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const preview = document.getElementById('preview');
    const sendBtn = document.getElementById('sendBtn');

    function updateUI() {
        charCount.innerText = message.value.length;
        preview.innerText = message.value;
    }

    message.addEventListener('input', updateUI);
    updateUI();

    document.getElementById('viberForm').addEventListener('submit', function () {
        sendBtn.disabled = true;
        sendBtn.innerText = "Sending to Viber...";
    });
</script>

</body>
</html>