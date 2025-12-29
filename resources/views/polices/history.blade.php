@extends('layouts.dashboard')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold text-dark mb-2">Historique de mes Polices</h2>
        <p class="text-secondary opacity-75 mb-0">Consultez l'historique complet de toutes vos polices souscrites.</p>
    </div>
    <div>
        <a href="{{ route('polices.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>

<!-- Historique des Polices -->
<div class="row">
    <div class="col-12">
        @if($polices->count() > 0)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-muted small py-3 border-0 fw-bold">Numéro Police</th>
                                    <th class="text-muted small py-3 border-0 fw-bold">Formule</th>
                                    <th class="text-muted small py-3 border-0 fw-bold">Date Début</th>
                                    <th class="text-muted small py-3 border-0 fw-bold">Date Fin</th>
                                    <th class="text-muted small py-3 border-0 fw-bold">Prime Mensuelle</th>
                                    <th class="text-muted small py-3 border-0 fw-bold">Statut</th>
                                    <th class="text-muted small py-3 border-0 fw-bold text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($polices as $police)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $police->numeroPolice }}</div>
                                        <small class="text-muted">{{ $police->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $police->formule ? $police->formule->nom : $police->typePolice }}</span>
                                        <div class="small text-muted">{{ $police->couverture }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $police->dateDebut ? $police->dateDebut->format('d/m/Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $police->dateFin ? $police->dateFin->format('d/m/Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($police->statut) {
                                                'actif' => 'bg-success bg-opacity-10 text-success',
                                                'en_attente' => 'bg-warning bg-opacity-10 text-warning',
                                                'rendez_vous_planifie' => 'bg-info bg-opacity-10 text-info',
                                                'suspendu' => 'bg-danger bg-opacity-10 text-danger',
                                                'resilie' => 'bg-secondary bg-opacity-10 text-secondary',
                                                'expire' => 'bg-danger bg-opacity-10 text-danger',
                                                default => 'bg-secondary bg-opacity-10 text-secondary'
                                            };
                                            $statusLabel = match($police->statut) {
                                                'actif' => 'Actif',
                                                'en_attente' => 'En attente',
                                                'rendez_vous_planifie' => 'RDV Planifié',
                                                'suspendu' => 'Suspendu',
                                                'resilie' => 'Résiliée',
                                                'expire' => 'Expirée',
                                                default => ucfirst($police->statut)
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('polices.show', $police) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i>Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-clock-history text-primary fs-1 opacity-50"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Aucun historique disponible</h4>
                    <p class="text-muted mb-4 col-md-6 mx-auto">Vous n'avez pas encore de polices dans votre historique.</p>
                    <a href="{{ route('polices.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                        Retour à mes polices
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

