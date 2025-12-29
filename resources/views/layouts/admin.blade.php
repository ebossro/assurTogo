<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AssurConnect - Admin</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .grow {
            flex-grow: 1 !important;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .nav-link {
            color: #6c757d;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #6d28d9;
            color: #eef2ff;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar-logo {
            padding: 24px 20px;
            margin-bottom: 20px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-company {
            background-color: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            vertical-align: middle;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <!-- scroll vertical -->
    <aside class="sidebar d-flex flex-column" style="overflow-y: auto;">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo text-decoration-none">
            <i class="bi bi-shield-check display-6 text-primary"></i>
            <span>AssurTogo <span class="badge-company">Compagnie</span></span>
        </a>

        <div class="px-3 grow">
            <span class="text-xs fw-bold text-muted text-uppercase px-3 mb-2 d-block small"
                style="font-size: 0.75rem;">Menu Principal</span>

            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Tableau de bord
            </a>
            <a href="{{ route('admin.polices') }}"
                class="nav-link {{ request()->routeIs('admin.polices*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Polices
            </a>
            <a href="{{ route('admin.sinistres') }}"
                class="nav-link {{ request()->routeIs('admin.sinistres*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Sinistres
            </a>
            <a href="{{ route('admin.users') }}"
                class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Utilisateurs
            </a>
            <a href="{{ route('admin.history') }}"
                class="nav-link {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Historique
            </a>
            <a href="{{ route('admin.analytics') }}"
                class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Analytiques
            </a>

        </div>

        <div class="px-3 pb-4">
            <hr class="my-4">
            <div class="d-flex align-items-center gap-3 px-3">
                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; min-width: 40px;">
                    {{ Auth::user()->prenom[0] }}{{ Auth::user()->name[0] }}
                </div>
                <div class="overflow-hidden">
                    <h6 class="mb-0 text-truncate fw-bold small">{{ Auth::user()->prenom }} {{ Auth::user()->name }}
                    </h6>
                    <small class="text-muted text-truncate d-block"
                        style="font-size: 0.75rem;">{{ Auth::user()->email }}</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-light w-100 btn-sm rounded-3 text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Mobile Header -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
            <span class="fw-bold fs-5">AssurConnect Admin</span>
            <button class="btn btn-light" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <!-- Top Bar Mockup (Search, Notifs) -->
        @if (request()->routeIs('admin.dashboard'))
            <div class="d-none d-lg-flex justify-content-end mb-4 gap-3">
                <button class="btn btn-light rounded-circle"><i class="bi bi-bell"></i></button>
                <button class="btn btn-light rounded-circle"><i class="bi bi-gear"></i></button>
            </div>
        @else
            <div class="d-none d-lg-flex justify-content-end mb-4 gap-3" style="margin-top: 40px;"></div>
        @endif
        @yield('content')
    </main>

    @yield('scripts')
</body>

</html>