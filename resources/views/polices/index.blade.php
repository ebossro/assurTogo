@extends('layouts.dashboard')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold text-dark mb-2">Mes polices d'assurance</h2>
        <p class="text-muted mb-0">Gérez toutes vos polices d'assurance santé</p>
    </div>
    
    <div>
        <a href="{{ route('polices.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-medium">
            <i class="bi bi-shield-plus me-2"></i>Nouvelle souscription
        </a>
    </div>
</div>

<!-- Policies List -->
<div class="d-flex flex-column gap-4">
    @forelse($polices as $police)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <!-- Card Header -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">{{ $police->typePolice }}</h4>
                        <span class="text-muted small">AssurTogo Partenaire</span>
                    </div>
                    @php
                        $daysRemaining = \Carbon\Carbon::now()->diffInDays($police->dateFin, false);
                        $isExpiring = $daysRemaining > 0 && $daysRemaining <= 30;
                        $isExpired = $daysRemaining <= 0;
                    @endphp

                    @if($isExpired)
                         <span class="badge bg-danger rounded-pill px-3 py-2">Expiré</span>
                    @elseif($isExpiring)
                        <span class="badge bg-danger rounded-pill px-3 py-2">Expire bientôt</span>
                    @else
                        <span class="badge bg-primary rounded-pill px-3 py-2">Actif</span>
                    @endif
                </div>

                <!-- Info Grid -->
                <div class="row g-4 mb-4">
                    <!-- Period -->
                    <div class="col-md-3">
                        <div class="d-flex gap-3">
                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center text-primary" style="width: 48px; height: 48px;">
                                <i class="bi bi-calendar-event fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted small mb-1">Période</span>
                                <span class="d-block text-dark fw-medium small">
                                    {{ $police->dateDebut->translatedFormat('d M Y') }} - 
                                </span>
                                <span class="d-block text-dark fw-medium small">
                                    {{ $police->dateFin->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Premium -->
                    <div class="col-md-3">
                         <div class="d-flex gap-3">
                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center text-primary" style="width: 48px; height: 48px;">
                                <i class="bi bi-currency-dollar fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted small mb-1">Prime mensuelle</span>
                                <span class="d-block text-dark fw-bold">
                                    {{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    </div>

                     <!-- Beneficiaries -->
                    <div class="col-md-3">
                         <div class="d-flex gap-3">
                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center text-primary" style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted small mb-1">Bénéficiaires</span>
                                <span class="d-block text-dark fw-medium">
                                    {{ $police->beneficiaires->count() }} personne(s)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage -->
                    <div class="col-md-3">
                         <div class="d-flex gap-3">
                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center text-primary" style="width: 48px; height: 48px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted small mb-1">Couverture</span>
                                <span class="d-block text-dark fw-medium small">
                                    {{ Str::limit($police->couverture, 40) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2">
                         <a href="{{ route('polices.show', $police) }}" class="btn btn-light border fw-medium small px-3 py-2">
                            <i class="bi bi-file-text me-2"></i>Voir les détails
                        </a>
                        <a href="#" class="btn btn-light border fw-medium small px-3 py-2">
                            <i class="bi bi-download me-2"></i>Télécharger l'attestation
                        </a>
                    </div>

                    @if($isExpiring || $isExpired)
                        <button class="btn btn-primary fw-medium px-4 py-2">
                            Renouveler maintenant
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-shield-x text-muted fs-1"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Aucune police active</h4>
            <p class="text-muted mb-4">Vous n'avez pas encore souscrit à une police d'assurance.</p>
            <a href="{{ route('polices.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-medium">
                Découvrir nos offres
            </a>
        </div>
    @endforelse
</div>
@endsection
