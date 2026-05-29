@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">HR Dashboard</h3>
            <p class="text-muted mb-0">Welcome back, <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span> 👋</p>
        </div>
        <div>
            <span class="badge bg-white text-muted border px-3 py-2 rounded-pill shadow-sm small">
                <i class="bi bi-clock me-1 text-primary"></i> {{ date('l, M d, Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 rounded-4 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fs-7 fw-semibold tracking-wider mb-1">Daily Reports</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $dailyCount ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary-subtle p-3 rounded-3 text-primary">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-danger border-4 rounded-4 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fs-7 fw-semibold tracking-wider mb-1">Late Employees</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $lateCount ?? 0 }}</h3>
                        </div>
                        <div class="bg-danger-subtle p-3 rounded-3 text-danger">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4 rounded-4 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fs-7 fw-semibold tracking-wider mb-1">No Time-In</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $noTimeInCount ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning-subtle p-3 rounded-3 text-warning">
                            <i class="bi bi-box-arrow-in-right fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-success border-4 rounded-4 card-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fs-7 fw-semibold tracking-wider mb-1">No Time-Out</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $noTimeOutCount ?? 0 }}</h3>
                        </div>
                        <div class="bg-success-subtle p-3 rounded-3 text-success">
                            <i class="bi bi-box-arrow-right fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4 g-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-folder2-open text-muted me-2"></i>Reports Hub</h5>
                    <p class="text-muted small mb-4">Quickly access breakdown summaries for employee attendance records.</p>

                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ url('/reports/daily') }}" class="btn btn-light border w-100 py-3 text-start px-3 h-100 d-flex flex-column justify-content-between action-card">
                                <i class="bi bi-file-earmark-text text-primary fs-4 mb-2"></i>
                                <span class="fw-semibold text-dark small">Daily Logs</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/reports/late') }}" class="btn btn-light border w-100 py-3 text-start px-3 h-100 d-flex flex-column justify-content-between action-card">
                                <i class="bi bi-exclamation-triangle text-danger fs-4 mb-2"></i>
                                <span class="fw-semibold text-dark small">Tardiness Report</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/reports/no-time-in') }}" class="btn btn-light border w-100 py-3 text-start px-3 h-100 d-flex flex-column justify-content-between action-card">
                                <i class="bi bi-door-open text-warning fs-4 mb-2"></i>
                                <span class="fw-semibold text-dark small">Missing Check-Ins</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/reports/no-time-out') }}" class="btn btn-light border w-100 py-3 text-start px-3 h-100 d-flex flex-column justify-content-between action-card">
                                <i class="bi bi-door-closed text-success fs-4 mb-2"></i>
                                <span class="fw-semibold text-dark small">Missing Check-Outs</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-sliders text-muted me-2"></i>Management Shortcuts</h5>
                    <p class="text-muted small mb-4">Core platform configuration links and workspace monitors.</p>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ url('/employees') }}" class="btn btn-outline-dark w-100 py-2.5 text-start px-3 d-flex align-items-center justify-content-between action-card-horizontal">
                            <span class="fw-medium"><i class="bi bi-people me-2"></i> Employee Directory</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>

                        <a href="{{ url('/attendance') }}" class="btn btn-outline-dark w-100 py-2.5 text-start px-3 d-flex align-items-center justify-content-between action-card-horizontal">
                            <span class="fw-medium"><i class="bi bi-display me-2"></i> Live Attendance Monitor</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>

                        <a href="{{ url('/reports') }}" class="btn btn-primary w-100 py-2.5 text-start px-3 d-flex align-items-center justify-content-between mt-2 shadow-sm">
                            <span class="fw-semibold"><i class="bi bi-lightning-charge-fill me-2"></i> Compile Global Master Report</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    /* Clean micro-interactions */
    .fs-7 { font-size: 0.72rem; }
    .tracking-wider { letter-spacing: 0.06em; }
    
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08)!important;
    }

    /* Grid button link enhancements */
    .action-card {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .action-card:hover {
        background-color: #f8f9fa !important;
        border-color: #cbd5e1 !important;
        transform: scale(1.02);
    }

    /* List shortcut link enhancements */
    .action-card-horizontal {
        border-color: #dee2e6;
        transition: all 0.15s ease;
    }
    .action-card-horizontal:hover {
        background-color: #f8f9fa;
        border-color: #212529;
    }
</style>
@endsection