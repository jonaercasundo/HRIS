<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar-check text-primary fs-4"></i>
            <h5 class="card-title mb-0 fw-bold text-dark">
                Daily Attendance <span class="text-muted fw-normal fs-6">({{ \Carbon\Carbon::parse($date)->format('M d, Y') }})</span>
            </h5>
        </div>
        
        <form method="GET" action="" class="m-0">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-filter"></i></span>
                <input type="date" 
                       name="date" 
                       value="{{ $date }}" 
                       class="form-control form-control-sm bg-light" 
                       style="max-width: 180px;"
                       onchange="this.form.submit()">
                <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase fs-7 tracking-wider">
                <tr>
                    <th scope="col" class="ps-4">Employee ID</th>
                    <th scope="col">Log Date</th>
                    <th scope="col">Time In</th>
                    <th scope="col">Time Out</th>
                    <th scope="col" class="pe-4 text-end">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td class="ps-4 fw-semibold text-dark">
                            <i class="bi bi-person me-2 text-secondary"></i>{{ $row->employeeNo ?? '-' }}
                        </td>
                        
                        <td class="text-secondary small">
                            {{ $row->biometricsDate ? \Carbon\Carbon::parse($row->biometricsDate)->format('Y-m-d') : '-' }}
                        </td>
                        
                        <td>
                            @if($row->biometricsTimeIn)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1.5 fs-7 fw-medium">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    {{ \Carbon\Carbon::parse($row->biometricsTimeIn)->format('g:i A') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($row->biometricsTimeOut)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1.5 fs-7 fw-medium">
                                    <i class="bi bi-box-arrow-left me-1"></i>
                                    {{ \Carbon\Carbon::parse($row->biometricsTimeOut)->format('g:i A') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <td class="pe-4 text-end">
                            @if($row->biometricsTimeIn && $row->biometricsTimeOut)
                                <span class="badge bg-primary rounded-pill px-2.5 py-1">Complete</span>
                            @elseif($row->biometricsTimeIn)
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Active Shift</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">Absent/No Logs</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 opacity-50 d-block mb-2"></i>
                            <p class="mb-0 fw-medium">No biometric logs discovered for this date.</p>
                            <small class="text-black-50">Try shifting to an alternative selection parameter date window above.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Clean adjustments matching professional HR platforms */
    .fs-7 { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .table > :not(caption) > * > * {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
</style>