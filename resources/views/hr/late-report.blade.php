@extends('layouts.app_hr')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Late Summary Report</h4>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ url('/late-report') }}" class="row g-2 mb-4">

        <div class="col-md-3">
            <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control" required>
        </div>

        <div class="col-md-3">
            <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control" required>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Generate
            </button>
        </div>

    </form>

    {{-- GRAND TOTAL --}}
    <div class="alert alert-dark d-flex justify-content-between">
        <strong>Total Late (All Employees)</strong>
        <span class="fw-bold">
            {{ $grandTotalLates ?? '00:00:00' }}
        </span>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Employee No</th>
                        <th>Name</th>
                        <th>Total Late (HH:MM:SS)</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($summary ?? [] as $row)
                        <tr>
                            <td>{{ $row['employeeNo'] }}</td>
                            <td>{{ $row['employeeName'] }}</td>
                            <td>
                                <span class="badge bg-danger">
                                    {{ $row['late_hms'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No late records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection