<!DOCTYPE html>
<html>
<head>
    <title>No Time In Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">

<h3>No Time In Employees</h3>

<div class="mb-3">
    <a href="/reports/no-time-out" class="btn btn-danger">No Time Out</a>
    <a href="/reports/no-time-in" class="btn btn-primary">No Time In</a>
</div>

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
            <th>Employee Name</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row['employeeNo'] ?? '-' }}</td>
                <td>{{ $row['employeeName'] ?? 'N/A' }}</td>
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