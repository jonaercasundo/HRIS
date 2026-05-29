@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center py-5">
     <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <!-- Card Container -->
            <div class="card shadow-xl border-0 rounded-4 overflow-hidden">
                <!-- Modern Header -->
                <div class="card-header border-0 text-center text-white py-5 position-relative overflow-hidden"
                    style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <!-- Background Circle Decoration -->
                    <div style="
                        position: absolute;
                        width: 180px;
                        height: 180px;
                        background: rgba(255,255,255,0.08);
                        border-radius: 50%;
                        top: -60px;
                        right: -60px;
                    "></div>

                    <div style="
                        position: absolute;
                        width: 120px;
                        height: 120px;
                        background: rgba(255,255,255,0.05);
                        border-radius: 50%;
                        bottom: -40px;
                        left: -40px;
                    "></div>

                    <!-- Content -->
                    <div class="position-relative">
                        <!-- Logo/Icon -->
                        <div class="mb-3">
                            <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-people-fill fs-2 text-white"></i>
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="fw-bold mb-1">
                            HRIS Portal
                        </h3>

                        <!-- Subtitle -->
                        <p class="mb-0 text-white-50 small">
                            Human Resource Information System
                        </p>

                    </div>
                </div>
                
                <div class="card-body p-5">
                    <!-- Success Status -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 py-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <!-- Welcome Text -->
                        <div class="text-center mb-4">
                            <h5 class="fw-bold text-dark mb-1">
                                Welcome Back
                            </h5>

                            <p class="text-muted small mb-0">
                                Login to continue to your dashboard
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">

                            <label for="email"
                                class="form-label fw-semibold text-dark mb-2">
                                Email Address
                            </label>

                            <div class="input-group input-group-lg shadow-sm">

                                <span class="input-group-text bg-white border-end-0 px-3">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                </span>

                                <input type="email"
                                    id="email"
                                    name="email"
                                    class="form-control border-start-0 ps-0 py-3 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="name@company.com"
                                    required
                                    autofocus>

                            </div>

                            @error('email')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Password -->
                        <div class="mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <label for="password"
                                    class="form-label fw-semibold text-dark mb-0">
                                    Password
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                    class="small text-decoration-none fw-semibold">
                                        Forgot Password?
                                    </a>
                                @endif

                            </div>

                            <div class="input-group input-group-lg shadow-sm">

                                <span class="input-group-text bg-white border-end-0 px-3">
                                    <i class="bi bi-lock-fill text-primary"></i>
                                </span>

                                <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control border-start-0 border-end-0 ps-0 py-3 @error('password') is-invalid @enderror"
                                    placeholder="Enter your password"
                                    required>

                                <button class="btn bg-white border border-start-0 px-3"
                                        type="button"
                                        id="togglePassword">

                                    <i class="bi bi-eye-fill text-secondary"
                                    id="toggleIcon"></i>

                                </button>

                            </div>

                            @error('password')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Remember -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label text-muted"
                                    for="remember">

                                    Keep me signed in

                                </label>

                            </div>

                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-4">

                            <button type="submit"
                                    class="btn btn-primary btn-lg fw-bold shadow-sm py-3 rounded-3">

                                Sign In
                                <i class="bi bi-arrow-right-circle-fill ms-2"></i>

                            </button>

                        </div>

                        <!-- Divider -->
                        <div class="position-relative text-center mb-4">

                            <hr>

                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                OR
                            </span>

                        </div>

                        <!-- Register -->
                        <div class="d-grid">

                            <a href="{{ route('register') }}"
                            class="btn btn-outline-dark btn-lg fw-semibold py-3 rounded-3">

                                Create New Account
                                <i class="bi bi-person-plus-fill ms-2"></i>

                            </a>

                        </div>

                    </form>

                </div>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-muted small mt-4">
                &copy; {{ date('Y') }} Human Resource Information System.<br>
                <span class="text-xs text-black-50">Authorized access only.</span>
            </p>

        </div>
    </div>
</div>

<!-- Optional micro-interactions and password toggle script -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>

<style>
    /* Clean UI Polish Overrides */
    .cursor-pointer { cursor: pointer; }
    .unselectable { user-select: none; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .transition-all:hover { transform: translateY(-1px); }
    .input-group:focus-within .input-group-text, 
    .input-group:focus-within .btn {
        border-color: #86b7fe !important; /* Matches Bootstrap focus ring color */
    }
</style>
@endsection