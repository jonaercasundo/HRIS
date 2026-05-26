<h2>Late Employees ({{ $date }})</h2>

<form method="GET">
    <input type="date" name="date" value="{{ $date }}">
    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Employee No</th>
        <th>Time In</th>
    </tr>

    @foreach($data as $row)
    <tr>
        <td>{{ $row->employeeNo ?? '-' }}</td>
        <td>{{ $row->biometricsTimeIn ?? '-' }}</td>
    </tr>
    @endforeach
</table>