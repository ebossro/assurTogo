<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AssurTogo - Tableau de bord</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
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
            background-color: #f0f7ff;
            color: #0d6efd;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar-logo {
            padding: 24px 20px;
            margin-bottom: 20px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0d6efd;
            display: flex;
            align-items: center;
            gap: 10px;
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
    <aside class="sidebar d-flex flex-column" style="overflow-y: auto;">
        <a href="{{ route('home') }}" class="sidebar-logo text-decoration-none">
            <i class="bi bi-shield-check display-6 text-primary"></i>
            <span>AssurTogo</span>
        </a>

        <div class="px-3 grow">
            <span class="text-xs fw-bold text-muted text-uppercase px-3 mb-2 d-block small"
                style="font-size: 0.75rem;">Menu Principal</span>

            <a href="{{ route('dashboard.index') }}"
                class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Tableau de bord
            </a>
            <a href="{{ route('polices.index') }}"
                class="nav-link {{ request()->routeIs('polices.index') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i> Mes Polices
            </a>
            <a href="{{ route('sinistres.index') }}"
                class="nav-link {{ request()->routeIs('sinistres*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Mes Sinistres
            </a>
            <a href="{{ route('documents.index') }}"
                class="nav-link {{ request()->routeIs('documents*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i> Documents
            </a>
            <a href="{{ route('polices.history') }}"
                class="nav-link {{ request()->routeIs('polices.history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Mes Historiques
            </a>
        </div>

        <div class="px-3 pb-4">
            <hr class="my-4">
            <div class="d-flex align-items-center gap-3 px-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; min-width: 40px;">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h6 class="mb-0 text-truncate fw-bold small">{{ Auth::user()->name }}</h6>
                    <small class="text-muted text-truncate d-block"
                        style="font-size: 0.75rem;">{{ Auth::user()->email }}</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm rounded-3">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Mobile Header (Visible only on small screens) -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('home') }}" class="fw-bold text-decoration-none text-dark fs-5">AssurTogo</a>
            <button class="btn btn-light" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        @yield('content')
    </main>

</body>

</html>