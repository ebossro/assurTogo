@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Utilisateurs</h2>
            <p class="text-secondary opacity-75 mb-0">Gestion des comptes et des accès.</p>
        </div>
    </div>

    <!-- Stats Cards: Using ONLY real data passed from controller -->
    <div class="row g-4 mb-5">
        <!-- Total Users -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark display-5">{{ number_format($totalUsers) }}</h2>
                    <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Utilisateurs Totaux</span>
                </div>
            </div>
        </div>
        
        <!-- Active Users -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark display-5">{{ number_format($activeUsers) }}</h2>
                    <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Polices Actives</span>
                </div>
            </div>
        </div>

        <!-- New Users This Month -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                         <div class="bg-info bg-opacity-10 text-info rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-calendar-plus fs-4"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark display-5">{{ number_format($newUsersThisMonth) }}</h2>
                    <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Nouveaux (Ce mois)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-2">
            <form action="{{ route('admin.users') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 py-3 shadow-none text-secondary fw-bold" placeholder="Rechercher par nom, email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-3 ms-auto text-end">
                    @if(request('search'))
                        <a href="{{ route('admin.users') }}" class="btn btn-light text-secondary border-0 rounded-pill px-4 py-2 fw-bold small me-2">
                            <i class="bi bi-x-circle me-2"></i>Effacer
                        </a>
                    @endif
                    <button type="submit" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 400px;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase fw-bold border-0">Utilisateur</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Contact</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Polices</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Inscription</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Statut</th>
                            <th class="text-end pe-4 py-3 text-muted small text-uppercase fw-bold border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-dark fw-bold text-decoration-none d-block">{{ $user->prenom }} {{ $user->name }}</a>
                                        <span class="text-muted small">ID: {{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-medium small mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $user->email }}</span>
                                    @if($user->telephone)
                                        <span class="text-muted small"><i class="bi bi-telephone me-2 text-muted"></i>{{ $user->telephone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($user->polices_count > 0)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 border border-primary border-opacity-10">
                                        {{ $user->polices_count }} Police(s)
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                                        Aucune
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small fw-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td>
                                @if($user->polices_count > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Actif</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Inactif</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('admin.users.show', $user) }}"><i class="bi bi-eye me-2"></i>Détails</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-search text-muted fs-1 opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Aucun utilisateur trouvé.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
