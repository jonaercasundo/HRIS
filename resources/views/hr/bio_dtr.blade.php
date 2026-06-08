@extends('layouts.app_hr')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Biometric DTR (BIO-1)</h4>

        <button id="syncBtn" class="btn btn-primary">
            Sync Attendance
        </button>
    </div>

    <div id="alertBox"></div>

    <div class="card">
        <div class="card-header">
            Recent Logs
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
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
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->uid }}</td>
                            <td>{{ $log->employee_no }}</td>
                            <td>{{ $log->date_log }}</td>
                            <td>{{ $log->time_log }}</td>
                            <td>{{ $log->state }}</td>
                            <td>
                                <span class="badge bg-{{ $log->tag == 'IN' ? 'success' : 'danger' }}">
                                    {{ $log->tag }}
                                </span>
                            </td>
                            <td>{{ $log->bio_name }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('syncBtn').addEventListener('click', function () {

    this.disabled = true;
    this.innerText = "Syncing...";

    fetch('/bio-dtr-sync')
        .then(res => res.json())
        .then(data => {

            let alertBox = document.getElementById('alertBox');

            alertBox.innerHTML = `
                <div class="alert alert-success">
                    ${data.message}
                </div>
            `;

            location.reload();

        })
        .catch(err => {

            document.getElementById('alertBox').innerHTML = `
                <div class="alert alert-danger">
                    Error syncing attendance
                </div>
            `;

        })
        .finally(() => {
            document.getElementById('syncBtn').disabled = false;
            document.getElementById('syncBtn').innerText = "Sync Attendance";
        });

});
</script>

@endsection