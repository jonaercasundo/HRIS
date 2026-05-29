@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        
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
                        <small class="text-white-50">Showing historical attendance discrepancies</small>
                    @endif
                </div>
            </div>
            
            <div>
                <button onclick="window.print()" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-1">
                    <i class="bi bi-printer"></i> Print Report
                </button>
            </div>
        </div>

        <div class="card-body p-4">

            <form method="GET" action="" class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 border">
                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-semibold text-secondary small">Start Date (From)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-range"></i></span>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control bg-white">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-semibold text-secondary small">End Date (To)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-range-fill"></i></span>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control bg-white">
                    </div>
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-funnel-fill"></i> Apply Filter
                    </button>
                    <a href="{{ url(request()->path()) }}" class="btn btn-outline-secondary btn-sm w-100 fw-medium d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider">
                        <tr>
                            <th scope="col" class="ps-3">Employee No</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Date Logged</th>
                            <th scope="col">Time In</th>
                            <th scope="col" class="pe-3 text-end">Classification Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">
                                    #{{ $row->employeeNo ?? '-' }}
                                </td>
                                
                                <td class="fw-medium text-secondary">
                                    {{ $row->employeeName ?? 'N/A' }}
                                </td>
                                
                                <td class="small">
                                    {{ $row->date_log ? \Carbon\Carbon::parse($row->date_log)->format('Y-m-d') : '-' }}
                                </td>
                                
                                <td>
                                    <span class="text-danger fw-bold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-clock-fill text-danger small"></i>
                                        {{ $row->time_log ? \Carbon\Carbon::parse($row->time_log)->format('g:i A') : '-' }}
                                    </span>
                                </td>
                                
                                <td class="pe-3 text-end">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-uppercase fs-7 fw-bold tracking-wide">
                                        Late Arrival
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-2 text-success">
                                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                                    </div>
                                    <p class="mb-1 fw-bold text-dark">Perfect Attendance Record Plain!</p>
                                    <small class="text-secondary">No recorded employee tardiness occurrences discovered within this selected frame tracking parameter scope.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<style>
    /* Clean layout formatting helper rules overrides */
    .fs-7 { font-size: 0.72rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .tracking-wide { letter-spacing: 0.02em; }
    
    .table > :not(caption) > * > * {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }
    
    /* Make input filter styling look tightly uniform */
    .input-group-text {
        border-color: #dee2e6;
    }
    
    @media print {
        body { background-color: #fff !important; }
        .card { shadow: none !important; border: none !important; }
        form, .btn, .navbar, .sidebar { display: none !important; } /* Strips system chrome during print out runs */
    }
</style>
@endsection