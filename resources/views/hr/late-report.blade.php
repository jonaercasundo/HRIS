@extends('layouts.app_hr')

@section('content')
<style>
    .custom-card {
        border: none;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    /* Enforce compact row height and crisp smaller fonts */
    .table-compact th {
        font-size: 0.725rem !important;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }
    .table-compact td {
        font-size: 0.775rem !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }
    /* Slim down form components */
    .form-control-sm {
        font-size: 0.775rem !important;
        border-radius: 6px;
    }
    .label-xs {
        font-size: 0.65rem !important;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
        display: block;
    }
    .btn-compact-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        font-size: 0.775rem !important;
        padding: 4px 12px;
        transition: opacity 0.2s;
    }
    .btn-compact-primary:hover {
        opacity: 0.9;
        color: white;
    }
    .form-control:focus {
        border-color: #5c60f5;
        box-shadow: 0 0 0 0.25rem rgba(92, 96, 245, 0.15);
    }
    .badge-compact {
        font-size: 0.675rem !important;
        padding: 2.5px 6px;
        font-weight: 600;
    }
    .badge-late-alert {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .metric-gradient-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: none;
    }
    /* Ultra-Compact High Density Button Spec */
    .btn-xs {
        padding: 2px 6px !important;
        font-size: 0.725rem !important;
        font-weight: 600 !important;
        border-radius: 4px !important;
        line-height: 1.2 !important;
    }
</style>

