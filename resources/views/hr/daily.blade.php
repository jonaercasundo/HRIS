@extends('layouts.app_hr')

@section('content')

<div class="card shadow-sm border-0 rounded-4 overflow-hidden">

    {{-- HEADER & FILTERS --}}
    <div class="card-header bg-white border-bottom border-light-subtle py-4 px-4 d-flex flex-column lg:flex-row align-items-start align-items-md-center justify-content-between gap-4">

        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <div class="p-2 bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-check fs-4 lh-1"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark-emphasis">Daily Attendance</h5>
                    <p class="text-muted small mb-0">
                        {{ \Carbon\Carbon::parse($from)->format('M d, Y') }} 
                        @if(isset($to) && $to !== $from) to {{ \Carbon\Carbon::parse($to)->format('M d, Y') }} @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- FILTER FORM --}}
        <form method="GET" class="m-0 w-100 w-md-auto">
            <div class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center backend-filter-wrapper">

                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0 text-muted">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control ps-1" aria-label="From Date">
                </div>

                <div class="text-muted small fw-medium text-center px-1">to</div>

                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0 text-muted">
                        <i class="bi bi-calendar-event-fill"></i>
                    </span>
                    <input type="date" name="to" value="{{ $to ?? $from }}" class="form-control ps-1" aria-label="To Date">
                </div>

                <button type="submit" class="btn btn-primary px-4 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                    <i class="bi bi-funnel-fill small"></i> Filter
                </button>

            </div>
        </form>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 custom-attendance-table">
            <thead>
                <tr>
                    <th class="ps-4 text-muted fw-bold">Employee ID</th>
                    <th class="text-muted fw-bold">Employee Name</th>
                    <th class="text-muted fw-bold">Log Date</th>
                    <th class="text-muted fw-bold">Time In</th>
                    <th class="text-muted fw-bold">Time Out</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    @php
                        $date = !empty($row['date_log'])
                            ? \Carbon\Carbon::parse($row['date_log'])->format('Y-m-d')
                            : '—';

                        $timeInRaw = $row['time_in'] ?? null;
                        $timeOutRaw = $row['time_out'] ?? null;

                        $timeIn = !empty($timeInRaw)
                            ? \Carbon\Carbon::parse($timeInRaw)->format('g:i A')
                            : null;

                        $timeOut = !empty($timeOutRaw)
                            ? \Carbon\Carbon::parse($timeOutRaw)->format('g:i A')
                            : null;
                    @endphp

                    <tr>
                        {{-- EMPLOYEE ID --}}
                        <td class="ps-4 font-monospace text-secondary-emphasis fw-medium">
                            {{ $row['employeeNo'] ?? '—' }}
                        </td>
                        
                        {{-- EMPLOYEE NAME --}}
                        <td class="fw-semibold text-dark-emphasis">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-placeholder rounded-circle bg-light text-secondary d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-person fs-6"></i>
                                </span>
                                <span>{{ $row['employeeName'] ?? '—' }}</span>
                            </div>
                        </td>

                        {{-- DATE --}}
                        <td class="text-secondary small fw-medium">
                            {{ $date }}
                        </td>

                        {{-- TIME IN --}}
                        <td>
                            @if($timeIn)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-medium d-inline-flex align-items-center gap-1.5 rounded-2">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    {{ $timeIn }}
                                </span>
                            @else
                                <span class="text-muted opacity-50">—</span>
                            @endif
                        </td>

                        {{-- TIME OUT --}}
                        <td>
                            @if($timeOut)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-medium d-inline-flex align-items-center gap-1.5 rounded-2">
                                    <i class="bi bi-box-arrow-left"></i>
                                    {{ $timeOut }}
                                </span>
                            @else
                                <span class="text-muted opacity-50">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Fixed colspan to 6 matching your visual columns --}}
                        <td colspan="6" class="text-center py-5 bg-light-subtle">
                            <div class="py-4">
                                <div class="p-3 bg-white shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center mb-3 border border-light text-muted opacity-70" style="width: 60px; height: 60px;">
                                    <i class="bi bi-folder-x fs-3"></i>
                                </div>
                                <h6 class="fw-bold text-dark-emphasis mb-1">No attendance records found</h6>
                                <p class="text-muted small mb-0">Try adjusting your selected date range filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
    /* Table Styling Overrides */
    .custom-attendance-table thead th {
        background-color: var(--bs-tertiary-bg);
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--bs-border-color-translucent);
    }
    .custom-attendance-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    .custom-attendance-table > :not(caption) > * > * {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    /* Utilities for Modern Touches */
    .avatar-placeholder {
        width: 28px;
        height: 28px;
        border: 1px solid var(--bs-border-color-translucent);
    }
    .badge {
        font-size: 0.825rem;
    }
    .input-group-text {
        font-size: 0.875rem;
    }
    .form-control {
        font-size: 0.875rem;
    }
    .form-control:focus {
        border-color: var(--bs-primary-border-subtle);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    /* Small layout tweaking fixes for inputs on mobile */
    @media (max-width: 575.98px) {
        .backend-filter-wrapper {
            width: 100%;
        }
        .backend-filter-wrapper .input-group {
            width: 100%;
        }
    }
</style>

@endsection