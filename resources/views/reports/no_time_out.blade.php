<!DOCTYPE html>
<html>
<head>
    <title>Attendance Exceptions Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">

{{-- ================= NO TIME OUT ================= --}}
<h3>No Time Out Employees</h3>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="/reports/no-time-out" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row->employee_no ?? '-' }}</td>
                <td>{{ $row->bio_name ?? '-' }}</td>
                <td>{{ $row->date_log ?? '-' }}</td>
                <td>{{ $row->time_in ?? '-' }}</td>
                <td><span class="text-danger">NO TIME OUT</span></td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No records found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<hr>

{{-- ================= NO TIME IN ================= --}}
<h3>No Time In Employees</h3>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="/reports/no-time-in" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Name</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row['employee_no'] ?? '-' }}</td>
                <td>{{ $row['bio_name'] ?? '-' }}</td>
                <td>{{ $row['date_log'] ?? '-' }}</td>
                <td><span class="text-danger">NO TIME IN</span></td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No records found</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>