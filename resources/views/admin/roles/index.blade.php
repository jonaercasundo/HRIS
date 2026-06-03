@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Roles & Permissions</h2>
            <small class="text-muted">Manage system access control</small>
        </div>

        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Create Role
        </a>
    </div>

    <!-- Cards -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Roles</h6>
                    <h3 class="fw-bold mb-0">3</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Active Permissions</h6>
                    <h3 class="fw-bold mb-0">12</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Users Assigned Roles</h6>
                    <h3 class="fw-bold mb-0">48</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Roles Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">System Roles</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Users</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                <span class="badge bg-primary">Admin</span>
                            </td>
                            <td>Full system access</td>
                            <td>5</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="badge bg-success">HR</span>
                            </td>
                            <td>HR module access</td>
                            <td>12</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="badge bg-warning text-dark">Employee</span>
                            </td>
                            <td>Basic employee access</td>
                            <td>31</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection