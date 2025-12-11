@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <a href="{{ route('admin.sinistres') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold text-dark mb-0">Sinistre {{ $sinistre->reference }}</h2>
            </div>
            <p class="text-muted ms-5 ps-2">Détails du sinistre et de la police associée</p>
        </div>
        <div class="d-flex gap-2">
            {{-- Actions placeholder --}}
            <button class="btn btn-light border shadow-sm"><i class="bi bi-printer me-2"></i>Imprimer</button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Claim Details -->
    <div class="col-lg-8">
        <!-- Sinistre Info -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Informations du Sinistre</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Référence</label>
                        <p class="fw-bold fs-5 mb-0">{{ $sinistre->reference }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date du Sinistre</label>
                        <p class="fw-medium mb-0">{{ $sinistre->date_sinistre ? $sinistre->date_sinistre->format('d/m/Y') : '-' }}</p>
                    </div>
                     <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Statut</label>
                             @if($sinistre->statut == 'valide')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Validé</span>
                            @elseif($sinistre->statut == 'rejete')
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Rejeté</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">En cours</span>
                            @endif
                    </div>
                    <div class="col-md-6">
                         <label class="text-muted small text-uppercase fw-bold mb-1">Montant Estimé</label>
                         <p class="fw-bold text-primary fs-5 mb-0">{{ number_format($sinistre->montant_estime, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Description</label>
                        <p class="text-muted bg-light p-3 rounded-3 mb-0">{{ $sinistre->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents / Preuves -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Documents & Preuves</h5>
            </div>
            <div class="card-body p-4 pt-0">
                @if($sinistre->fichier_preuve)
                <div class="d-flex align-items-center p-3 border rounded-3">
                    <i class="bi bi-file-earmark-text fs-3 text-secondary me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Preuve du sinistre</h6>
                        <small class="text-muted">Document joint lors de la déclaration</small>
                    </div>
                    <a href="{{ asset('storage/' . $sinistre->fichier_preuve) }}" target="_blank" class="btn btn-sm btn-outline-primary">Visualiser</a>
                </div>
                @else
                <p class="text-muted text-center py-4">Aucun document joint.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Policy & User Info -->
    <div class="col-lg-4">
        <!-- Policy Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
             <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Police Concernée</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-shield-check text-primary fs-4"></i>
                    </div>
                    <div>
                        <span class="d-block small text-muted">Numéro de Police</span>
                        <a href="{{ route('admin.polices.show', $sinistre->police) }}" class="fw-bold text-decoration-none">{{ $sinistre->police->numeroPolice }}</a>
                    </div>
                </div>
                 <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Type</span>
                    <span class="fw-medium">{{ $sinistre->police->typePolice }}</span>
                </div>
                 <div class="d-flex justify-content-between">
                    <span class="text-muted">Couverture</span>
                    <span class="fw-medium">{{ $sinistre->police->couverture }}</span>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Informations de l'assuré</h5>
            </div>
            <div class="card-body p-4 pt-0 text-center">
                 <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ substr($sinistre->police->user->prenom ?? 'U', 0, 1) }}{{ substr($sinistre->police->user->name ?? 'U', 0, 1) }}
                </div>
                <h5 class="fw-bold">{{ $sinistre->police->user->prenom }} {{ $sinistre->police->user->name }}</h5>
                <p class="text-muted small mb-3">{{ $sinistre->police->user->email }}</p>
                 <div class="text-start border-top pt-3">
                     <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone text-muted me-3"></i>
                        <span class="fw-medium">{{ $sinistre->police->user->telephone ?? 'Non renseigné' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
