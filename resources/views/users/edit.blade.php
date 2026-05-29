@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Edit User
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select">
                        <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="hr" {{ $user->role == 'hr' ? 'selected' : '' }}>HR</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label>New Password (optional)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <button class="btn btn-success">
                    Update User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection