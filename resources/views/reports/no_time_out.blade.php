<!DOCTYPE html>
<html>
<head>
    <title>No Time Out Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">

<h3>No Time Out Employees</h3>

<div class="mb-3">
    <a href="/reports/no-time-out" class="btn btn-danger">No Time Out</a>
    <a href="/reports/no-time-in" class="btn btn-primary">No Time In</a>
</div>

<form method="GET" action="" class="mb-3">
    <div class="row">

        <div class="col-md-3">
            <input type="date"
                   name="from"
                   class="form-control"
                   value="{{ request('from', date('Y-m-d')) }}">
        </div>

        <div class="col-md-3">
            <input type="date"
                   name="to"
                   class="form-control"
                   value="{{ request('to', date('Y-m-d')) }}">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary">Filter</button>

            <a href="/reports/no-time-out" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row['employeeNo'] ?? '-' }}</td>
                <td>{{ $row['employeeName'] ?? 'N/A' }}</td>
                <td>{{ $row['date_log'] ?? '-' }}</td>
                <td>{{ $row['time_in'] ?? '-' }}</td>
                <td><span class="text-danger">NO TIME OUT</span></td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No records found</td>
            </tr>
        @endforelse
    </tbody>  
</table>

</body>
</html>