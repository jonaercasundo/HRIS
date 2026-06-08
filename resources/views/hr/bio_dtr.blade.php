@extends('layouts.app_hr')

@section('content')
<style>
    .custom-card {
        border: none;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
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
    .form-control-sm, .form-select-sm {
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
    .badge-compact {
        font-size: 0.675rem !important;
        padding: 2px 6px;
        font-weight: 600;
    }
    .badge-in { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-out { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    
    /* Modern Compact Pagination Styles */
    .pagination-sm .page-link {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem;
        color: #4f46e5;
    }
    .pagination-sm .page-item.active .page-link {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
</style>

<div class="container-fluid px-3 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 fw-bold text-dark tracking-tight">Biometric DTR</h5>
            <small class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-cpu-fill me-1"></i>BIO-1 Stream</small>
        </div>
        <button id="syncBtn" class="d-none btn btn-compact-primary rounded-2 shadow-sm d-flex align-items-center gap-1.5">
            <span id="syncText" class="d-flex align-items-center gap-1">
                <i class="bi bi-arrow-repeat"></i> Sync Logs
            </span>
        </button>
    </div>

    <div id="alertBox" class="mb-2" style="font-size: 0.75rem;"></div>

    <div class="card custom-card shadow-sm rounded-3 overflow-hidden">
        
        <div class="card-header bg-white border-bottom border-light p-2.5">
            <div class="row g-2">
                <div class="col-md-3 col-sm-12">
                    <span class="label-xs text-uppercase text-muted">Search Query</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0 py-0"><i class="bi bi-search" style="font-size: 0.7rem;"></i></span>
                        <input type="text" id="searchInput" class="form-control form-control-sm bg-light border-start-0 ps-1"
                            placeholder="UID, Emp No, Device...">
                    </div>
                </div>

                <div class="col-md-2 col-sm-4">
                    <span class="label-xs text-uppercase text-muted">Direction</span>
                    <select id="tagFilter" class="form-select form-select-sm bg-light">
                        <option value="">All Direction</option>
                        <option value="IN">IN</option>
                        <option value="OUT">OUT</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-4">
                    <span class="label-xs text-uppercase text-muted">Sort Matrix</span>
                    <select id="sortFilter" class="form-select form-select-sm bg-light">
                        <option value="latest">Latest Logs First</option>
                        <option value="oldest">Oldest Logs First</option>
                        <option value="emp_asc">Employee (A-Z)</option>
                        <option value="emp_desc">Employee (Z-A)</option>
                    </select>
                </div>

                <div class="col-md-4 col-sm-4">
                    <span class="label-xs text-uppercase text-muted">Date Frame</span>
                    <div class="input-group input-group-sm">
                        <input type="date" id="dateFrom" class="form-control form-control-sm bg-light px-1">
                        <span class="input-group-text bg-light text-muted px-1.5 py-0 border-start-0 border-end-0" style="font-size: 0.7rem;">to</span>
                        <input type="date" id="dateTo" class="form-control form-control-sm bg-light px-1">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-compact table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-muted">
                        <tr>
                            <th class="ps-3">UID</th>
                            <th>Employee No</th>
                            <th>Date Record</th>
                            <th>Timestamp</th>
                            <th>Status State</th>
                            <th>Tag</th>
                            <th class="pe-3">Terminal Device</th>
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
                                <td class="ps-3 text-secondary font-monospace">#{{ $log->uid }}</td>
                                <td class="fw-semibold text-dark">{{ $log->employee_no }}</td>
                                <td class="text-dark">
                                    {{ \Carbon\Carbon::parse($log->date_log)->format('M d, Y') }}
                                </td>
                                <td class="text-secondary">
                                    {{ \Carbon\Carbon::parse($log->time_log)->format('h:i:s A') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-1.5 py-0.5 rounded-1 font-medium text-xs">
                                        {{ $log->state ?? 'Default' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-compact rounded-pill {{ $log->tag == 'IN' ? 'badge-in' : 'badge-out' }}">
                                        {{ $log->tag }}
                                    </span>
                                </td>
                                <td class="pe-3 text-muted" style="font-size: 0.725rem;">
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <span class="p-0.5 bg-success rounded-circle d-inline-block" style="width:4px; height:4px;"></span>
                                        {{ $log->bio_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyStaticRow">
                                <td colspan="7" class="text-center py-4 text-muted bg-light border-0">
                                    <div class="py-2">
                                        <i class="bi bi-folder-x fs-4 text-muted"></i>
                                        <p class="mb-0 fw-medium small">No synchronized logs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <tbody id="dynamicEmptyRow" class="d-none">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted bg-light border-0">
                                <div class="py-2">
                                    <i class="bi bi-search fs-4 text-muted"></i>
                                    <p class="mb-0 fw-medium small">No matching parameters found</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top border-light px-3 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.725rem;" id="paginationInfo">Showing 0 to 0 of 0 entries</span>
                <select id="pageSizeSelect" class="form-select form-select-sm bg-light py-0 px-1" style="width: auto; height: 24px; font-size: 0.7rem;">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationControls">
                    </ul>
            </nav>
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
    
    // Pagination Selectors
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const paginationControls = document.getElementById('paginationControls');
    const paginationInfo = document.getElementById('paginationInfo');

    let allRows = Array.from(tableBody.querySelectorAll('tr')).filter(r => r.id !== 'emptyStaticRow');
    
    // Pagination State Parameters
    let currentPage = 1;
    let pageSize = parseInt(pageSizeSelect.value);
    let filteredRows = [];

    function filterAndSortLogs() {
        const search = searchInput.value.toLowerCase().trim();
        const tag = tagFilter.value;
        const fromDateStr = dateFrom.value;
        const toDateStr = dateTo.value;

        const from = fromDateStr ? new Date(fromDateStr + 'T00:00:00') : null;
        const to = toDateStr ? new Date(toDateStr + 'T23:59:59') : null;

        // Reset tracking array
        filteredRows = [];

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
                filteredRows.push(row);
            } else {
                row.classList.add('d-none'); // Hide mismatched rows immediately
            }
        });

        if (allRows.length > 0) {
            if (filteredRows.length === 0) {
                dynamicEmptyRow.classList.remove('d-none');
                paginationControls.innerHTML = '';
                paginationInfo.innerText = "Showing 0 to 0 of 0 entries";
            } else {
                dynamicEmptyRow.classList.add('d-none');
                sortVisibleLogs();
                // Reset to page 1 only if structural criteria reset updates execution tree
                renderPagination();
            }
        }
    }

    function sortVisibleLogs() {
        const mode = sortFilter.value;

        filteredRows.sort((a, b) => {
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

        filteredRows.forEach(row => tableBody.appendChild(row));
    }

    function renderPagination() {
        const totalItems = filteredRows.length;
        const totalPages = Math.ceil(totalItems / pageSize);

        // Clamping bounds check safeguards bounds errors
        if (currentPage > totalPages) currentPage = totalPages || 1;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = Math.min(startIndex + pageSize, totalItems);

        // Toggle strict block states to cleanly show only matching window rows
        allRows.forEach(row => row.classList.add('d-none'));
        for (let i = startIndex; i < endIndex; i++) {
            filteredRows[i].classList.remove('d-none');
        }

        // Update Text Info Display Counter
        paginationInfo.innerText = `Showing ${totalItems === 0 ? 0 : startIndex + 1} to ${endIndex} of ${totalItems} entries`;

        // Generate controls matrix HTML string safely
        let html = '';
        
        // Prev button
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}"><i class="bi bi-chevron-left"></i></a>
        </li>`;

        // Render clean structural layout without clogging grid windows
        for (let p = 1; p <= totalPages; p++) {
            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                html += `<li class="page-item ${currentPage === p ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${p}">${p}</a>
                </li>`;
            } else if (p === currentPage - 2 || p === currentPage + 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Next button
        html += `<li class="page-item ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}"><i class="bi bi-chevron-right"></i></a>
        </li>`;

        paginationControls.innerHTML = html;
    }

    // Capture dynamic click handlers directly out of target node elements
    paginationControls.addEventListener('click', (e) => {
        e.preventDefault();
        const targetLink = e.target.closest('a');
        if (!targetLink || targetLink.parentElement.classList.contains('disabled')) return;
        
        currentPage = parseInt(targetLink.dataset.page);
        renderPagination();
    });

    pageSizeSelect.addEventListener('change', () => {
        pageSize = parseInt(pageSizeSelect.value);
        currentPage = 1;
        renderPagination();
    });

    let debounceTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            currentPage = 1;
            filterAndSortLogs();
        }, 120);
    });

    tagFilter.addEventListener('change', () => { currentPage = 1; filterAndSortLogs(); });
    sortFilter.addEventListener('change', () => { currentPage = 1; filterAndSortLogs(); });
    dateFrom.addEventListener('change', () => { currentPage = 1; filterAndSortLogs(); });
    dateTo.addEventListener('change', () => { currentPage = 1; filterAndSortLogs(); });

    // Initial Trigger Configuration execution
    filterAndSortLogs();

    // SYSTEM SYNC PIPELINE
    const syncBtn = document.getElementById('syncBtn');
    const syncText = document.getElementById('syncText');
    const alertBox = document.getElementById('alertBox');

    syncBtn.addEventListener('click', async function () {
        syncBtn.disabled = true;
        syncText.innerHTML = `<span class="spinner-border spinner-border-sm" style="width: 0.75rem; height: 0.75rem;"></span> Syncing...`;

        try {
            const res = await fetch('/bio-dtr-sync');
            if (!res.ok) throw new Error('Network error.');
            const data = await res.json();

            alertBox.innerHTML = `
                <div class="alert alert-success border-0 py-1.5 px-3 mb-2 shadow-sm rounded d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> <span>${data.message || 'Updated completely.'}</span>
                </div>
            `;
            setTimeout(() => location.reload(), 1000);
        } catch (err) {
            alertBox.innerHTML = `
                <div class="alert alert-danger border-0 py-1.5 px-3 mb-2 shadow-sm rounded d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> <span>Sync cycle failure.</span>
                </div>
            `;
            syncBtn.disabled = false;
            syncText.innerHTML = `<i class="bi bi-arrow-repeat"></i> Sync Logs`;
        }
    });
});
</script>
@endsection