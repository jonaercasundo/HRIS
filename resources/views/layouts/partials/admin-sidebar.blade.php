<!-- SIDEBAR CONTAINER WRAPPER -->
<div class="h-100 d-flex flex-column p-4" style="background-color: #09090b; border-right: 1px solid #18181b;">

    <!-- HEADER BRANDING AREA -->
    <div class="sidebar-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="logo-box shadow-sm">
                <i class="bi bi-buildings-fill text-white"></i>
            </div>
            <div>
                <h5 class="text-white fw-bold mb-0 tracking-tight" style="font-size: 1.05rem;">
                    Metro Mobilia
                </h5>
                <small class="text-muted fw-semibold uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em; color: #a1a1aa !important;">
                    ERP / Admin Panel
                </small>
            </div>
        </div>
    </div>

    <!-- SCROLLABLE LINK WORKSPACE AREA -->
    <div class="sidebar-scroll-wrapper flex-grow-1 overflow-y-auto pe-1" style="max-height: calc(100vh - 180px);">
        
        <!-- MODULE: SYSTEM MAIN -->
        <div class="menu-title">MAIN MANAGEMENT</div>

        <a href="/admin/dashboard" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="/users" class="sidebar-link {{ request()->is('users*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>User Management</span>
        </a>

        <a href="{{ route('roles.index') }}"
        class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Roles & Permissions</span>
        </a>

        <!-- MODULE: HRIS PIPELINE -->
        <div class="menu-title mt-4">HRIS ECOSYSTEM</div>

        <a href="/employees" class="sidebar-link {{ request()->is('employees*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i>
            <span>Employees</span>
        </a>

        <a href="/attendance" class="sidebar-link {{ request()->is('attendance*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i>
            <span>Attendance Logs</span>
        </a>

        <a href="/leave-management" class="sidebar-link {{ request()->is('leave-management*') ? 'active' : '' }}">
            <i class="bi bi-airplane-fill"></i>
            <span>Leave Tracker</span>
        </a>

        <a href="/payroll" class="sidebar-link {{ request()->is('payroll*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Payroll Engine</span>
        </a>

        <!-- MODULE: ACCOUNTING LEDGER -->
        <div class="menu-title mt-4">FINANCIAL LEDGER</div>

        <a href="/accounting/dashboard" class="sidebar-link {{ request()->is('accounting/dashboard*') ? 'active' : '' }}">
            <i class="bi bi-calculator-fill"></i>
            <span>Accounting Home</span>
        </a>

        <a href="/journal-entries" class="sidebar-link {{ request()->is('journal-entries*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Journal Entries</span>
        </a>

        <a href="/financial-reports" class="sidebar-link {{ request()->is('financial-reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Financial Statements</span>
        </a>

        <!-- MODULE: INVENTORY CORE -->
        <div class="menu-title mt-4">INVENTORY CORE</div>

        <a href="/products" class="sidebar-link {{ request()->is('products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i>
            <span>Product Master</span>
        </a>

        <a href="/stocks" class="sidebar-link {{ request()->is('stocks*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i>
            <span>Stock Allocations</span>
        </a>

        <a href="/suppliers" class="sidebar-link {{ request()->is('suppliers*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            <span>Supplier Nodes</span>
        </a>

        <!-- MODULE: INTELLIGENCE METRICS -->
        <div class="menu-title mt-4">SYSTEM ENGINE</div>

        <a href="/reports" class="sidebar-link {{ request()->is('reports') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>Analytical Reports</span>
        </a>

        <a href="/audit-logs" class="sidebar-link {{ request()->is('audit-logs*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Security Audit Logs</span>
        </a>

        <a href="/settings" class="sidebar-link {{ request()->is('settings*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i>
            <span>Global Settings</span>
        </a>

    </div>

    <!-- LOWER FOOTER ACCESS TERMINATION PORTAL -->
    <div class="pt-3 mt-auto" style="border-top: 1px solid #18181b;">
        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
            @csrf
            <button type="submit" class="logout-btn btn w-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out Account</span>
            </button>
        </form>
    </div>

</div>

<!-- COMPONENT ISOLATED STYLE HOOKS -->
<style>
    /* Custom thin scrollbar layout for elegant styling views */
    .sidebar-scroll-wrapper::-webkit-scrollbar {
        width: 4px;
    }
    .sidebar-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #27272a;
        border-radius: 20px;
    }

    .sidebar-header {
        padding-bottom: 20px;
        border-bottom: 1px solid #18181b;
    }

    .logo-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .menu-title {
        color: #52525b;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
        padding-left: 12px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 12px;
        text-decoration: none;
        color: #a1a1aa;
        margin-bottom: 4px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .sidebar-link:hover {
        background: #141416;
        color: #fafafa;
        transform: translateX(3px);
    }

    .sidebar-link.active {
        background: #18181b;
        color: #ffffff;
        font-weight: 600;
        border: 1px solid #27272a;
    }

    .sidebar-link.active i {
        color: #818cf8; /* Indigo Accent Icon Glow */
    }

    .sidebar-link i {
        font-size: 1.15rem;
        transition: color 0.2s ease;
    }

    .logout-btn {
        border: 1px solid rgba(239, 68, 68, 0.1);
        background: rgba(239, 68, 68, 0.04);
        color: #fca5a5;
        padding: 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .logout-btn:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
    }
</style>