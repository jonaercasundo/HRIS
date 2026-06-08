@extends('layouts.app_hr')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Biometric DTR</h3>
            <small class="text-muted">BIO-1 Attendance Logs</small>
        </div>

        <button id="syncBtn" class="btn btn-primary px-4 d-flex align-items-center gap-2">
            <span id="syncText">Sync Attendance</span>
        </button>
    </div>

    <!-- ALERT -->
    <div id="alertBox" class="mb-3"></div>

    <!-- CARD -->
    <div class="card border-0 shadow-sm rounded-4">

        <!-- FILTER BAR -->
        <div class="card-header bg-white border-0">
            <div class="row g-2">

                <!-- Search -->
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="Search UID, Employee, Device...">
                </div>

                <!-- Tag -->
                <div class="col-md-2">
                    <select id="tagFilter" class="form-select">
                        <option value="">All</option>
                        <option value="IN">IN</option>
                        <option value="OUT">OUT</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="col-md-3">
                    <select id="sortFilter" class="form-select">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="emp_asc">Employee A-Z</option>
                        <option value="emp_desc">Employee Z-A</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-md-3 d-flex gap-2">
                    <input type="date" id="dateFrom" class="form-control">
                    <input type="date" id="dateTo" class="form-control">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>UID</th>
                            <th>Employee No</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>State</th>
                            <th>Tag</th>
                            <th>Device</th>
                        </tr>
                    </thead>

                    <tbody id="logTable">
                        @forelse($logs as $log)
                            <tr
                                data-uid="{{ $log->uid }}"
                                data-employee="{{ $log->employee_no }}"
                                data-date="{{ $log->date_log }}"
                                data-time="{{ $log->time_log }}"
                                data-tag="{{ $log->tag }}"
                                data-device="{{ $log->bio_name }}"
                            >
                                <td class="text-muted">{{ $log->uid }}</td>
                                <td class="fw-semibold">{{ $log->employee_no }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($log->date_log)->format('F d, Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($log->time_log)->format('h:i A') }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $log->state }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge rounded-pill bg-{{ $log->tag == 'IN' ? 'success' : 'danger' }}">
                                        {{ $log->tag }}
                                    </span>
                                </td>

                                <td class="text-muted">{{ $log->bio_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    No logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
const searchInput = document.getElementById('searchInput');
const tagFilter = document.getElementById('tagFilter');
const sortFilter = document.getElementById('sortFilter');
const dateFrom = document.getElementById('dateFrom');
const dateTo = document.getElementById('dateTo');

const tableBody = document.getElementById('logTable');

// ---------------- FILTER ----------------
function filterLogs() {

    let rows = Array.from(tableBody.querySelectorAll('tr'));

    const search = searchInput.value.toLowerCase();
    const tag = tagFilter.value;
    const from = dateFrom.value ? new Date(dateFrom.value) : null;
    const to = dateTo.value ? new Date(dateTo.value) : null;

    rows.forEach(row => {

        const uid = row.dataset.uid?.toLowerCase() || '';
        const emp = row.dataset.employee?.toLowerCase() || '';
        const device = row.dataset.device?.toLowerCase() || '';
        const rowTag = row.dataset.tag;

        const rowDate = new Date(row.dataset.date);

        let show =
            (uid.includes(search) ||
             emp.includes(search) ||
             device.includes(search));

        if (tag && rowTag !== tag) show = false;

        if (from && rowDate < from) show = false;

        if (to && rowDate > to) show = false;

        row.style.display = show ? '' : 'none';
    });

    sortLogs();
}

// ---------------- SORT ----------------
function sortLogs() {

    let rows = Array.from(tableBody.querySelectorAll('tr'))
        .filter(r => r.style.display !== 'none');

    const mode = sortFilter.value;

    rows.sort((a, b) => {

        const dateA = new Date(a.dataset.date + ' ' + a.dataset.time);
        const dateB = new Date(b.dataset.date + ' ' + b.dataset.time);

        const empA = a.dataset.employee || '';
        const empB = b.dataset.employee || '';

        switch (mode) {
            case 'latest':
                return dateB - dateA;

            case 'oldest':
                return dateA - dateB;

            case 'emp_asc':
                return empA.localeCompare(empB);

            case 'emp_desc':
                return empB.localeCompare(empA);
        }
    });

    rows.forEach(row => tableBody.appendChild(row));
}

// ---------------- EVENTS ----------------
searchInput.addEventListener('input', filterLogs);
tagFilter.addEventListener('change', filterLogs);
sortFilter.addEventListener('change', filterLogs);
dateFrom.addEventListener('change', filterLogs);
dateTo.addEventListener('change', filterLogs);

// initial
filterLogs();


// ---------------- SYNC ----------------
const syncBtn = document.getElementById('syncBtn');
const syncText = document.getElementById('syncText');
const alertBox = document.getElementById('alertBox');

syncBtn.addEventListener('click', async function () {

    syncBtn.disabled = true;
    syncText.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Syncing...`;

    try {
        const res = await fetch('/bio-dtr-sync');
        const data = await res.json();

        alertBox.innerHTML = `
            <div class="alert alert-success shadow-sm rounded-3">
                ${data.message}
            </div>
        `;

        setTimeout(() => location.reload(), 1000);

    } catch (err) {
        alertBox.innerHTML = `
            <div class="alert alert-danger shadow-sm rounded-3">
                Sync failed. Try again.
            </div>
        `;
    } finally {
        syncBtn.disabled = false;
        syncText.innerText = "Sync Attendance";
    }

});
</script>

@endsection