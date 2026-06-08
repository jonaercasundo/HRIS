<div class="container py-4">

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- REPORT HEADER --}}
        <div class="card-header bg-dark text-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>

                <div>
                    <h4 class="mb-0 fw-bold">Late Employees Report</h4>

                    @if(!empty(request('from')) || !empty(request('to')))
                        <small class="text-white-50">
                            Period:
                            <strong>{{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('M d, Y') : 'Beginning' }}</strong>
                            to
                            <strong>{{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('M d, Y') : 'Today' }}</strong>
                        </small>
                    @else
                        <small class="text-white-50">
                            Showing historical attendance discrepancies
                        </small>
                    @endif
                </div>
            </div>

            <button onclick="window.print()" class="btn btn-sm btn-outline-light">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>

        <div class="card-body p-4">

            {{-- FILTER --}}
            <form method="GET" action="" class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 border">
                ...
            </form>

            {{-- SUMMARY --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Late Attendance Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="alert alert-danger">
                                <strong>Total Late Incidents:</strong>
                                {{ number_format($grandTotalLates) }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <strong>Total Employees Late:</strong>
                                {{ $summary->count() }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee No</th>
                                    <th>Employee Name</th>
                                    <th class="text-center">Frequency of Lates</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($summary as $employee)
                                    <tr>
                                        <td>{{ $employee->employeeNo }}</td>
                                        <td>{{ $employee->employeeName }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">
                                                {{ $employee->total_lates }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No late records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="2" class="text-end">
                                        Grand Total Lates
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($grandTotalLates) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

            {{-- DETAILED RECORDS --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    ...
                </table>
            </div>

        </div>
    </div>

</div>