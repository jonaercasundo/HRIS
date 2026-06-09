<!DOCTYPE html>
<html>
<head>
    <title>Metro-Mobilia Viber Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Metro-Mobilia - Viber Test Sender</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ url('/viber/send') }}">
                @csrf

                <div class="mb-3">
                    <label>Mobile Number (E.164 format)</label>
                    <input type="text" name="mobile" class="form-control" placeholder="639171234567" required>
                </div>

                <div class="mb-3">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="5" required>
Hello from Metro-Mobilia!

This is a test message via Viber Business Messages.

We craft quality furniture for homes, offices, and commercial spaces.

Thank you.
                    </textarea>
                </div>

                <button class="btn btn-success w-100">
                    Send Viber Message
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>