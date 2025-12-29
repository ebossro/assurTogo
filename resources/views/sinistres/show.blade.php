@extends('layouts.dashboard')

@section('content')
<div class="mb-5">
    <a href="{{ route('sinistres.index') }}" class="btn btn-light rounded-pill px-3 mb-3 fw-medium">
        <i class="bi bi-arrow-left me-2"></i>Retour aux sinistres
    </a>
    
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2 class="fw-bold text-dark mb-1">Détails du sinistre</h2>
            <p class="text-muted mb-0">Référence: <span class="fw-bold text-dark">{{ $sinistre->reference }}</span></p>
        </div>
        
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
        <span class="badge bg-{{ $statusClass }} fs-6 px-3 py-2 rounded-pill">{{ $statusLabel }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Informations générales</h5>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date du sinistre</label>
                        <p class="fw-medium fs-5 mb-0">{{ $sinistre->date_sinistre->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Montant estimé</label>
                        <p class="fw-bold text-primary fs-5 mb-0">{{ number_format($sinistre->montant_total, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Description</label>
                        <div class="bg-light p-3 rounded-3 mt-1">
                            {{ $sinistre->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Documents justificatifs</h5>
                
                @if($sinistre->fichier_preuve)
                    <div class="d-flex align-items-center p-3 border rounded-3 bg-white">
                        <div class="bg-light rounded-circle p-3 me-3">
                            <i class="bi bi-file-earmark-image fs-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Preuve du sinistre</h6>
                            <p class="mb-0 text-muted small">Document soumis lors de la déclaration</p>
                        </div>
                        <a href="{{ Storage::url($sinistre->fichier_preuve) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-2"></i>Visualiser
                        </a>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-file-earmark-x fs-3 d-block mb-2"></i>
                        Aucun document joint
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Police d'assurance</h5>
                
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $sinistre->police->typePolice }}</h6>
                    <p class="text-muted small mb-0">{{ $sinistre->police->numeroPolice }}</p>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Couverture</span>
                    <span class="fw-medium">{{ $sinistre->police->couverture }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Début</span>
                    <span class="fw-medium">{{ $sinistre->police->dateDebut->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Fin</span>
                    <span class="fw-medium">{{ $sinistre->police->dateFin->format('d/m/Y') }}</span>
                </div>

                <div class="mt-4 pt-4 border-top text-center">
                    <a href="{{ route('polices.show', $sinistre->police) }}" class="btn btn-light w-100 text-primary fw-medium">
                        Voir la police
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
