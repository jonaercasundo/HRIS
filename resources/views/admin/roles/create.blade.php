@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Create Role</h2>
            <small class="text-muted">Add a new system role</small>
        </div>

        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <!-- Role Name -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="e.g. Admin, HR, Accounting"
                           required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3"
                              placeholder="Brief role description"></textarea>
                </div>

                <!-- Permissions Placeholder -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Permissions</label>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="users.manage">
                        <label class="form-check-label">Manage Users</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="reports.view">
                        <label class="form-check-label">View Reports</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="roles.manage">
                        <label class="form-check-label">Manage Roles</label>
                    </div>

                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i>
                    Save Role
                </button>

            </form>

        </div>
    </div>

</div>
@endsection