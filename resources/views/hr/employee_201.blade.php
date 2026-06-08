@extends('layouts.app_hr')

@section('content')
<div class="container">

    <h4>Employee 201 File</h4>

    <input type="text" class="form-control mb-3" placeholder="Search employee...">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($employees as $emp)
            <tr>
                <td>{{ $emp->employeeNo }}</td>
                <td>{{ $emp->firstName }} {{ $emp->lastName }}</td>

                <td>
                    <input type="email"
                        class="form-control email-input"
                        data-emp="{{ $emp->employeeNo }}"
                        value="{{ $emp->email }}">
                </td>

                <td>
                    <button class="btn btn-primary btn-save"
                        data-emp="{{ $emp->employeeNo }}">
                        Save
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $employees->links() }}

</div>
@endsection
<script>
document.querySelectorAll('.btn-save').forEach(btn => {
    btn.addEventListener('click', function () {

        let empNo = this.dataset.emp;
        let email = document.querySelector(
            `.email-input[data-emp="${empNo}"]`
        ).value;

        fetch('/hr/employee-201/save-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                employee_no: empNo,
                email: email
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        });

    });
});
</script>