<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssurTogo - Votre assurance santé simplifiée</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007acc;
            --primary-hover: #0062a3;
            --bg-light-blue: #f0f9ff;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a;
            font-weight: 700;
        }
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-outline-secondary {
            color: #475569;
            border-color: #e2e8f0;
            background-color: white;
        }
        .btn-outline-secondary:hover {
            background-color: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .navbar {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
            color: #64748b;
            margin-left: 1rem;
            margin-right: 1rem;
        }
        .nav-link:hover {
            color: var(--primary-color);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
        }
        
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding-top: 4rem;
            padding-bottom: 2rem;
        }
        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top border-bottom">
        <div class="container">
            <a class="navbar-brand" href="/">Assur<span class="text-primary">Togo</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/#fonctionnalites">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#avantages">Avantages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="d-none d-md-block">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                                @if(Auth::user()->role && Auth::user()->role->typeRole === 'assure')
                                    <li><a class="dropdown-item px-3 py-2" href="{{ route('dashboard.index') }}"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @elseif(Auth::user()->role && Auth::user()->role->typeRole === 'admin')
                                    <li><a class="dropdown-item px-3 py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif  
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item px-3 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary shadow-sm rounded-3 me-2">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-primary shadow-sm rounded-3">Commencer</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main style="padding-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    @yield('footer')
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