<div class="container-fluid px-3 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 fw-bold text-dark tracking-tight">Late Summary Report</h5>
            <small class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-file-earmark-bar-graph me-1"></i>Aggregated Exception Processing</small>
        </div>
    </div>

    <div class="card custom-card shadow-sm rounded-3 overflow-hidden mb-3">
        <div class="card-body p-2.5 bg-white">
            <form method="GET" action="{{ route('reports.late') }}" class="row g-2 align-items-end">
                <div class="col-md-4 col-sm-6">
                    <span class="label-xs text-uppercase text-muted">Temporal Window From</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted py-0"><i class="bi bi-calendar-event" style="font-size: 0.7rem;"></i></span>
                        <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control form-control-sm bg-light" required>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <span class="label-xs text-uppercase text-muted">Temporal Window To</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted py-0"><i class="bi bi-calendar-check" style="font-size: 0.7rem;"></i></span>
                        <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control form-control-sm bg-light" required>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <button type="submit" class="btn btn-compact-primary w-100 rounded-2 shadow-sm d-flex align-items-center justify-content-center gap-1.5" style="height: 31px;">
                        <i class="bi bi-lightning-charge-fill"></i> Compile Matrix
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card metric-gradient-card shadow-sm rounded-3 mb-3">
        <div class="card-body p-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 bg-white bg-opacity-10 rounded-2 text-white-50">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <span class="text-white-50 text-uppercase d-block fw-bold tracking-wider" style="font-size: 0.65rem;">Total Late Accumulation</span>
                    <small class="text-secondary" style="font-size: 0.7rem;">All corporate system identities combined</small>
                </div>
            </div>
            <div class="text-end">
                <span class="text-white fw-bold font-monospace fs-4 tracking-tight">
                    {{ $grandTotalLates ?? '00:00:00' }}
                </span>
            </div>
        </div>
    </div>

    <div class="card custom-card shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-compact table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-muted">
                        <tr>
                            <th class="ps-3">Employee No</th>
                            <th>Employee Name</th>
                            <th>Grace Period</th>
                            <th>Total Late Calculation (HH:MM:SS)</th>
                            <th>Late Frequency</th>
                            <th>Half Day Count</th>
                            <th class="pe-3 text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse($summary ?? [] as $row)
                            <tr>
                                <td class="ps-3 fw-semibold text-dark font-monospace" style="font-size: 0.75rem;">{{ $row['employeeNo'] }}</td>
                                <td class="text-dark fw-medium">{{ $row['employeeName'] }}</td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-1.5 py-0.5 rounded-1 text-xs fw-normal">
                                        {{ $row['gracePeriod'] }} mins
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $lateString = !empty($row['late_hms']) ? $row['late_hms'] : '00:00:00';
                                        $parts = explode(':', $lateString);
                                    @endphp

                                    <span class="badge badge-compact rounded-1 badge-late-alert" style="font-family: var(--bs-font-monospace, monospace); letter-spacing: -0.02em;">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ (int)($parts[0] ?? 0) }}h 
                                        {{ (int)($parts[1] ?? 0) }}m 
                                        {{ (int)($parts[2] ?? 0) }}s
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-1.5 py-0.5 rounded-1 text-xs fw-normal">
                                        {{ $row['late_count'] }} counts
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-1.5 py-0.5 rounded-1 text-xs fw-semibold">
                                        {{ $row['halfday_count'] }} half-days
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-1.5 align-items-center justify-content-end">
                                        @if(($row['late_count'] ?? 0) >= 5)
                                            <a href="{{ url('/reports/nte/'.$row['employeeNo'].'?from='.$from.'&to='.$to) }}"
                                               class="btn btn-outline-danger btn-xs d-inline-flex align-items-center gap-1 shadow-sm">
                                                <i class="bi bi-download" style="font-size: 0.65rem;"></i>
                                                <span>NTE</span>
                                            </a>
                                        @else
                                            <span class="text-muted text-xs font-medium me-1" style="font-size: 0.7rem;"><i class="bi bi-check-circle-fill text-success me-1"></i>Compliant</span>
                                        @endif
                                        
                                        <button type="button" class="btn btn-outline-primary btn-xs view-late d-inline-flex align-items-center gap-1" data-id="{{ $row['employeeNo'] }}">
                                            <i class="bi bi-eye" style="font-size: 0.65rem;"></i>
                                            <span>View</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted bg-light border-0">
                                    <div class="py-3">
                                        <i class="bi bi-clipboard-x fs-3 text-muted mb-1 d-block"></i>
                                        <p class="mb-0 fw-medium small">No punctual discrepancies tracked within this timeframe</p>
                                        <small class="text-muted text-xs">Modify parameters above to recompute records.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="lateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow border-0 rounded-3">
            <div class="modal-header bg-light py-2 px-3">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="bi bi-clock-history me-1.5 text-primary"></i>Late Records: <span id="empNo" class="font-monospace text-primary"></span>
                </h6>
                <button type="button" class="btn-close style-none" style="font-size: 0.75rem;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-compact table-striped align-middle mb-0" style="font-size: 0.75rem;">
                        <thead class="table-light text-uppercase text-muted" style="font-size: 0.68rem;">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Time In</th>
                                <th class="pe-3 text-end">Late Duration</th>
                            </tr>
                        </thead>
                        <tbody id="lateBody" class="border-top-0">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.view-late');
        if (!btn) return;

        let empNo = btn.getAttribute('data-id');
        const modalElement = document.getElementById('lateModal');
        const modal = new bootstrap.Modal(modalElement);

        document.getElementById('empNo').innerText = empNo;
        document.getElementById('lateBody').innerHTML = `<tr><td colspan="3" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-1" role="status"></div> Loading...</td></tr>`;
        
        modal.show();

        fetch(`/reports/late/details/${empNo}?from={{ $from ?? '' }}&to={{ $to ?? '' }}`)
            .then(res => {
                if (!res.ok) throw new Error('Network validation mismatch');
                return res.json();
            })
            .then(res => {
                let rows = '';
                if (!res.data || res.data.length === 0) {
                    rows = `<tr><td colspan="3" class="text-center py-4 text-muted small fw-medium">No chronological exceptions logged.</td></tr>`;
                } else {
                    res.data.forEach(item => {
                        rows += `
                            <tr>
                                <td class="ps-3 fw-medium text-dark">${item.date}</td>
                                <td class="font-monospace">${item.time}</td>
                                <td class="pe-3 text-end"><span class="badge badge-late-alert font-monospace px-1.5 py-0.5 rounded-1">${item.late}</span></td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('lateBody').innerHTML = rows;
            })
            .catch(err => {
                document.getElementById('lateBody').innerHTML = `<tr><td colspan="3" class="text-danger text-center py-4 small fw-medium"><i class="bi bi-exclamation-triangle-fill me-1"></i> Failed to aggregate exception records.</td></tr>`;
            });
    });
});
</script>