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
    }
    
    /* Search Bar Input Style */
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

    /* Inline Table Inputs */
    .table-input-sm {
        font-size: 0.8rem !important;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        color: #334155;
    }
    .table-input-sm:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        outline: 0;
    }
    
    /* SaaS Data Table Profile */
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
        padding: 10px 16px !important;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tr:hover td {
        background-color: #f8fafc;
    }

    /* Ultra compact micro control row buttons */
    .btn-micro-action {
        padding: 5px 12px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    /* Custom Toast Notification Placement */
    .toast-container-custom {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1090;
    }
</style>

<div class="dashboard-wrapper px-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-slate-900 tracking-tight" style="color: #0f172a;">Employee 201 File</h4>
            <p class="text-muted mb-0 d-flex align-items-center gap-1.5" style="font-size: 0.8rem; color: #64748b;">
                <i class="bi bi-folder2-open text-indigo-600" style="color: #4f46e5;"></i> 
                Manage Master Corporate Records and Verification Profiles
            </p>
        </div>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text input-icon-span px-2.5 py-0"><i class="bi bi-search" style="font-size: 0.8rem;"></i></span>
                        <input type="text" id="employeeSearch" class="form-control form-input-modern" placeholder="Search system profiles...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern overflow-hidden shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0" id="employeeTable">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 15%;">Employee ID</th>
                            <th style="width: 30%;">Full Name</th>
                            <th style="width: 40%;">Core Email Identity</th>
                            <th class="pe-4 text-end" style="width: 15%;">Operations</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse($employees as $emp)
                            <tr>
                                <td class="ps-4 fw-semibold font-monospace text-slate-700" style="color: #334155;">{{ $emp->employeeNo }}</td>
                                <td class="fw-medium text-slate-900" style="color: #0f172a;">{{ $emp->firstName }} {{ $emp->lastName }}</td>
                                <td>
                                    <input type="email"
                                           class="form-control table-input-sm email-input w-100 font-monospace"
                                           data-emp="{{ $emp->employeeNo }}"
                                           value="{{ $emp->email }}"
                                           placeholder="name@company.com"
                                           autocomplete="off">
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-primary btn-micro-action btn-save shadow-sm"
                                            style="background-color: #4f46e5; border-color: #4338ca;"
                                            data-emp="{{ $emp->employeeNo }}">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span>Save</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted bg-light border-0">
                                    <div class="py-4">
                                        <div class="p-3 bg-white d-inline-block rounded-circle shadow-sm border mb-3">
                                            <i class="bi bi-people text-slate-400" style="color: #94a3b8; font-size: 1.5rem;"></i>
                                        </div>
                                        <p class="mb-1 fw-semibold text-slate-800" style="color: #1e293b; font-size: 0.9rem;">No Identity Records Compiled</p>
                                        <small class="text-muted d-block" style="font-size: 0.775rem;">Add workers onto your server ecosystem database infrastructure to populate registry logs.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($employees->hasPages())
            <div class="card-footer bg-white border-top border-slate-100 px-4 py-3 d-flex justify-content-center">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>

<div class="toast-container-custom">
    <div id="feedbackToast" class="toast align-items-center text-white border-0 shadow-lg rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex p-2.5">
            <div class="toast-body d-flex align-items-center gap-2 fw-medium" style="font-size: 0.815rem;" id="toastMessage">
                </div>
            <button type="button" class="btn-close btn-close-white m-auto me-2 shadow-none" style="font-size: 0.7rem;" data-bs-dismiss="none" onclick="hideToast()"></button>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- NOTIFICATION HANDLER ---
    const toastElement = document.getElementById('feedbackToast');
    
    function showNotification(message, type = 'success') {
        const toastMessage = document.getElementById('toastMessage');
        
        if(type === 'success') {
            toastElement.style.backgroundColor = '#10b981'; // Modern green
            toastMessage.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${message}`;
        } else {
            toastElement.style.backgroundColor = '#ef4444'; // Modern red
            toastMessage.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> ${message}`;
        }
        
        toastElement.classList.add('show');
        setTimeout(hideToast, 4000);
    }

    window.hideToast = function() {
        toastElement.classList.remove('show');
    };

    // --- RE-ARCHITECTED EVENT LISTENER DELEGATION ---
    // Using root element capturing to prevent execution dropout over dynamic pagination updates
    document.getElementById('employeeTable').addEventListener('click', function(e) {
        let btn = e.target.closest('.btn-save');
        if (!btn || btn.disabled) return;

        let empNo = btn.getAttribute('data-id') || btn.dataset.emp;
        let emailInput = document.querySelector(`.email-input[data-emp="${empNo}"]`);
        
        if (!emailInput) return;
        let emailValue = emailInput.value.trim();

        // Base Client Validation Checklist
        if(emailValue !== "" && !emailInput.checkValidity()) {
            showNotification('Invalid structure configuration applied to target email identity format.', 'error');
            emailInput.focus();
            return;
        }

        // Mutation UI Locking Transitions
        btn.disabled = true;
        const fallbackText = btn.innerHTML;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;

        fetch('/hr/employee-201/save-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                employee_no: empNo,
                email: emailValue
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('System ledger validation mismatch or connection error');
            return res.json();
        })
        .then(data => {
            showNotification(data.message || 'Identity verification data parameters saved successfully.', 'success');
        })
        .catch(err => {
            showNotification(err.message || 'Fatal exception detected compiling directory state.', 'error');
        })
        .finally(() => {
            // Restore UI Interaction Capabilities
            btn.disabled = false;
            btn.innerHTML = fallbackText;
        });
    });

    // --- MINIMAL RUNTIME FRONTEND DIRECTORY SEARCH FILTER ---
    document.getElementById('employeeSearch').addEventListener('input', function(e) {
        let keyword = e.target.value.toLowerCase().trim();
        let rows = document.querySelectorAll('#employeeTable tbody tr');

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Ignore standard empty fallback components
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
});
</script>