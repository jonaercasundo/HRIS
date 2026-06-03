<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Metro Mobilia HRIS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --app-bg: #f8fafc;
            --navbar-bg: rgba(255, 255, 255, 0.85);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--app-bg);
            color: var(--text-primary);
            letter-spacing: -0.01em;
            overflow-x: hidden;
        }

        /* Fixed Left-Sided Nav Anchor Block */
        .app-sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1040;
            border-right: 1px solid rgba(241, 245, 249, 1);
        }



        /* Ultra-Modern Blurred Sticky Header Navbar */
        .navbar-modern {
            background-color: var(--navbar-bg);
            backdrop-filter: blur(12px) saturate(190%);
            -webkit-backdrop-filter: blur(12px) saturate(190%);
            border-bottom: 1px solid rgba(241, 245, 249, 0.8);
            z-index: 1030;
        }

        .navbar-brand-modern {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Clean User Utilities Dropdowns */
        .user-dropdown-toggle {
            padding: 8px 14px;
            border-radius: 12px;
            color: var(--text-primary);
            transition: var(--transition-smooth);
        }

        .user-dropdown-toggle:hover, .user-dropdown-toggle:focus {
            background-color: #f1f5f9;
            color: #4f46e5;
        }

        .dropdown-menu-modern {
            border: 1px solid rgba(241, 245, 249, 0.9);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
            padding: 8px;
            min-width: 230px;
        }

        .dropdown-item-modern {
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
            transition: var(--transition-smooth);
        }

        .dropdown-item-modern:hover {
            background-color: #fcfbfe;
            color: #df2222;
        }

        /* Responsiveness view handling constraints */
        @media (max-width: 991.98px) {
            .app-sidebar-fixed {
                transform: translateX(-100%);
                transition: var(--transition-smooth);
            }
            .app-sidebar-fixed.show-mobile {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <div class="d-flex w-100 h-100 align-items-stretch">

        <div class="app-workspace-main flex-grow-1">

            <nav class="navbar navbar-expand-lg navbar-modern sticky-top py-3">
                <div class="container-fluid px-4">

                    <button class="btn btn-light d-lg-none me-2 p-2 rounded-3 border-0" type="button" id="sidebarToggler">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <a class="navbar-brand-modern d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <i class="bi bi-buildings-fill text-indigo fs-5" style="color: #4f46e5;"></i>
                        <span>Metro Mobilia HRIS</span>
                    </a>

                    <button class="navbar-toggler border-0 p-2 bg-light rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                        <span class="navbar-toggler-icon" style="width: 20px; height: 20px;"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNavbar">
                        <ul class="navbar-nav ms-auto align-items-center mt-3 mt-lg-0">

                            @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link user-dropdown-toggle dropdown-toggle d-flex align-items-center gap-2 fw-semibold border-0"
                                   href="#"
                                   id="navbarDropdownUser"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <div class="bg-indigo-subtle text-indigo rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; background: #e0e7ff; color: #4f46e5; font-size: 0.8rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                    <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern border-0 mt-2 animate__animated animate__fadeInFast" aria-labelledby="navbarDropdownUser">
                                    <li class="px-3 py-2.5">
                                        <span class="text-uppercase tracking-wider text-muted fw-bold" style="font-size: 0.65rem;">Authenticated Node</span>
                                        <div class="text-dark fw-bold text-truncate" style="max-width: 190px;">{{ Auth::user()->name }}</div>
                                        <div class="text-muted text-truncate font-monospace small" style="max-width: 190px; font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider my-2" style="border-color: #f1f5f9;"></li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item dropdown-item-modern d-flex align-items-center gap-2">
                                                <i class="bi bi-box-arrow-right fs-5 text-danger"></i>
                                                <span class="text-danger">Sign Out Session</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            @endauth

                        </ul>
                    </div>

                </div>
            </nav>

            <main class="flex-grow-1 p-4 p-md-5">
                @yield('content')
            </main>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggler')?.addEventListener('click', function () {
            const sidebar = document.querySelector('.app-sidebar-fixed');
            sidebar?.classList.toggle('show-mobile');
        });
    </script>
</body>
</html>