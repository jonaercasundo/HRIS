@extends('layouts.app')

@section('content')
<!-- ISOLATED DASHBOARD STYLING ENGINE Overrides -->
<style>
    :root {
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --primary-grad: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }

    .glass-header {
        background: var(--primary-grad);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Abstract decorative background shapes for the header */
    .glass-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .modern-card {
        border: 1px solid rgba(241, 245, 249, 0.8);
        border-radius: 20px;
        background: var(--card-bg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .quick-action-btn {
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
    }

    .quick-action-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        color: #0f172a;
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-modern tbody tr {
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.01);
        border-radius: 12px;
        transition: all 0.15s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8fafc;
    }

    .table-modern td, .table-modern th {
        padding: 16px 20px;
        border: none;
    }

    .table-modern td:first-child, .table-modern th:first-child {
        border-radius: 12px 0 0 12px;
    }

    .table-modern td:last-child, .table-modern th:last-child {
        border-radius: 0 12px 12px 0;
    }

    .badge-pill-modern {
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: inline-block;
    }
</style>

<div class="container-fluid px-0">

    <!-- MASTER BRAND HEADER CONTEXT PANEL -->
    <div class="glass-header mb-5 shadow-sm">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4 position-relative" style="z-index: 2;">
            <div>
                <h1 class="fw-bold tracking-tight mb-1" style="font-size: 2rem;">Admin Dashboard</h1>
                <p class="mb-0 text-white-50 fs-6 fw-medium">
                    Welcome back. Here’s your business analytics overview today.
                </p>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-white text-dark bg-white px-4 py-2.5 rounded-3 fw-bold shadow-sm border-0 d-flex align-items-center gap-2 transition" style="font-size: 0.9rem;">
                <i class="bi bi-people-fill text-indigo" style="color: #4f46e5;"></i>
                Manage Users
            </a>
        </div>
    </div>

    <!-- METRICS PLOTS GRID -->
    <div class="row g-4 mb-5">

        <!-- METRIC ITEM: TOTAL USER STACK -->
        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">Total Users</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">
                            {{ $totalUsers ?? \App\Models\User::count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #e0e7ff; color: #4f46e5;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- METRIC ITEM: HR NODES ACCESS -->
        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">HR Operations</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">
                            {{ $hrCount ?? \App\Models\User::where('role','hr')->count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #d1fae5; color: #10b981;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- METRIC ITEM: WORKER INSTANCES -->
        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">Active Employees</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">
                            {{ $employeeCount ?? \App\Models\User::where('role','employee')->count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #fef3c7; color: #f59e0b;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- PIPELINE SHORTCUT LINKS COMPONENT -->
    <div class="modern-card p-4 mb-5">
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Quick Actions</h5>
            <p class="text-muted small mb-0">Instant access operational pipeline shortcuts</p>
        </div>

        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('users.index') }}" class="btn quick-action-btn d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill" style="color: #4f46e5;"></i>
                View Users Directory
            </a>

            <a href="{{ route('register') }}" class="btn quick-action-btn d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill" style="color: #10b981;"></i>
                Onboard New User
            </a>

            <a href="/reports" class="btn quick-action-btn d-flex align-items-center gap-2">
                <i class="bi bi-graph-up-arrow text-secondary"></i>
                Generate Analytics
            </a>
        </div>
    </div>

    <!-- LOG REGISTRATION MATRIX DATATABLE -->
    <div class="modern-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Recent Registrations</h5>
                <p class="text-muted small mb-0">Monitor logs of the last 5 registered user profiles</p>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm rounded-3 fw-bold px-3 py-2 border-0" style="color: #4f46e5; font-size: 0.85rem;">
                View Entire Directory <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr class="text-uppercase tracking-wider small fw-bold text-muted" style="font-size: 0.72rem;">
                        <th>User Profile</th>
                        <th>Email System</th>
                        <th>Role Node</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Fallback logic inside pipeline block framework container context
                        $recentUsers = $recentUsers ?? \App\Models\User::latest()->take(5)->get();
                    @endphp

                    @foreach($recentUsers as $user)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.8rem; background-color: #f1f5f9; color: #475569;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>{{ $user->name }}</div>
                                </div>
                            </td>

                            <td class="text-muted fw-medium" style="font-size: 0.9rem;">
                                {{ $user->email }}
                            </td>

                            <td>
                                @php
                                    $badgeStyle = match($user->role) {
                                        'admin' => 'background-color: #e0e7ff; color: #4f46e5;',
                                        'hr' => 'background-color: #d1fae5; color: #10b981;',
                                        default => 'background-color: #fef3c7; color: #f59e0b;'
                                    };
                                @endphp

                                <span class="badge-pill-modern" style="{{ $badgeStyle }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection