@extends('layouts.dashboard')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold text-dark mb-2">Tableau de bord</h2>
        <p class="text-muted mb-0">Bienvenue sur votre espace personnel AssurTogo</p>
    </div>
    
    <div class="d-flex gap-3 align-items-center">
        <button class="btn btn-light position-relative p-2 rounded-circle">
            <i class="bi bi-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <!-- Active Policies -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <span class="text-muted small">Polices actives</span>
                    <i class="bi bi-shield-check text-muted"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">{{ $activePolicesCount }}</h2>
                    <span class="text-muted small">Assurance santé et vie</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Renewal -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <span class="text-muted small">Prochaine échéance</span>
                    <i class="bi bi-calendar-event text-muted"></i>
                </div>
                <div>
                    @if($nextRenewalPolicy)
                        <h2 class="fw-bold mb-1">{{ abs((int)$daysUntilRenewal) }} jours</h2>
                        <span class="text-muted small">Renouvellement le {{ $nextRenewalPolicy->dateFin->translatedFormat('d F Y') }}</span>
                    @else
                        <h4 class="fw-bold mb-1">-</h4>
                        <span class="text-muted small">Aucune échéance</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Premium -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <span class="text-muted small">Cotisation mensuelle</span>
                    <span class="text-muted small">FCFA</span>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">{{ number_format($totalMonthlyPremium, 0, ',', ' ') }}</h2>
                    <span class="text-muted small">Paiement automatique</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Claims -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Sinistres récents</h5>
                <a href="#" class="text-decoration-none text-muted small hover-primary">Voir tout <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body p-4 pt-0">
                @forelse($recentClaims as $claim)
                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold text-dark">{{ $claim->libelle ?? 'Consultation médicale' }}</span>
                                <span class="badge bg-{{ $claim->statut == 'paye' ? 'primary' : ($claim->statut == 'en_cours' ? 'success' : 'secondary') }} rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                                    {{ ucfirst($claim->statut) }}
                                </span>
                            </div>
                            <small class="text-muted">CLM-{{ $claim->id }} • {{ $claim->created_at->translatedFormat('d F Y') }}</small>
                        </div>
                        <div class="fw-bold text-dark">
                            {{ number_format($claim->montant ?? 0, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-4 mb-2 d-block"></i>
                        Aucun sinistre récent
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Actions rapides</h5>
                
                <div class="d-flex flex-column gap-3">
                    <button class="btn btn-primary w-100 py-2 fw-medium mb-1">
                        <i class="bi bi-plus text-lg me-2"></i> Déclarer un sinistre
                    </button>
                    <button class="btn btn-light border w-100 py-2 text-start text-muted">
                        <i class="bi bi-download me-2"></i> Télécharger attestation
                    </button>
                    <button class="btn btn-light border w-100 py-2 text-start text-muted">
                        <i class="bi bi-file-earmark-text me-2"></i> Voir mes documents
                    </button>
                    <button class="btn btn-light border w-100 py-2 text-start text-muted">
                        <i class="bi bi-headset me-2"></i> Contacter le support
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Renewals -->
@if($nextRenewalPolicy)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 alert-info bg-opacity-10" style="background-color: #f0f9ff;">
             <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3">Renouvellements à venir</h6>
                
                <div class="bg-white rounded-4 p-4 border border-light shadow-sm">
                    <div class="d-flex gap-3 align-items-start">
                        <i class="bi bi-exclamation-circle text-primary fs-4"></i>
                        <div class="grow">
                            <h6 class="fw-bold text-dark mb-1">{{ $nextRenewalPolicy->typePolice ?? 'Assurance Santé' }}</h6>
                            <p class="text-muted small mb-2">Renouvellement le {{ $nextRenewalPolicy->dateFin->translatedFormat('d F Y') }} ({{ abs((int)$daysUntilRenewal) }} jours restants)</p>
                            <p class="text-dark small fw-medium mb-3">Cotisation: {{ number_format($nextRenewalPolicy->primeMensuelle, 0, ',', ' ') }} FCFA/mois</p>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm px-3">Renouveler maintenant</button>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endif

<style>
    .hover-primary:hover { color: var(--bs-primary) !important; }
</style>
@endsection
