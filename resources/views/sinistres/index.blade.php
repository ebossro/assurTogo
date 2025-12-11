@extends('layouts.dashboard')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold text-dark mb-2">Mes sinistres</h2>
        <p class="text-muted mb-0">Suivez l'état de vos demandes de remboursement</p>
    </div>
    
    <div>
        <a href="{{ route('sinistres.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-medium">
            <i class="bi bi-plus-lg me-2"></i>Déclarer un sinistre
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Total remboursé</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalRembourse, 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-2">En attente</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalEnAttente, 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Sinistres traités</p>
                <h3 class="fw-bold text-dark mb-0">{{ $nombreTraites }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Claims History -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">Historique des sinistres</h5>
        <p class="text-muted small mb-4">Tous vos sinistres déclarés</p>

        <div class="d-flex flex-column gap-3">
            @forelse($sinistres as $sinistre)
                <div class="border rounded-3 p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 hover-shadow transition-all">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-light rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h6 class="fw-bold text-dark mb-0">{{ $sinistre->description }}</h6> <!-- Assumed description is the title or use a type field -->
                                @php
                                    $statusClass = match($sinistre->statut) {
                                        'valide' => 'success',
                                        'en_cours' => 'warning',
                                        'rejete' => 'danger',
                                        default => 'secondary'
                                    };
                                    $statusLabel = match($sinistre->statut) {
                                        'valide' => 'Validé',
                                        'en_cours' => 'En cours',
                                        'rejete' => 'Rejeté',
                                        default => ucfirst($sinistre->statut)
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $statusLabel }}</span>
                            </div>
                            <div class="text-muted small d-flex flex-wrap gap-3 align-items-center">
                                <span><i class="bi bi-calendar me-1"></i> {{ $sinistre->date_sinistre->translatedFormat('d M Y') }}</span>
                                <span>•</span>
                                <span class="fw-medium text-dark">{{ number_format($sinistre->montant_estime, 0, ',', ' ') }} FCFA</span>
                                @if($sinistre->police && $sinistre->police->typePolice)
                                    <span>•</span>
                                    <span>{{ $sinistre->police->typePolice }}</span>
                                @endif
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock me-1"></i> Soumis le {{ $sinistre->created_at->translatedFormat('d M Y') }} 
                                @if($sinistre->statut == 'valide' || $sinistre->statut == 'rejete')
                                    • Traité le {{ $sinistre->updated_at->translatedFormat('d M Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <a href="{{ route('sinistres.show', $sinistre) }}" class="btn btn-light border fw-medium small text-nowrap">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-inbox text-muted fs-3"></i>
                    </div>
                    <h6>Aucun sinistre trouvé</h6>
                    <p class="text-muted small">Vos demandes de remboursement apparaîtront ici.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important; border-color: transparent!important; background-color: #fff; }
</style>
@endsection
