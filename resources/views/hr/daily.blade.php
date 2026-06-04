@extends('layouts.app_hr')

@section('content')

<div class="card shadow-sm border-0 rounded-3">

    {{-- HEADER --}}
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar-check text-primary fs-4"></i>

            <h5 class="mb-0 fw-bold text-dark">
                Daily Attendance
                <span class="text-muted fw-normal fs-6">
                    ({{ \Carbon\Carbon::parse($from)->format('M d, Y') }})
                </span>
            </h5>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="m-0">

            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">

                {{-- FROM --}}
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-calendar-event"></i>
                    </span>

                    <input type="date"
                        name="from"
                        value="{{ $from }}"
                        class="form-control">
                </div>

                <span class="text-muted small">to</span>

                {{-- TO --}}
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-calendar-event-fill"></i>
                    </span>

                    <input type="date"
                        name="to"
                        value="{{ $to ?? $from }}"
                        class="form-control">
                </div>

                {{-- BUTTON --}}
                <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                    Filter
                </button>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light text-uppercase small">
                <tr>
                    <th class="ps-4">Employee ID</th>
                    <th>Log Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th class="pe-4 text-end">Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $row)

                    @php
                        $date = $row['biometricsDate'] ?? null
                            ? \Carbon\Carbon::parse($row['biometricsDate'])->format('Y-m-d')
                            : '-';

                        $timeIn = $row['biometricsTimeIn'] ?? null
                            ? \Carbon\Carbon::parse($row['biometricsTimeIn'])->format('g:i A')
                            : null;

                        $timeOut = $row['biometricsTimeOut'] ?? null
                            ? \Carbon\Carbon::parse($row['biometricsTimeOut'])->format('g:i A')
                            : null;
                    @endphp

                    <tr>

                        <td class="ps-4 fw-semibold text-dark">
                            <i class="bi bi-person text-muted me-1"></i>
                            {{ $row['employeeNo'] ?? '-' }}
                        </td>

                        <td class="text-muted small">
                            {{ $date }}
                        </td>

                        {{-- TIME IN --}}
                        <td>
                            @if($timeIn)
                                <span class="badge bg-success px-2 py-1">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    {{ $timeIn }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- TIME OUT --}}
                        <td>
                            @if($timeOut)
                                <span class="badge bg-danger px-2 py-1">
                                    <i class="bi bi-box-arrow-left me-1"></i>
                                    {{ $timeOut }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="pe-4 text-end">

                            @if($row['time_in'] && $row['biometricsTimeOut'])
                                <span class="badge bg-primary rounded-pill px-3 py-1">
                                    Complete
                                </span>

                            @elseif($row['time_in'] && !$row['biometricsTimeOut'])
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1">
                                    Active Shift
                                </span>

                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-1">
                                    No Logs
                                </span>
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">

                            <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-50"></i>

                            <div class="fw-semibold">No attendance records found</div>
                            <small class="text-muted">
                                Try selecting another date range.
                            </small>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- STYLE FIX --}}
<style>
    .table > :not(caption) > * > * {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
</style>

@endsection