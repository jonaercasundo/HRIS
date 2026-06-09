<!DOCTYPE html>
<html>
<head>
    <title>SAP → Viber Business Messages Gateway</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f4f6f9, #e9eef5);
        }

        .header-banner {
            background: #2c3e50;
            color: white;
            padding: 18px;
            border-radius: 10px;
        }

        .badge-status {
            font-size: 12px;
        }

        .message-box {
            resize: none;
        }

        .counter {
            font-size: 12px;
            float: right;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .btn-primary {
            background: #6c5ce7;
            border: none;
        }

        .btn-primary:hover {
            background: #5a4bd6;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="header-banner mb-4 shadow-sm">
        <h4 class="mb-1">SAP → Viber Business Messages</h4>
        <small>Enterprise Messaging Gateway for Metro-Mobilia</small>
    </div>

    <!-- STATUS -->
    <div class="alert alert-warning">
        Sender Status: <strong>Pending Approval</strong> (Viber Business Sender)
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- FORM -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Send Viber Business Message
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ url('/viber/send') }}" id="viberForm">
                        @csrf

                        <!-- MOBILE -->
                        <div class="mb-3">
                            <label class="form-label">Recipient Mobile (E.164)</label>
                            <input type="text" name="mobile" class="form-control"
                                   placeholder="639171234567" required>
                            <small class="text-muted">Must include country code (PH: 63)</small>
                        </div>

                        <!-- MESSAGE -->
                        <div class="mb-3">
                            <label class="form-label">Message Content</label>

                            <textarea name="message" id="message"
                                      class="form-control message-box"
                                      rows="7" required maxlength="1000">Hello from Metro-Mobilia!

This message is sent via SAP integration → Viber Business Messages.

We provide premium furniture solutions for homes, offices, and commercial projects.

Thank you.</textarea>

                            <span class="counter text-muted">
                                <span id="charCount">0</span>/1000
                            </span>
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" class="btn btn-primary w-100" id="sendBtn">
                            Send via Viber Business Messages
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <!-- PREVIEW PANEL -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Message Preview
                </div>
                <div class="card-body">
                    <pre id="preview" style="white-space: pre-wrap;"></pre>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    SAP Integration Notes
                </div>
                <div class="card-body">
                    <ul class="small">
                        <li>Source: SAP Sales / CRM Module</li>
                        <li>Trigger: Order / Notification Event</li>
                        <li>Channel: Viber Business Messages</li>
                        <li>Status Tracking: Pending / Sent / Failed</li>
                    </ul>
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
        sendBtn.innerText = "Sending...";
    });
</script>

</body>
</html>