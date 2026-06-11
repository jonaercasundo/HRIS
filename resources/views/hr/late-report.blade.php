@extends('layouts.app_hr')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Global modern UI reset inside component wrapper */
    .dashboard-wrapper {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        color: #334155;
    }
    
    /* Modern minimalist surfaces */
    .card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
        border-radius: 12px !important;
        transition: box-shadow 0.2s ease;
    }
    
    /* Filter interactive surface states */
    .form-input-modern {
        font-size: 0.815rem !important;
        font-weight: 500;
        color: #1e293b;
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.15s ease-in-out;
    }
    .form-input-modern:focus {
        color: #0f172a;
        background-color: #fff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        outline: 0;
    }
    
    .input-icon-span {
        border: 1px solid #cbd5e1;
        border-right: none;
        background-color: #f8fafc;
        color: #64748b;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .input-icon-span + .form-input-modern {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* Premium Accent Action Button */
    .btn-action-primary {
        background: #4f46e5;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.815rem !important;
        border: 1px solid #4338ca;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.15s ease;
    }
    .btn-action-primary:hover {
        background: #4338ca;
        color: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    
    /* High density data tables matching Stripe/Linear style profiles */
    .table-modern th {
        font-size: 0.725rem !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 16px !important;
    }
    .table-modern td {
        font-size: 0.825rem !important;
        color: #334155;
        padding: 12px 16px !important;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tr:hover td {
        background-color: #f8fafc;
    }

    /* Micro Badges design syntax */
    .badge-modern {
        font-size: 0.725rem !important;
        font-weight: 500;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-modern-neutral {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .badge-modern-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fee2e2;
    }
    .badge-modern-success {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #dcfce7;
    }

    /* Ultra compact micro control row buttons */
    .btn-micro-action {
        padding: 4px 10px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<div class="dashboard-wrapper px-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-slate-900 tracking-tight" style="color: #0f172a;">Late Summary Report</h4>
            <p class="text-muted mb-0 d-flex align-items-center gap-1.5" style="font-size: 0.8rem; color: #64748b;">
                <i class="bi bi-shield-check text-indigo-600" style="color: #4f46e5;"></i> 
                Aggregated core exception framework matrix parameters
            </p>
        </div>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reports.late') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="label-xs text-uppercase text-muted fw-bold mb-1.5" style="font-size: 0.65rem; color: #64748b; letter-spacing: 0.05em;">Temporal Window From</label>
                    <div class="input-group">
                        <span class="input-group-text input-icon-span px-2.5 py-0"><i class="bi bi-calendar4-event" style="font-size: 0.85rem;"></i></span>
                        <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control form-input-modern" required>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label class="label-xs text-uppercase text-muted fw-bold mb-1.5" style="font-size: 0.65rem; color: #64748b; letter-spacing: 0.05em;">Temporal Window To</label>
                    <div class="input-group">
                        <span class="input-group-text input-icon-span px-2.5 py-0"><i class="bi bi-calendar4-check" style="font-size: 0.85rem;"></i></span>
                        <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control form-input-modern" required>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-action-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm" style="height: 38px;">
                        <i class="bi bi-arrow-clockwise"></i> Compile Matrix Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-modern mb-4 border-start border-4" style="border-left-color: #4f46e5 !important;">
        <div class="card-body p-3.5 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="p-2.5 rounded-3 text-indigo-600 d-flex align-items-center justify-content-center" style="background-color: #eeebff; color: #4f46e5;">
                    <i class="bi bi-hourglass-split fs-5"></i>
                </div>
                <div>
                    <span class="text-uppercase fw-bold tracking-wider d-block mb-0.5" style="font-size: 0.68rem; color: #64748b; letter-spacing: 0.05em;">Total Late Accumulation</span>
                    <span class="text-secondary d-block" style="font-size: 0.775rem; color: #64748b;">Combined systemic chronological deficits across tracked profiles</span>
                </div>
            </div>
            <div class="text-sm-end bg-light px-3 py-2 rounded-3 border border-slate-100">
                <span class="fw-bold font-monospace text-slate-900 fs-4 tracking-tight" style="color: #0f172a;">
                    {{ $grandTotalLates ?? '00:00:00' }}
                </span>
            </div>
        </div>
    </div>

    <div class="card card-modern overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Employee ID</th>
                            <th>Employee Name</th>
                            <th>Grace Parameter</th>
                            <th>Calculated Delay (HH:MM:SS)</th>
                            <th>Exception Count</th>
                            <th>Half Day Allocation</th>
                            <th class="pe-4 text-end">Operational Actions</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse($summary ?? [] as $row)
                            <tr>
                                <td class="ps-4 fw-semibold font-monospace text-slate-700" style="color: #334155;">{{ $row['employeeNo'] }}</td>
                                <td class="fw-medium text-slate-900" style="color: #0f172a;">{{ $row['employeeName'] }}</td>
                                <td>
                                    <span class="badge-modern badge-modern-neutral">
                                        <i class="bi bi-clock-history opacity-70"></i> {{ $row['gracePeriod'] }}m
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $lateString = !empty($row['late_hms']) ? $row['late_hms'] : '00:00:00';
                                        $parts = explode(':', $lateString);
                                    @endphp

                                    <span class="badge-modern badge-modern-danger font-monospace fw-semibold">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ (int)($parts[0] ?? 0) }}h 
                                        {{ (int)($parts[1] ?? 0) }}m 
                                        {{ (int)($parts[2] ?? 0) }}s
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-modern-neutral">
                                        {{ $row['late_count'] }} metrics
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-modern-neutral fw-medium" style="background-color: #f8fafc;">
                                        {{ $row['halfday_count'] }} periods
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-2 align-items-center justify-content-end">
                                        @if(($row['late_count'] ?? 0) >= 5)
                                        <a href="{{ url('/reports/nte/'.$row['employeeNo'].'?from='.$from.'&to='.$to) }}"
                                            class="btn btn-outline-danger btn-micro-action shadow-sm"
                                            title="Download NTE">
                                                <i class="bi bi-download"></i>
                                        </a>
                                        @else
                                            <span class="badge-modern badge-modern-success me-1">
                                                <i class="bi bi-check2-circle"></i> Compliant
                                            </span>
                                        @endif
                                        
                                        <button type="button" class="btn btn-outline-secondary btn-micro-action text-slate-700 view-late" style="color: #475569;" data-id="{{ $row['employeeNo'] }}">
                                            <i class="bi bi-eye"></i>
                                            <span>Inspect</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted bg-light border-0">
                                    <div class="py-4">
                                        <div class="p-3 bg-white d-inline-block rounded-circle shadow-sm border mb-3">
                                            <i class="bi bi-folder-x fs-3 text-slate-400" style="color: #94a3b8;"></i>
                                        </div>
                                        <p class="mb-1 fw-semibold text-slate-800" style="color: #1e293b; font-size: 0.9rem;">No dynamic punctuality discrepancies tracked</p>
                                        <small class="text-muted d-block" style="font-size: 0.775rem;">Modify configuration data bounds parameters above to recompute ledger matrices.</small>
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
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="modal-header bg-white py-3 px-3.5 border-bottom border-slate-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-1.5 rounded-2 text-indigo-600 bg-indigo-50" style="background-color: #f5f3ff; color: #4f46e5;">
                        <i class="bi bi-activity" style="font-size: 0.9rem;"></i>
                    </div>
                    <h6 class="modal-title fw-bold text-slate-900 mb-0" style="color: #0f172a; font-size: 0.925rem;">
                        Audit Log Trace: <span id="empNo" class="font-monospace text-indigo-600" style="color: #4f46e5;"></span>
                    </h6>
                </div>
                <button type="button" class="btn-close shadow-none" style="font-size: 0.7rem;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0" style="font-size: 0.8rem;">
                        <thead>
                            <tr>
                                <th class="ps-3.5">Target Timestamp</th>
                                <th>Inbound Core Punch</th>
                                <th class="pe-3.5 text-end">Absolute Delay</th>
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
        document.getElementById('lateBody').innerHTML = `
            <tr>
                <td colspan="3" class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-indigo-600 me-2" style="color: #4f46e5;" role="status"></div>
                    <span style="font-size: 0.815rem; font-weight: 500;">Aggregating remote operational state telemetry...</span>
                </td>
            </tr>`;
        
        modal.show();

        fetch(`/reports/late/details/${empNo}?from={{ $from ?? '' }}&to={{ $to ?? '' }}`)
            .then(res => {
                if (!res.ok) throw new Error('Dynamic validation mismatch');
                return res.json();
            })
            .then(res => {
                let rows = '';
                if (!res.data || res.data.length === 0) {
                    rows = `<tr><td colspan="3" class="text-center py-4 text-muted small fw-medium">No system log history exceptions compiled.</td></tr>`;
                } else {
                    res.data.forEach(item => {
                        rows += `
                            <tr>
                                <td class="ps-3.5 fw-medium text-slate-800" style="color: #1e293b;">${item.date}</td>
                                <td class="font-monospace text-slate-600" style="color: #475569;">${item.time}</td>
                                <td class="pe-3.5 text-end">
                                    <span class="badge-modern badge-modern-danger font-monospace px-2 py-0.5">${item.late}</span>
                                </td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('lateBody').innerHTML = rows;
            })
            .catch(err => {
                document.getElementById('lateBody').innerHTML = `
                    <tr>
                        <td colspan="3" class="text-danger text-center py-4 small fw-medium">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Failed to aggregate transaction timeline exceptions.
                        </td>
                    </tr>`;
            });
    });
});
</script>