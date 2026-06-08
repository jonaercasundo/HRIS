@extends('layouts.app_hr')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Biometric DTR</h3>
            <small class="text-muted">BIO-1 Attendance Logs</small>
        </div>

        <button id="syncBtn" class="btn btn-primary px-4 d-flex align-items-center gap-2">
            <span id="syncText">Sync Attendance</span>
        </button>
    </div>

    <!-- Alert / Toast Area -->
    <div id="alertBox"></div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recent Logs</span>
            <span class="text-muted small">{{ count($logs) }} records</span>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>UID</th>
                            <th>Employee No</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>State</th>
                            <th>Tag</th>
                            <th>Device</th>
                        </tr>
                    </thead>

                    <tbody id="logTable">
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-muted">{{ $log->uid }}</td>
                                <td class="fw-semibold">{{ $log->employee_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->date_log)->format('F d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->time_log)->format('h:i A') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $log->state }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $log->tag == 'IN' ? 'success' : 'danger' }}">
                                        {{ $log->tag }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $log->bio_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    No logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<!-- Script -->
<script>
const syncBtn = document.getElementById('syncBtn');
const syncText = document.getElementById('syncText');
const alertBox = document.getElementById('alertBox');

syncBtn.addEventListener('click', async function () {

    syncBtn.disabled = true;
    syncText.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Syncing...`;

    try {
        const res = await fetch('/bio-dtr-sync');
        const data = await res.json();

        alertBox.innerHTML = `
            <div class="alert alert-success shadow-sm rounded-3">
                ${data.message}
            </div>
        `;

        setTimeout(() => location.reload(), 1000);

    } catch (err) {
        alertBox.innerHTML = `
            <div class="alert alert-danger shadow-sm rounded-3">
                Error syncing attendance. Please try again.
            </div>
        `;
    } finally {
        syncBtn.disabled = false;
        syncText.innerText = "Sync Attendance";
    }

});
</script>

@endsection