<div class="bg-dark text-white d-flex flex-column flex-shrink-0 p-3 shadow-lg vh-100" 
     style="width: 260px; position: fixed; top: 0; left: 0; z-index: 1020; border-right: 1px solid #2c3034;">
    
    <a href="{{ url('/dashboard') }}" class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none px-2 mt-2">
        <i class="bi bi-building-fill-gear text-primary fs-3 me-2"></i>
        <span class="fs-4 fw-bold tracking-tight">Metro Mobilia</span>
    </a>

    <hr class="text-secondary opacity-25 mt-0 mb-3">

    <ul class="nav nav-pills flex-column mb-auto">
        
        <li class="nav-item mb-1">
            <a href="{{ url('/dashboard') }}" 
               class="nav-link text-white d-flex align-items-center gap-3 transition-link {{ request()->is('dashboard') ? 'active bg-primary fw-semibold' : 'opacity-75' }}">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ url('/employees') }}" 
               class="nav-link text-white d-flex align-items-center gap-3 transition-link {{ request()->is('employees*') ? 'active bg-primary fw-semibold' : 'opacity-75' }}">
                <i class="bi bi-people-fill fs-5"></i>
                <span>Employees</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ url('/departments') }}" 
               class="nav-link text-white d-flex align-items-center gap-3 transition-link {{ request()->is('departments*') ? 'active bg-primary fw-semibold' : 'opacity-75' }}">
                <i class="bi bi-diagram-3-fill fs-5"></i>
                <span>Departments</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ url('/attendance') }}" 
               class="nav-link text-white d-flex align-items-center gap-3 transition-link {{ request()->is('attendance*') ? 'active bg-primary fw-semibold' : 'opacity-75' }}">
                <i class="bi bi-clock-history fs-5"></i>
                <span>Attendance</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ url('/reports') }}" 
               class="nav-link text-white d-flex align-items-center gap-3 transition-link {{ request()->is('reports*') ? 'active bg-primary fw-semibold' : 'opacity-75' }}">
                <i class="bi bi-file-earmark-bar-graph-fill fs-5"></i>
                <span>Reports</span>
            </a>
        </li>

    </ul>

    <hr class="text-secondary opacity-25 my-3">

    @auth
    <div class="d-flex flex-column gap-2 px-2 pb-2">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-person-circle text-secondary fs-5"></i>
            <span class="small text-truncate text-muted">{{ Auth::user()->name }}</span>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="bi bi-box-arrow-left"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
    @endauth

</div>

<style>
    /* Smooth interactive animations for a native app feel */
    .transition-link {
        transition: all 0.15s ease-in-out;
        border-radius: 8px;
        padding: 0.6rem 1rem;
    }
    .transition-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.08);
        opacity: 1 !important;
        transform: translateX(4px);
    }
    .tracking-tight {
        letter-spacing: -0.5px;
    }
</style>