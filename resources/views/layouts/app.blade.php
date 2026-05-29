<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Metro Mobilia HRIS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #f4f7fb;
        }

        .navbar {
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .nav-link {
            transition: 0.2s ease;
        }

        .nav-link:hover {
            transform: translateY(-1px);
        }

        .dropdown-menu {
            border-radius: 12px;
        }

        main {
            padding: 20px;
        }
    </style>
</head>

<body>

<div id="app" class="d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top border-bottom">

        <div class="container">

            <!-- BRAND -->
            <a class="navbar-brand text-primary d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-buildings-fill fs-5"></i>
                <span>Metro Mobilia HRIS</span>
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto">
                    <!-- future links -->
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">

                    @auth
                        <!-- USER MENU -->
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 fw-semibold"
                               href="#"
                               data-bs-toggle="dropdown">

                                <i class="bi bi-person-circle fs-5 text-primary"></i>
                                {{ Auth::user()->name }}

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">

                                <li class="px-3 py-2 text-muted small">
                                    Signed in as<br>
                                    <strong>{{ Auth::user()->email }}</strong>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </li>
                    @else
                        <!-- LOGIN -->
                        <li class="nav-item">
                            <a class="btn btn-outline-primary btn-sm px-3"
                               href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Login
                            </a>
                        </li>
                    @endauth

                </ul>

            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>