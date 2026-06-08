<div class="h-100 d-flex flex-column p-3 hris-sidebar">

    <div class="sidebar-header mb-3 pb-2 border-bottom border-light">
        <div class="d-flex align-items-center gap-2">
            <div class="logo-box d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <i class="bi bi-person-badge-fill text-white" style="font-size: 1rem;"></i>
            </div>
            
            <div class="ms-1">
                <h6 class="text-dark fw-bold mb-0 tracking-tight" style="font-size: 0.85rem; line-height: 1.2;">HRIS System</h6>
                <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 0.6rem; letter-spacing: 0.05em; line-height: 1;">HR Platform</span>
            </div>
        </div>
    </div>

    <div class="sidebar-scroll-wrapper flex-grow-1 overflow-y-auto pe-1">

        <div class="menu-title">MAIN</div>

        <a href="/hr/dashboard" class="sidebar-link {{ request()->is('hr/dashboard*') || request()->is('admin/dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>

        <div class="menu-title mt-3">EMPLOYEE CORE</div>

        <a href="/hr/employee-201" class="sidebar-link {{ request()->is('hr/employee-201*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Employee Directory</span>
        </a>

        <a href="/departments" class="sidebar-link {{ request()->is('departments*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3-fill"></i><span>Departments</span>
        </a>

        <a href="/org-chart" class="sidebar-link {{ request()->is('org-chart*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2-fill"></i><span>Org Chart</span>
        </a>

        <div class="menu-title mt-3">TIME MANAGEMENT</div>

        <a href="/bio-dtr" class="sidebar-link {{ request()->is('bio-dtr*') ? 'active' : '' }}">
            <i class="bi bi-clock-fill"></i><span>Bio DTR</span>
        </a>

        <a href="/attendance" class="sidebar-link {{ request()->is('attendance*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i><span>Attendance Matrix</span>
        </a>

        <a href="/overtime" class="sidebar-link {{ request()->is('overtime*') ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i><span>Overtime Logs</span>
        </a>

        <div class="menu-title mt-3">LEAVE MANAGEMENT</div>

        <a href="/leave" class="sidebar-link {{ request()->is('leave') || request()->is('leave/requests*') ? 'active' : '' }}">
            <i class="bi bi-airplane-fill"></i><span>Leave Requests</span>
        </a>

        <a href="/leave/calendar" class="sidebar-link {{ request()->is('leave/calendar*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event-fill"></i><span>Leave Calendar</span>
        </a>

        <div class="menu-title mt-3">PAYROLL RUNS</div>

        <a href="/payroll" class="sidebar-link {{ request()->is('payroll*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i><span>Payroll Engine</span>
        </a>

        <a href="/payslips" class="sidebar-link {{ request()->is('payslips*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i><span>Payslips Hub</span>
        </a>

        <div class="menu-title mt-3">SELF SERVICE</div>

        <a href="/my-profile" class="sidebar-link {{ request()->is('my-profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i><span>My Profile</span>
        </a>

        <a href="/my-attendance" class="sidebar-link {{ request()->is('my-attendance*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i><span>My Attendance</span>
        </a>

        <div class="menu-title mt-3">SYSTEM SECURITY</div>

        <a href="/reports" class="sidebar-link {{ request()->is('reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i><span>Reports Engine</span>
        </a>

        <a href="/settings" class="sidebar-link {{ request()->is('settings*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i><span>Settings</span>
        </a>

    </div>

    <div class="pt-2 mt-auto sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>

</div>

<style>
/* MAIN SIDEBAR LIGHT ARCHITECTURE */
.hris-sidebar {
    background-color: #ffffff;
    border-right: 1px solid #e2e8f0;
}

/* HEADER boundary alignment */
.sidebar-header {
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

/* BRANDING BOX EMBED */
.logo-box {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 2px 4px rgba(79, 70, 229, 0.15);
}

/* GROUP LABEL METRICS */
.menu-title {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    color: #94a3b8;
    margin: 14px 0 6px 8px;
    text-transform: uppercase;
}

/* FLEXIBLE NAVIGATION LINKS */
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 12px;
    border-radius: 8px;
    color: #475569;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: background-color 0.15s ease, color 0.15s ease;
    margin-bottom: 2px;
}

/* TRANSITION STATES */
.sidebar-link:hover {
    background-color: #f8fafc;
    color: #0f172a;
}

/* ACTIVE HIGHLIGHT STATE */
.sidebar-link.active {
    background-color: #f0fdf4; /* Light green tint matching system attendance markers */
    color: #15803d;
    font-weight: 600;
}

.sidebar-link.active i {
    color: #16a34a;
}

/* Dynamic backup fallback matching colorway for primary modules if preferred */
/* Uncomment if you prefer an indigo theme over green context markers
.sidebar-link.active {
    background-color: #e0e7ff;
    color: #4338ca;
}
.sidebar-link.active i {
    color: #4f46e5;
}
*/

.sidebar-link i {
    font-size: 1.05rem;
    display: inline-flex;
    align-items: center;
}

/* HIGH DESIGNS INTERNAL SCROLLBARS */
.sidebar-scroll-wrapper::-webkit-scrollbar {
    width: 4px;
}

.sidebar-scroll-wrapper::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.sidebar-scroll-wrapper::-webkit-scrollbar-track {
    background: transparent;
}

/* SYSTEM CONTROL FOOTER */
.sidebar-footer {
    border-top: 1px solid #f1f5f9;
}

/* SOFT WARNING TERMINATION TOGGLE BUTTON */
.logout-btn {
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #fee2e2;
    background-color: #fef2f2;
    color: #991b1b;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

.logout-btn:hover {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #ffffff;
}
</style>