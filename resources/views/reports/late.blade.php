<h2>Late Employees ({{ $date }})</h2>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="date" value="{{ $date }}" class="form-control">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="/reports/late" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Time In</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row->employeeNo ?? '-' }}</td>
                <td>{{ $row->employeeName ?? 'N/A' }}</td>
                <td>
                    <span class="text-danger fw-bold">
                        {{ $row->biometricsTimeIn ?? '-' }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">
                    No late employees found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>