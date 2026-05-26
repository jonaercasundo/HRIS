<h2>No Time Out Employees</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Employee No</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Status</th>
    </tr>

    @forelse($data as $row)
        <tr>
            <td>{{ $row->employeeNo ?? '-' }}</td>
            <td>{{ $row->biometricsTimeIn ?? '-' }}</td>
            <td>{{ $row->biometricsTimeOut ?? 'NO TIME OUT' }}</td>
            <td style="color:red;">NO TIME OUT</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align:center;">No records found</td>
        </tr>
    @endforelse
</table>