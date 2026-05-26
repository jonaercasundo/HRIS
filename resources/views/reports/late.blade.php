<!DOCTYPE html>
<html>
<head>
    <title>Late Employees Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Late Employees Report</h4>
            <small>{{ $from ?? '' }} to {{ $to ?? '' }}</small>
        </div>

        <div class="card-body">

            {{-- FILTER --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="/reports/late" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee No</th>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ $row->employeeNo ?? '-' }}</td>
                                <td>{{ $row->employeeName ?? 'N/A' }}</td>
                                <td>{{ $row->date_log ?? '-' }}</td>
                                <td class="text-danger fw-bold">
                                    {{ $row->time_log ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-danger">LATE</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No late employees found for selected date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>