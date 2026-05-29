@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Admin Dashboard</h3>
            <small class="text-muted">HRIS System Overview</small>
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="bi bi-people-fill me-1"></i> Manage Users
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ \App\Models\User::count() }}</h5>
                        <small class="text-muted">Total Users</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ \App\Models\User::where('role','hr')->count() }}</h5>
                        <small class="text-muted">HR Users</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                        <i class="bi bi-person fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ \App\Models\User::where('role','employee')->count() }}</h5>
                        <small class="text-muted">Employees</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="mb-3">Quick Actions</h5>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-plus"></i> View Users
                </a>

                <a href="{{ route('register') }}" class="btn btn-outline-success">
                    <i class="bi bi-person-plus-fill"></i> Add User
                </a>

                <a href="/reports/daily" class="btn btn-outline-dark">
                    <i class="bi bi-graph-up"></i> Daily Report
                </a>

            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Recent Users</h5>
                <a href="{{ route('users.index') }}" class="small text-decoration-none">View all</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection