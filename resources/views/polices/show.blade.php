@extends('layouts.dashboard')

@section('content')
<div class="mb-5">
    <a href="{{ route('polices.index') }}" class="btn btn-light rounded-pill px-3 mb-3 fw-medium">
        <i class="bi bi-arrow-left me-2"></i>Retour à mes polices
    </a>
    
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2 class="fw-bold text-dark mb-1">Détails de la police</h2>
            <p class="text-muted mb-0">N° {{ $police->numeroPolice }}</p>
        </div>
        
        @php
            $statusClass = match($police->statut) {
                'actif' => 'success',
                'en_attente' => 'warning',
                'suspendu' => 'danger',
                default => 'secondary'
            };
            $statusLabel = match($police->statut) {
                'actif' => 'Active',
                'en_attente' => 'En attente',
                'suspendu' => 'Suspendue',
                default => ucfirst($police->statut)
            };
        @endphp
        <span class="badge bg-{{ $statusClass }} fs-6 px-3 py-2 rounded-pill">{{ $statusLabel }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Informations principales -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Informations du contrat</h5>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Type d'assurance</label>
                        <p class="fw-bold fs-5 mb-0">{{ $police->typePolice }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Couverture</label>
                        <p class="fw-bold fs-5 mb-0">{{ $police->couverture }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date de début</label>
                        <p class="fw-medium mb-0">{{ $police->dateDebut->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date d'échéance</label>
                        <p class="fw-medium mb-0">{{ $police->dateFin->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-12">
                        <div class="bg-light p-3 rounded-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted">Prime Mensuelle</span>
                            <span class="fw-bold text-primary fs-4">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bénéficiaires -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Bénéficiaires</h5>
                <span class="badge bg-light text-dark">{{ $police->beneficiaires->count() }}</span>
            </div>
            <div class="card-body p-4">
                @if($police->beneficiaires->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Nom complet</th>
                                    <th class="border-0">Relation</th>
                                    <th class="border-0 rounded-end">Date de naissance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($police->beneficiaires as $beneficiaire)
                                <tr>
                                    <td class="fw-medium">{{ $beneficiaire->prenom }} {{ $beneficiaire->nom }}</td>
                                    <td>{{ $beneficiaire->relation ?? '-' }}</td>
                                    <td>{{ $beneficiaire->date_naissance ? \Carbon\Carbon::parse($beneficiaire->date_naissance)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        Aucun bénéficiaire enregistré
                    </div>
                @endif
            </div>
        </div>

        <!-- Sinistres récents -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-0">Sinistres associés</h5>
            </div>
            <div class="card-body p-4">
                @if($police->sinistres->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($police->sinistres->take(3) as $sinistre)
                        <a href="{{ route('sinistres.show', $sinistre) }}" class="list-group-item list-group-item-action border-0 px-0 py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $sinistre->reference }}</h6>
                                    <small class="text-muted">{{ $sinistre->date_sinistre->format('d/m/Y') }} - {{ Str::limit($sinistre->description, 50) }}</small>
                                </div>
                                <span class="badge bg-light text-dark">{{ ucfirst($sinistre->statut) }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-shield-check fs-3 d-block mb-2"></i>
                        Aucun sinistre déclaré pour cette police
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Actions</h5>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-outline-primary py-2 text-start">
                        <i class="bi bi-download me-2"></i>Télécharger le contrat
                    </button>
                    <button class="btn btn-outline-primary py-2 text-start">
                        <i class="bi bi-receipt me-2"></i>Attestation d'assurance
                    </button>
                    <a href="{{ route('sinistres.create') }}" class="btn btn-primary py-2 text-start">
                        <i class="bi bi-exclamation-triangle me-2"></i>Déclarer un sinistre
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 text-white bg-primary bg-gradient">
            <div class="card-body p-4 text-center">
                <i class="bi bi-headset fs-1 mb-3"></i>
                <h5 class="fw-bold">Besoin d'aide ?</h5>
                <p class="small mb-4 text-white-50">Notre équipe est disponible pour répondre à toutes vos questions concernant votre police d'assurance.</p>
                <button class="btn btn-light text-primary fw-bold w-100">Contacter le support</button>
            </div>
        </div>
    </div>
</div>
@endsection
