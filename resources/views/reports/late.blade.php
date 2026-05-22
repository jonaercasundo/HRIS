<h2>Late Employees ({{ $date }})</h2>

<form>
    <input type="date" name="date">
    <button>Filter</button>
</form>

<table border="1">
@foreach($data as $row)
<tr>
    <td>{{ $row->employee_name ?? $row->emp_id }}</td>
    <td>{{ $row->time_in }}</td>
</tr>
@endforeach
</table>