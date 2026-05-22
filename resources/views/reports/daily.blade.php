<h2>Daily Attendance ({{ $date }})</h2>

<form>
    <input type="date" name="date">
    <button>Filter</button>
</form>

<table border="1">
<tr>
    <th>Employee</th>
    <th>Time In</th>
    <th>Time Out</th>
</tr>

@foreach($data as $row)
<tr>
    <td>{{ $row->employee_name ?? $row->emp_id }}</td>
    <td>{{ $row->time_in }}</td>
    <td>{{ $row->time_out }}</td>
</tr>
@endforeach
</table>