<h3>NTE Notice</h3>

<p><strong>Employee:</strong> {{ $employee['employeeName'] }}</p>
<p><strong>Employee No:</strong> {{ $employee['employeeNo'] }}</p>

<p><strong>Late Count:</strong> {{ $employee['late_count'] }}</p>
<p><strong>Total Late:</strong> {{ $employee['late_hms'] }}</p>

<p>Period: {{ $from }} to {{ $to }}</p>

<hr>

<p>This is an automated notice regarding repeated tardiness.</p>