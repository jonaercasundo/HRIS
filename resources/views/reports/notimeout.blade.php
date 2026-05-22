<h2>No Time Out Employees</h2>

<table border="1">
@foreach($data as $row)
<tr>
    <td>{{ $row->employee_name ?? $row->emp_id }}</td>
    <td>{{ $row->time_in }}</td>
    <td>{{ $row->time_out ?? 'NO TIME OUT' }}</td>
</tr>
@endforeach
</table>