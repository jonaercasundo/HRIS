@extends('layouts.app')

@section('title', 'No Time Out Report')

@section('content')
<div class="container py-4">

    <!-- Header & Sub-Navigation Sub-Module Toggle Systems -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Missing Check-Out Records</h3>
            <p class="text-muted small mb-0">Identify staff logs lacking valid evening biometric clock-out tracking records.</p>
        </div>
        
        <!-- Tabbed Variant Link Context Controllers -->
        <div class="btn-group shadow-sm" role="group" aria-label="Report Sub-toggle">
            <a href="{{ url('/reports/no-time-in') }}" class="btn btn-outline-primary px-3 fw-medium">
                <i class="bi bi-box-arrow-in-right me-1"></i> No Time In
            </a>
            <a href="{{ url('/reports/no-time-out') }}" class="btn btn-primary active px-3 fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> No Time Out
            </a>
        </div>
    </div>

    <!-- Main Workspace Container Grid Card Wrapper -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-4">

            <!-- Filter Controls System Panel Wrapper Box -->
            <form method="GET" action="" class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 border">
                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-semibold text-secondary small">From Date</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" 
                               name="from" 
                               class="form-control bg-white"
                               value="{{ request('from', date('Y-m-d')) }}">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <label class="form-label fw-semibold text-secondary small">To Date</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-event-fill"></i></span>
                        <input type="date" 
                               name="to" 
                               class="form-control bg-white"
                               value="{{ request('to', date('Y-m-d')) }}">
                    </div>
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ url('/reports/no-time-out') }}" class="btn btn-outline-secondary btn-sm w-100 fw-medium d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Table Workspace Layout Data Sheet Grid -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider">
                        <tr>
                            <th scope="col" class="ps-3">Employee No</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Date Logged</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col" class="pe-3 text-end">Exception Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <!-- Employee ID Identification No -->
                                <td class="ps-3 fw-bold text-dark">
                                    #{{ $row['employeeNo'] ?? '-' }}
                                </td>
                                
                                <!-- Employee Display Label Name Identification -->
                                <td class="fw-medium text-secondary">
                                    {{ $row['employeeName'] ?? 'N/A' }}
                                </td>
                                
                                <!-- Log Date Metadata Column Grid Block -->
                                <td class="small">
                                    {{ isset($row['date_log']) ? \Carbon\Carbon::parse($row['date_log'])->format('Y-m-d') : '-' }}
                                </td>
                                
                                <!-- Check In Metric Log Tracker Area Values Element Block -->
                                <td>
                                    @if(!empty($row['time_in']))
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-7 fw-medium">
                                            <i class="bi bi-box-arrow-in-right text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($row['time_in'])->format('g:i A') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                <!-- Empty Critical Punch Exception Target Column Block Element -->
                                <td>
                                    <span class="text-danger-gradient small fw-bold text-uppercase tracking-wide">
                                        <i class="bi bi-x-circle-fill me-1"></i> Missing
                                    </span>
                                </td>
                                
                                <!-- Badge Status Output Flag Element Target Block Wrap -->
                                <td class="pe-3 text-end">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-uppercase fs-7 fw-bold tracking-wide">
                                        No Time Out
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <!-- Null-State Exception Handler Catch Layer Row Element -->
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="text-success mb-2">
                                        <i class="bi bi-shield-check fs-1 opacity-75"></i>
                                    </div>
                                    <p class="mb-1 fw-bold text-dark">No Missing Entry Actions Found</p>
                                    <small class="text-secondary">All employee biometric punch-out events look fully accounted for inside this operational window track block parameters.</small>
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
    /* Architectural layout visual overrides custom rules */
    .fs-7 { font-size: 0.72rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .tracking-wide { letter-spacing: 0.02em; }
    
    .table > :not(caption) > * > * {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }
    
    /* Clean micro text accent formatting rules overrides styles layer */
    .text-danger-gradient {
        color: #dc3545;
        font-size: 0.8rem;
    }
    
    .input-group-text {
        border-color: #dee2e6;
    }
</style>
@endsection