<!-- SIDEBAR CONTAINER -->
<div class="h-100 d-flex flex-column p-4 hris-sidebar">

    <!-- HEADER -->
    <div class="sidebar-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="logo-box">
                <i class="bi bi-person-badge-fill text-white"></i>
            </div>
            <div>
                <h5 class="text-white fw-bold mb-0">HRIS System</h5>
                <small class="text-white text-uppercase">HR Platform</small>
            </div>
        </div>
    </div>

    <!-- SCROLL AREA -->
    <div class="sidebar-scroll-wrapper flex-grow-1 overflow-y-auto pe-1">

        <!-- MAIN -->
        <div class="menu-title">MAIN</div>

        <a href="/hr/dashboard" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>

        <!-- EMPLOYEES -->
        <div class="menu-title mt-4">EMPLOYEE CORE</div>

        <a href="/employees" class="sidebar-link {{ request()->is('employees*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Employee Directory</span>
        </a>

        <a href="/departments" class="sidebar-link {{ request()->is('departments*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3-fill"></i><span>Departments</span>
        </a>

        <a href="/org-chart" class="sidebar-link {{ request()->is('org-chart*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2-fill"></i><span>Org Chart</span>
        </a>

        <!-- TIME -->
        <div class="menu-title mt-4">TIME MANAGEMENT</div>

        <a href="/attendance" class="sidebar-link {{ request()->is('attendance*') ? 'active' : '' }}">
            <i class="bi bi-clock-fill"></i><span>Attendance</span>
        </a>

        <a href="/overtime" class="sidebar-link {{ request()->is('overtime*') ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i><span>Overtime</span>
        </a>

        <!-- LEAVE -->
        <div class="menu-title mt-4">LEAVE</div>

        <a href="/leave" class="sidebar-link {{ request()->is('leave*') ? 'active' : '' }}">
            <i class="bi bi-airplane-fill"></i><span>Leave Requests</span>
        </a>

        <a href="/leave/calendar" class="sidebar-link {{ request()->is('leave/calendar*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event-fill"></i><span>Leave Calendar</span>
        </a>

        <!-- PAYROLL -->
        <div class="menu-title mt-4">PAYROLL</div>

        <a href="/payroll" class="sidebar-link {{ request()->is('payroll*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i><span>Payroll Engine</span>
        </a>

        <a href="/payslips" class="sidebar-link {{ request()->is('payslips*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i><span>Payslips</span>
        </a>

        <!-- SELF SERVICE -->
        <div class="menu-title mt-4">SELF SERVICE</div>

        <a href="/my-profile" class="sidebar-link {{ request()->is('my-profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i><span>My Profile</span>
        </a>

        <a href="/my-attendance" class="sidebar-link {{ request()->is('my-attendance*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i><span>My Attendance</span>
        </a>

        <!-- SYSTEM -->
        <div class="menu-title mt-4">SYSTEM</div>

        <a href="/reports" class="sidebar-link {{ request()->is('reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i><span>Reports</span>
        </a>

        <a href="/settings" class="sidebar-link {{ request()->is('settings*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i><span>Settings</span>
        </a>

    </div>

    <!-- FOOTER -->
    <div class="pt-3 mt-auto sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

</div>

<!-- ================= DESIGN SYSTEM ================= -->
<style>
/* MAIN SIDEBAR */
.hris-sidebar {
    background-color: #09090b;
    border-right: 1px solid #18181b;
}

/* HEADER */
.sidebar-header {
    padding-bottom: 18px;
    border-bottom: 1px solid #18181b;
}

/* LOGO */
.logo-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* MENU TITLE */
.menu-title {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #52525b;
    margin: 14px 0 10px 12px;
}

/* LINK BASE */
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 12px;
    color: #a1a1aa;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 4px;
}

/* HOVER EFFECT */
.sidebar-link:hover {
    background: #141416;
    color: #fafafa;
    transform: translateX(3px);
}

/* ACTIVE STATE */
.sidebar-link.active {
    background: #18181b;
    color: #ffffff;
    border: 1px solid #27272a;
    font-weight: 600;
}

.sidebar-link.active i {
    color: #818cf8;
}

/* ICON */
.sidebar-link i {
    font-size: 1.15rem;
}

/* SCROLLBAR */
.sidebar-scroll-wrapper::-webkit-scrollbar {
    width: 4px;
}

.sidebar-scroll-wrapper::-webkit-scrollbar-thumb {
    background: #27272a;
    border-radius: 20px;
}

/* FOOTER */
.sidebar-footer {
    border-top: 1px solid #18181b;
}

/* LOGOUT BUTTON */
.logout-btn {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid rgba(239, 68, 68, 0.15);
    background: rgba(239, 68, 68, 0.05);
    color: #fca5a5;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
}
</style>