@extends('layouts.app')

@section('content')

<style>
    :root {
        --card-bg: #ffffff;
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

    .glass-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
    }

    .modern-card {
        border: 1px solid rgba(241, 245, 249, 0.8);
        border-radius: 20px;
        background: var(--card-bg);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        transition: 0.25s ease;
    }

    .modern-card:hover {
        transform: translateY(-4px);
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-action-btn {
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #334155;
        transition: 0.2s;
    }

    .quick-action-btn:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-modern tbody tr {
        background: #fff;
        border-radius: 12px;
    }

    .badge-pill-modern {
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="glass-header mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-1">Admin Dashboard</h1>
                <p class="mb-0 text-white-50">Welcome back. Here’s your overview today.</p>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-light fw-bold">
                <i class="bi bi-people-fill"></i> Manage Users
            </a>
        </div>
    </div>

    {{-- METRICS --}}
    <div class="row g-4 mb-5">

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Total Users</small>
                        <h2 class="mb-0">
                            {{ $totalUsers ?? \App\Models\User::count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background:#e0e7ff;color:#4f46e5;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">HR Operations</small>
                        <h2 class="mb-0">
                            {{ $hrCount ?? \App\Models\User::where('role','hr')->count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background:#d1fae5;color:#10b981;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Active Employees</small>
                        <h2 class="mb-0">
                            {{ $employeeCount ?? \App\Models\User::where('role','employee')->count() }}
                        </h2>
                    </div>
                    <div class="icon-wrapper" style="background:#fef3c7;color:#f59e0b;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- QUICK ACTIONS --}}
    <div class="modern-card p-4 mb-5">
        <h5 class="fw-bold mb-1">Quick Actions</h5>
        <p class="text-muted small">Instant access shortcuts</p>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('users.index') }}" class="quick-action-btn">
                <i class="bi bi-person-lines-fill"></i> Users
            </a>

            <a href="{{ route('register') }}" class="quick-action-btn">
                <i class="bi bi-person-plus-fill"></i> Add User
            </a>

            <a href="/reports/daily" class="quick-action-btn">
                <i class="bi bi-graph-up"></i> Daily Report
            </a>
        </div>
    </div>

    {{-- RECENT USERS --}}
    <div class="modern-card p-4">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h5 class="fw-bold mb-0">Recent Registrations</h5>
                <small class="text-muted">Last 5 users</small>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>

        @php
            $recentUsers = $recentUsers ?? \App\Models\User::latest()->take(5)->get();
        @endphp

        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentUsers as $user)
                        <tr>
                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td class="text-muted">
                                {{ $user->email }}
                            </td>

                            <td>
                                @php
                                    $style = match($user->role) {
                                        'admin' => 'background:#e0e7ff;color:#4f46e5;',
                                        'hr' => 'background:#d1fae5;color:#10b981;',
                                        default => 'background:#fef3c7;color:#f59e0b;'
                                    };
                                @endphp

                                <span class="badge-pill-modern" style="{{ $style }}">
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