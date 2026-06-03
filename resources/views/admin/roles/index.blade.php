@extends('layouts.app')

@section('content')
<style>
    :root {
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
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

    .action-btn-group .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
</style>

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold tracking-tight mb-1" style="font-size: 2rem; color: var(--text-main);">Roles & Permissions</h1>
            <p class="text-muted small mb-0">Configure user security parameters and application module access nodes</p>
        </div>

        <a href="{{ route('roles.create') }}" class="btn text-white px-4 py-2.5 rounded-3 fw-bold border-0 d-flex align-items-center gap-2 transition" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); font-size: 0.9rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);">
            <i class="bi bi-plus-circle-fill"></i>
            Create New Role
        </a>
    </div>

    <div class="row g-4 mb-5">

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">Total Roles</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">3</h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #e0e7ff; color: #4f46e5;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">Active Permissions</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">12</h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #d1fae5; color: #10b981;">
                        <i class="bi bi-key-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="modern-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase fw-bold text-muted small tracking-wider d-block mb-1" style="font-size: 0.75rem;">Assigned Identifiers</span>
                        <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 2.25rem;">48</h2>
                    </div>
                    <div class="icon-wrapper" style="background-color: #fef3c7; color: #f59e0b;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modern-card p-4">
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">System Core Authorization Matrices</h5>
            <p class="text-muted small mb-0">Active functional permissions profiles parsed out per user node context</p>
        </div>

        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr class="text-uppercase tracking-wider small fw-bold text-muted" style="font-size: 0.72rem;">
                        <th>System Role</th>
                        <th>Access Scope Description</th>
                        <th>User Count</th>
                        <th class="text-end" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td>
                            <span class="badge-pill-modern" style="background-color: #e0e7ff; color: #4f46e5;">
                                admin
                            </span>
                        </td>
                        <td class="text-dark fw-medium" style="font-size: 0.9rem;">
                            Full root configuration authority across active software engines
                        </td>
                        <td class="text-muted fw-semibold" style="font-size: 0.9rem;">
                            5 profiles
                        </td>
                        <td>
                            <div class="action-btn-group d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-light border-0 text-primary" title="Edit Properties">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.85rem;"></i>
                                </a>
                                <button type="button" class="btn btn-light border-0 text-danger" title="Delete Scope Definition">
                                    <i class="bi bi-trash3-fill" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="badge-pill-modern" style="background-color: #d1fae5; color: #10b981;">
                                hr
                            </span>
                        </td>
                        <td class="text-dark fw-medium" style="font-size: 0.9rem;">
                            Operational access bound exclusively inside HRIS organizational modules
                        </td>
                        <td class="text-muted fw-semibold" style="font-size: 0.9rem;">
                            12 profiles
                        </td>
                        <td>
                            <div class="action-btn-group d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-light border-0 text-primary" title="Edit Properties">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.85rem;"></i>
                                </a>
                                <button type="button" class="btn btn-light border-0 text-danger" title="Delete Scope Definition">
                                    <i class="bi bi-trash3-fill" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="badge-pill-modern" style="background-color: #fef3c7; color: #f59e0b;">
                                employee
                            </span>
                        </td>
                        <td class="text-dark fw-medium" style="font-size: 0.9rem;">
                            Isolated self-service ledger tracking context configuration visibility
                        </td>
                        <td class="text-muted fw-semibold" style="font-size: 0.9rem;">
                            31 profiles
                        </td>
                        <td>
                            <div class="action-btn-group d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-light border-0 text-primary" title="Edit Properties">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.85rem;"></i>
                                </a>
                                <button type="button" class="btn btn-light border-0 text-danger" title="Delete Scope Definition">
                                    <i class="bi bi-trash3-fill" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection