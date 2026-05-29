@extends('layouts.app')

@section('content')
<div class="container min-vh-100 d-flex align-items-center py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header text-center text-white py-4"
                     style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">

                    <h3 class="fw-bold mb-1">Create Account</h3>
                    <p class="mb-0 text-white-50 small">
                        HRIS User Registration
                    </p>

                </div>

                <!-- Body -->
                <div class="card-body p-5">

                    <form method="POST" action="{{ route('register.submit') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control form-control-lg"
                                   value="{{ old('email') }}"
                                   required>
                        </div>

                        <!-- Role Dropdown -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role</label>

                            <select name="role" class="form-select form-select-lg" required>
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>
                                    Employee
                                </option>
                                <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>
                                    HR
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>

                            <small class="text-muted">
                                Choose role carefully (admin access is restricted)
                            </small>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                Create Account
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small">
                                Already have an account? Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection