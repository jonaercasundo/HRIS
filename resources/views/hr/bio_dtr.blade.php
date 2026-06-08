@extends('layouts.app_hr')

@section('content')
<style>
    .custom-card {
        border: none;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .table-hover tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(248, 249, 250, 0.85) !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #5c60f5;
        box-shadow: 0 0 0 0.25rem rgba(92, 96, 245, 0.15);
    }
    .btn-modern-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        transition: opacity 0.2s;
    }
    .btn-modern-primary:hover {
        opacity: 0.9;
        color: white;
    }
    .badge-in { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-out { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="container py-5">

    <div class="row align-items-center mb-4 g-3">
        <div class="col-sm-6">
            <h2 class="mb-1 fw-extrabold text-dark tracking-tight">Biometric DTR</h2>
            <p class="text-muted mb-0"><i class="bi bi-cpu-fill me-1"></i> BIO-1 Centralized Attendance Streams</p>
        </div>
        <div class="col-sm-6 text-sm-end">
            <button id="syncBtn" class="btn btn-modern-primary px-4 py-2 rounded-3 shadow-sm font-semibold gap-2">
                <span id="syncText" class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-repeat"></i> Sync Attendance
                </span>
            </button>
        </div>
    </div>

    <div id="alertBox" class="mb-4"></div>

    <div class="card custom-card shadow-lg rounded-4 overflow-hidden">
        
        <div class="card-header bg-white border-bottom border-light p-4">
            <div class="row g-3">
                <div class="col-lg-4 col-md-12">
                    <label class="form-label text-xs text-uppercase fw-bold text-muted tracking-wider">Search Streams</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control bg-light border-start-0 ps-0"
                            placeholder="UID, Employee Name, Device...">
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="form-label text-xs text-uppercase fw-bold text-muted tracking-wider">Direction</label>
                    <select id="tagFilter" class="form-select bg-light">
                        <option value="">All Matrix</option>
                        <option value="IN">IN</option>
                        <option value="OUT">OUT</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="form-label text-xs text-uppercase fw-bold text-muted tracking-wider">Chronology</label>
                    <select id="sortFilter" class="form-select bg-light">
                        <option value="latest">Latest Log</option>
                        <option value="oldest">Oldest Log</option>
                        <option value="emp_asc">Employee (A-Z)</option>
                        <option value="emp_desc">Employee (Z-A)</option>
                    </select>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label class="form-label text-xs text-uppercase fw-bold text-muted tracking-wider">Temporal Window</label>
                    <div class="input-group">
                        <input type="date" id="dateFrom" class="form-control bg-light">
                        <span class="input-group-text bg-light border-start-0 border-end-0 text-muted">to</span>
                        <input type="date" id="dateTo" class="form-control bg-light">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-muted">
                        <tr>
                            <th class="ps-4 py-3">UID</th>
                            <th class="py-3">Employee Identification</th>
                            <th class="py-3">Date Record</th>
                            <th class="py-3">Timestamp</th>
                            <th class="py-3">Status State</th>
                            <th class="py-3">Tag</th>
                            <th class="pe-4 py-3">Hardware Terminal</th>
                        </tr>
                    </thead>

                    <tbody id="logTable" class="border-top-0">
                        @forelse($logs as $log)
                            <tr
                                data-uid="{{ $log->uid }}"
                                data-employee="{{ $log->employee_no }}"
                                data-date="{{ $log->date_log }}"
                                data-time="{{ $log->time_log }}"
                                data-tag="{{ $log->tag }}"
                                data-device="{{ $log->bio_name }}"
                            >
                                <td class="ps-4 text-secondary font-monospace fs-7">#{{ $log->uid }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder me-2 d-flex align-items-center justify-content-center bg-light rounded-circle fw-bold text-secondary" style="width:32px; height:32px; font-size: 11px;">
                                            {{ substr($log->employee_no, -2) }}
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $log->employee_no }}</span>
                                    </div>
                                </td>
                                <td class="text-dark">
                                    {{ \Carbon\Carbon::parse($log->date_log)->format('M d, Y') }}
                                </td>
                                <td class="text-secondary fw-medium">
                                    {{ \Carbon\Carbon::parse($log->time_log)->format('h:i:s A') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-medium fs-7">
                                        {{ $log->state ?? 'Default' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge px-3 py-1.5 rounded-pill font-bold fs-7 {{ $log->tag == 'IN' ? 'badge-in' : 'badge-out' }}">
                                        <i class="bi {{ $log->tag == 'IN' ? 'bi-box-arrow-in-right' : 'bi-box-arrow-left' }} me-1"></i>
                                        {{ $log->tag }}
                                    </span>
                                </td>
                                <td class="pe-4 text-muted fs-7">
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <span class="p-1 bg-success rounded-circle d-inline-block" style="width:6px; height:6px;"></span>
                                        {{ $log->bio_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyStaticRow">
                                <td colspan="7" class="text-center py-5 text-muted bg-light border-0">
                                    <div class="py-4">
                                        <i class="bi bi-folder-x display-4 text-muted mb-2"></i>
                                        <p class="mb-0 fw-medium fs-5">No localized logs synchronized yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <tbody id="dynamicEmptyRow" class="d-none">
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="bi bi-search display-5 text-black-50 mb-2"></i>
                                    <p class="mb-0 fw-semibold fs-6">No matching parameters found</p>
                                    <small class="text-muted">Modify filters or search queries to isolate active elements.</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById('searchInput');
    const tagFilter = document.getElementById('tagFilter');
    const sortFilter = document.getElementById('sortFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const tableBody = document.getElementById('logTable');
    const dynamicEmptyRow = document.getElementById('dynamicEmptyRow');

    let allRows = Array.from(tableBody.querySelectorAll('tr')).filter(r => r.id !== 'emptyStaticRow');

    function filterAndSortLogs() {
        const search = searchInput.value.toLowerCase().trim();
        const tag = tagFilter.value;
        const fromDateStr = dateFrom.value;
        const toDateStr = dateTo.value;

        // Establish strict UTC midnight boundary metrics 
        const from = fromDateStr ? new Date(fromDateStr + 'T00:00:00') : null;
        const to = toDateStr ? new Date(toDateStr + 'T23:59:59') : null;

        let visibleCount = 0;

        // 1. Run Filters across original row cache
        allRows.forEach(row => {
            const uid = row.dataset.uid?.toLowerCase() || '';
            const emp = row.dataset.employee?.toLowerCase() || '';
            const device = row.dataset.device?.toLowerCase() || '';
            const rowTag = row.dataset.tag;
            const rowDate = new Date(row.dataset.date + 'T00:00:00');

            let show = uid.includes(search) || emp.includes(search) || device.includes(search);
            if (tag && rowTag !== tag) show = false;
            if (from && rowDate < from) show = false;
            if (to && rowDate > to) show = false;

            if (show) {
                row.classList.remove('d-none');
                visibleCount++;
            } else {
                row.classList.add('d-none');
            }
        });

        // Toggle Clean State Elements if client matching hits absolute zero
        if (allRows.length > 0) {
            if (visibleCount === 0) {
                dynamicEmptyRow.classList.remove('d-none');
            } else {
                dynamicEmptyRow.classList.add('d-none');
                // 2. Execute Sort Arrays directly safely inside DOM Context
                sortVisibleLogs();
            }
        }
    }

    function sortVisibleLogs() {
        const mode = sortFilter.value;

        // Sort rows regardless of status to prevent pipeline processing breakage 
        allRows.sort((a, b) => {
            const dateA = new Date(a.dataset.date + 'T' + a.dataset.time);
            const dateB = new Date(b.dataset.date + 'T' + b.dataset.time);
            const empA = a.dataset.employee || '';
            const empB = b.dataset.employee || '';

            switch (mode) {
                case 'latest': return dateB - dateA;
                case 'oldest': return dateA - dateB;
                case 'emp_asc': return empA.localeCompare(empB);
                case 'emp_desc': return empB.localeCompare(empA);
                default: return 0;
            }
        });

        // Safely re-append elements directly preserving references 
        allRows.forEach(row => tableBody.appendChild(row));
    }

    // Debounce processing wrapper for high frequency key inputs
    let debounceTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(filterAndSortLogs, 150);
    });

    // Native Change Event Observers
    tagFilter.addEventListener('change', filterAndSortLogs);
    sortFilter.addEventListener('change', filterAndSortLogs);
    dateFrom.addEventListener('change', filterAndSortLogs);
    dateTo.addEventListener('change', filterAndSortLogs);

    // Bootstrap execution instance
    filterAndSortLogs();

    // SYSTEM SYNC PIPELINE
    const syncBtn = document.getElementById('syncBtn');
    const syncText = document.getElementById('syncText');
    const alertBox = document.getElementById('alertBox');

    syncBtn.addEventListener('click', async function () {
        syncBtn.disabled = true;
        syncText.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Compiling Sync streams...`;

        try {
            const res = await fetch('/bio-dtr-sync');
            if (!res.ok) throw new Error('Network execution error returned.');
            const data = await res.json();

            alertBox.innerHTML = `
                <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i> <span>${data.message || 'Biometric sequence updated completely.'}</span>
                </div>
            `;
            setTimeout(() => location.reload(), 1200);
        } catch (err) {
            alertBox.innerHTML = `
                <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i> <span>Sync cycle failure. Check physical device endpoint connection logs.</span>
                </div>
            `;
            syncBtn.disabled = false;
            syncText.innerHTML = `<i class="bi bi-arrow-repeat"></i> Sync Attendance`;
        }
    });
});
</script>
@endsection