@extends('layouts.app')

@section('content')

<style>
    :root {
        --dash-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: rgba(241, 245, 249, 0.9);
    }

    body {
        background-color: var(--dash-bg);
        color: var(--text-main);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .modern-card {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        background: var(--card-bg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        padding: 16px 24px;
        border: none;
    }

    .table-modern tbody tr {
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        transform: scale(1.002);
        background: #fdfdfd;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
    }

    .table-modern td {
        padding: 16px 24px;
        border: none;
        vertical-align: middle;
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
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        display: inline-block;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-alert {
        border-radius: 14px;
        border: none;
        background: #d1fae5;
        color: #065f46;
        padding: 16px 20px;
        font-weight: 500;
    }
</style>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-black tracking-tight mb-1" style="font-size: 1.75rem;">User Management</h2>
            <p class="text-muted small mb-0">Review permissions, roles, and structural access keys here.</p>
        </div>
        
        <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none;">
            <i class="bi bi-person-plus-fill"></i>
            Onboard New User
        </a>
    </div>

    @if(session('success'))
        <div class="alert toast-alert shadow-sm mb-4 d-flex align-items-center gap-3 animate__animated animate__fadeIn" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="modern-card p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div class="text-muted small fw-medium">
                Showing <span class="text-dark fw-bold">{{ $users->count() }}</span> registered entities
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>User Profile</th>
                        <th>Email System</th>
                        <th>Role Node</th>
                        <th width="150" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-muted font-monospace small">
                                #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-placeholder shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="fw-bold text-slate">{{ $user->name }}</div>
                                </div>
                            </td>

                            <td class="text-muted fw-medium">
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
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="btn btn-light action-btn text-warning bg-light border-0" 
                                       title="Edit User Info">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-light action-btn text-danger bg-light border-0"
                                                type="submit"
                                                title="Revoke and Delete Access"
                                                onclick="return confirm('Are you sure you want to completely remove this user profile? This action is irreversible.')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection