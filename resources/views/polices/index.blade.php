@extends('layouts.dashboard')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold text-dark mb-2">Mes Polices</h2>
        <p class="text-secondary opacity-75 mb-0">Retrouvez le détails de vos couvertures d'assurance.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('polices.history') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-clock-history me-2"></i>Historique
        </a>
        @if(!$police || $police->statut === 'expire' || $isExpired)
        <a href="{{ route('polices.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi {{ !$police ? 'bi-plus-circle' : 'bi-arrow-repeat' }} me-2"></i>{{ !$police ? 'Souscrire' : 'Renouveler' }}
        </a>
        @endif
    </div>
</div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<!-- Policies List -->
<div class="row">
    <div class="col-12">
        @if($police)
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4">
                    <!-- Card Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-4">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-1">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-dark mb-0">{{ $police->typePolice }}</h4>
                                    <span class="text-secondary small">{{ $police->numeroPolice }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Badge de Statut --}}
                        @if($police->statut === 'actif')
                             @if($isExpiringSoon)
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-10">
                                    <i class="bi bi-clock-fill me-1"></i>Expire dans {{ abs($daysRemaining) }} jour(s)
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-10">Actif</span>
                            @endif
                        @elseif($police->statut === 'en_attente')
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-10">En Attente</span>
                        @elseif($police->statut === 'expire')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-10">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Expiré
                            </span>
                        @elseif($police->statut === 'resilie')
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 border border-secondary border-opacity-10">Résilié</span>
                        @elseif($police->statut === 'suspendu')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-10">Suspendu</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 border border-secondary border-opacity-10">{{ ucfirst($police->statut) }}</span>
                        @endif
                    </div>

                    <!-- Info Grid -->
                    <div class="row g-4 mb-4">
                        <!-- Period -->
                        <div class="col-md-3">
                             <div class="p-3 bg-light rounded-4 h-100">
                                <span class="d-block text-secondary small text-uppercase fw-bold mb-2">Validité</span>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-calendar-check text-success"></i>
                                    <span class="text-dark fw-medium">{{ $police->dateDebut ? $police->dateDebut->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-x text-danger"></i>
                                    <span class="text-dark fw-medium">{{ $police->dateFin ? $police->dateFin->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Premium -->
                        <div class="col-md-3">
                             <div class="p-3 bg-light rounded-4 h-100">
                                <span class="d-block text-secondary small text-uppercase fw-bold mb-2">Cotisation</span>
                                <span class="d-block text-dark fw-bold fs-5 mb-1">
                                    {{ number_format($police->primeMensuelle, 0, ',', ' ') }} <small class="fs-6 text-muted">FCFA</small>
                                </span>
                                <span class="text-muted small">Paiement mensuel</span>
                            </div>
                        </div>

                         <!-- Beneficiaries -->
                        <div class="col-md-3">
                             <div class="p-3 bg-light rounded-4 h-100">
                                <span class="d-block text-secondary small text-uppercase fw-bold mb-2">Bénéficiaires</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-dark fw-bold fs-5">{{ $police->beneficiaires->count() }}</span>
                                    <span class="text-muted small">Personne(s)</span>
                                </div>
                                 <a href="{{ route('polices.show', $police) }}" class="text-primary small text-decoration-none fw-bold mt-2 d-inline-block">
                                    Voir la liste <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Coverage -->
                        <div class="col-md-3">
                             <div class="p-3 bg-light rounded-4 h-100">
                                <span class="d-block text-secondary small text-uppercase fw-bold mb-2">Couverture</span>
                                <span class="d-block text-dark fw-medium small">
                                    {{ $police->couverture ?? 'Standard' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end align-items-center gap-3 border-top pt-4">
                        @if($police->statut === 'expire' || $isExpired)
                             <a href="{{ route('polices.create') }}" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm">
                                <i class="bi bi-arrow-repeat me-2"></i>Renouveler ma Police
                            </a>
                        @endif

                        @if($police->statut === 'actif' && !$isExpired)
                         <a href="{{ route('polices.download_attestation', $police) }}" class="btn btn-light border fw-bold px-4 rounded-pill">
                            <i class="bi bi-download me-2"></i>Attestation
                        </a>
                        @endif
                         <a href="{{ route('polices.show', $police) }}" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                            Détails du Contrat
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-plus text-primary fs-1 opacity-50"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Aucune police active</h4>
                    <p class="text-muted mb-4 col-md-6 mx-auto">Vous n'avez pas encore souscrit d'assurance. Commencez dès maintenant pour vous protéger vous et vos proches.</p>
                    <a href="{{ route('polices.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm scale-hover">
                        Découvrir nos Offres
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .scale-hover:hover { transform: scale(1.02); transition: transform 0.2s; }
</style>
@endsection
