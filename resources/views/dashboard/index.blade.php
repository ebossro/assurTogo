@extends('layouts.dashboard')

@section('content')
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Tableau de Bord</h2>
            <p class="text-secondary opacity-75 mb-0">Bienvenue, {{ $user->prenom }} {{ $user->name }}</p>
        </div>
        <div>
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill fw-normal">
                <i class="bi bi-calendar-event me-2 text-primary"></i> {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Active Policies -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill d-flex align-items-center border border-primary border-opacity-10">
                            {{ $activePolicesCount }} Actif(s)
                        </span>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark display-5">{{ $police ? 1 : 0 }}</h2>
                    <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Mes Contrats</span>
                </div>
            </div>
        </div>
        
        <!-- Monthly Premium -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill d-flex align-items-center border border-success border-opacity-10">
                            Mensuel
                        </span>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark display-5">{{ number_format($totalMonthlyPremium, 0, ',', ' ') }} <small class="fs-6 text-muted">FCFA</small></h2>
                    <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Cotisation Totale</span>
                </div>
            </div>
        </div>

        <!-- Next Renewal -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between mb-4">
                         <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                    @if($nextRenewalPolicy)
                        <h2 class="fw-bold mb-1 text-dark display-5">{{ abs($daysUntilRenewal) }} <small class="fs-6 text-muted">Jours</small></h2>
                        <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Durée du Contrat</span>
                        <div class="mt-2 text-muted small">
                            Fin le {{ $nextRenewalPolicy->dateFin->translatedFormat('d F Y') }}
                        </div>
                    @else
                         <h2 class="fw-bold mb-1 text-dark display-5">-</h2>
                        <span class="text-secondary fw-medium small text-uppercase letter-spacing-1">Aucune Échéance</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Claims -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <div>
                         <h5 class="fw-bold mb-0">Sinistres Récents</h5>
                         <p class="text-muted small mb-0">Historique de vos dernières déclarations</p>
                    </div>
                    <a href="{{ route('sinistres.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
                        Tout Voir
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small text-uppercase fw-bold border-0">Référence</th>
                                    <th class="py-3 text-muted small text-uppercase fw-bold border-0">Type</th>
                                    <th class="py-3 text-muted small text-uppercase fw-bold border-0">Date</th>
                                    <th class="py-3 text-muted small text-uppercase fw-bold border-0">Statut</th>
                                    <th class="pe-4 text-end py-3 text-muted small text-uppercase fw-bold border-0">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentClaims as $claim)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <a href="{{ route('sinistres.show', $claim) }}" class="text-decoration-none text-dark">
                                            {{ $claim->reference ?? 'SIN-' . $claim->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $claim->type_sinistre ?? 'Non spécifié' }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $claim->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($claim->statut === 'approuve')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Approuvé</span>
                                        @elseif($claim->statut === 'en_attente')
                                             <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">En attente</span>
                                        @elseif($claim->statut === 'rejete')
                                             <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Rejeté</span>
                                        @else
                                             <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ ucfirst($claim->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        {{ number_format($claim->montant_total, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-clipboard-check text-muted fs-1 opacity-25 d-block mb-3"></i>
                                        <span class="text-muted">Aucun sinistre récent.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Actions Rapides</h5>
                    
                    <div class="d-grid gap-3">
                        <a href="{{ route('sinistres.create') }}" class="btn btn-primary py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle me-2 fs-5"></i> Déclarer un Sinistre
                        </a>
                         @if($police)
                            <a href="{{ route('polices.show', $police) }}" class="btn btn-light py-3 rounded-3 fw-bold text-dark border d-flex align-items-center justify-content-between px-4">
                                <span><i class="bi bi-file-earmark-text me-2 text-primary"></i> Ma Police</span>
                                <i class="bi bi-chevron-right small text-muted"></i>
                            </a>
                        @endif
                        <a href="{{ route('documents.index') }}" class="btn btn-light py-3 rounded-3 fw-bold text-dark border d-flex align-items-center justify-content-between px-4">
                            <span><i class="bi bi-folder2-open me-2 text-warning"></i> Mes Documents</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Summary (Optional) -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4 position-relative z-1 text-center">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: bold;">
                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->prenom }} {{ $user->name }}</h5>
                    <p class="opacity-75 small mb-0">{{ $user->email }}</p>
                </div>
                <!-- Decorative Circle -->
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="border-radius: 50%; transform: scale(1.5) translateY(50%);"></div>
            </div>
        </div>
    </div>
</div>
@endsection
