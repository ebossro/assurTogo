@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <h2 class="fw-bold text-dark mb-1">Gestion des sinistres</h2>
    <p class="text-muted">Traitez et gérez toutes les demandes de remboursement</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">En attente d'action</p>
                        <h3 class="fw-bold mb-0">{{ $pendingCount }}</h3>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingCount }}%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Approuvés ce mois</p>
                        <h3 class="fw-bold mb-0">{{ $approvedCount }}</h3>
                    </div>
                </div>
                <div class="text-success small fw-medium">
                    <i class="bi bi-graph-up-arrow me-1"></i> Performance mensuelle
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Montant validé (Mois)</p>
                        <h3 class="fw-bold mb-0">{{ number_format($totalAmount, 0, ',', ' ') }} <small class="text-muted fs-6 fw-normal">FCFA</small></h3>
                    </div>
                </div>
                <div class="text-muted small">
                    Cumul des remboursements approuvés
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs & Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-header bg-white border-bottom p-0">
        <div class="d-flex overflow-auto">
            <a href="{{ route('admin.sinistres', ['search' => request('search')]) }}" 
               class="text-decoration-none px-4 py-3 fw-medium border-bottom border-2 {{ !request('statut') ? 'border-primary text-primary bg-primary bg-opacity-10' : 'border-transparent text-muted hover-bg-light' }}">
                Tous les sinistres
            </a>
            <a href="{{ route('admin.sinistres', ['statut' => 'en_attente', 'search' => request('search')]) }}" 
               class="text-decoration-none px-4 py-3 fw-medium border-bottom border-2 {{ request('statut') == 'en_attente' ? 'border-warning text-warning bg-warning bg-opacity-10' : 'border-transparent text-muted hover-bg-light' }}">
                En attente 
                <span class="badge rounded-pill bg-warning text-dark ms-2">{{ $countEnAttente }}</span>
            </a>
            <a href="{{ route('admin.sinistres', ['statut' => 'en_analyse', 'search' => request('search')]) }}" 
               class="text-decoration-none px-4 py-3 fw-medium border-bottom border-2 {{ request('statut') == 'en_analyse' ? 'border-info text-info bg-info bg-opacity-10' : 'border-transparent text-muted hover-bg-light' }}">
                En analyse
                <span class="badge rounded-pill bg-info text-dark ms-2">{{ $countEnAnalyse }}</span>
            </a>
            <a href="{{ route('admin.sinistres', ['statut' => 'approuve', 'search' => request('search')]) }}" 
               class="text-decoration-none px-4 py-3 fw-medium border-bottom border-2 {{ request('statut') == 'approuve' ? 'border-success text-success bg-success bg-opacity-10' : 'border-transparent text-muted hover-bg-light' }}">
                Approuvés
                <span class="badge rounded-pill bg-success ms-2">{{ $countApprouve }}</span>
            </a>
            <a href="{{ route('admin.sinistres', ['statut' => 'rejete', 'search' => request('search')]) }}" 
               class="text-decoration-none px-4 py-3 fw-medium border-bottom border-2 {{ request('statut') == 'rejete' ? 'border-danger text-danger bg-danger bg-opacity-10' : 'border-transparent text-muted hover-bg-light' }}">
                Rejetés
                <span class="badge rounded-pill bg-danger ms-2">{{ $countRejete }}</span>
            </a>
        </div>
    </div>
    
    <!-- Search Bar (Inside Card) -->
    <div class="card-body p-3 bg-light border-bottom">
        <form action="{{ route('admin.sinistres') }}" method="GET" class="row align-items-center g-2">
            @if(request('statut'))
                <input type="hidden" name="statut" value="{{ request('statut') }}">
            @endif
            <div class="col-md-6">
                <div class="input-group bg-white rounded-3 border shadow-sm">
                    <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0" name="search" placeholder="Rechercher par référence, police, nom..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3"></div> <!-- Spacer -->
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-dark w-100 shadow-sm"><i class="bi bi-filter me-2"></i>Filtrer</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Sinistre</th>
                    <th class="text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Assuré</th>
                    <th class="text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Type & Détails</th>
                    <th class="text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Montant</th>
                    <th class="text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Date</th>
                    <th class="text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Statut</th>
                    <th class="text-end pe-4 text-muted small py-3 border-0 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sinistres as $sinistre)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $sinistre->reference }}</div>
                        <div class="small text-muted">{{ $sinistre->police->numeroPolice ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                {{ substr($sinistre->police->user->prenom ?? 'U', 0, 1) }}{{ substr($sinistre->police->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $sinistre->police->user->prenom ?? '' }} {{ $sinistre->police->user->name ?? 'Inconnu' }}</div>
                                <div class="small text-muted">{{ $sinistre->police->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border mb-1">{{ ucfirst($sinistre->type_sinistre) }}</span>
                        <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $sinistre->description }}">
                            {{ Str::limit($sinistre->description, 30) }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ number_format($sinistre->montant_total, 0, ',', ' ') }} CFA</div>
                    </td>
                    <td>
                        <div class="text-dark">{{ $sinistre->date_sinistre ? $sinistre->date_sinistre->format('d/m/Y') : '-' }}</div>
                        <div class="small text-muted">{{ $sinistre->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        @switch($sinistre->statut)
                            @case('en_attente')
                                <span class="badge bg-warning text-dark bg-opacity-25 border border-warning px-3 py-2 rounded-pill">
                                    <i class="bi bi-clock me-1"></i> En attente
                                </span>
                                @break
                            @case('en_analyse')
                                <span class="badge bg-info text-dark bg-opacity-25 border border-info px-3 py-2 rounded-pill">
                                    <i class="bi bi-search me-1"></i> En analyse
                                </span>
                                @break
                            @case('approuve')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Approuvé
                                </span>
                                @break
                            @case('rejete')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">
                                    <i class="bi bi-x-circle me-1"></i> Rejeté
                                </span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $sinistre->statut }}</span>
                        @endswitch
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sinistres.show', $sinistre) }}" class="btn btn-light btn-sm border text-muted hover-primary" data-bs-toggle="tooltip" title="Voir les détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            {{-- Logic for EN ATTENTE --}}
                            @if($sinistre->statut == 'en_attente')
                                <button type="button" class="btn btn-info btn-sm bg-info bg-opacity-10 text-info border-0 hover-info-solid" 
                                        onclick="if(confirm('Passer ce sinistre en analyse ?')) document.getElementById('analyze-form-{{ $sinistre->id }}').submit();"
                                        title="Analyser">
                                    <i class="bi bi-search"></i>
                                </button>
                                <form id="analyze-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.analyze', $sinistre) }}" method="POST" class="d-none">@csrf</form>

                                <button type="button" class="btn btn-danger btn-sm bg-danger bg-opacity-10 text-danger border-0 hover-danger-solid" 
                                        onclick="if(confirm('Rejeter ce sinistre ?')) document.getElementById('reject-form-{{ $sinistre->id }}').submit();"
                                        title="Rejeter">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <form id="reject-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.reject', $sinistre) }}" method="POST" class="d-none">@csrf</form>
                            @endif

                            {{-- Logic for EN ANALYSE --}}
                            @if($sinistre->statut == 'en_analyse')
                                <button type="button" class="btn btn-success btn-sm bg-success bg-opacity-10 text-success border-0 hover-success-solid" 
                                        onclick="if(confirm('Approuver ce sinistre ?')) document.getElementById('approve-form-{{ $sinistre->id }}').submit();"
                                        title="Approuver">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <form id="approve-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.approve', $sinistre) }}" method="POST" class="d-none">@csrf</form>

                                <button type="button" class="btn btn-danger btn-sm bg-danger bg-opacity-10 text-danger border-0 hover-danger-solid" 
                                        onclick="if(confirm('Rejeter ce sinistre ?')) document.getElementById('reject-form-{{ $sinistre->id }}').submit();"
                                        title="Rejeter">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <form id="reject-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.reject', $sinistre) }}" method="POST" class="d-none">@csrf</form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light rounded-circle p-4 mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted fw-normal">Aucun sinistre trouvé</h5>
                            <p class="text-muted small mb-0">Essayez de modifier vos filtres</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($sinistres->hasPages())
    <div class="card-footer bg-white border-0 py-4 d-flex justify-content-end">
        {{ $sinistres->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
