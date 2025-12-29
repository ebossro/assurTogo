@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <a href="{{ route('admin.sinistres') }}" class="btn btn-white border-0 rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold mb-0" style="color: #000;">Dossier Sinistre</h2>
                <span class="badge bg-primary bg-opacity-20 ml-2 px-3 py-2 rounded-pill fw-normal" style="color: #fff;">Ref: {{ str_replace('SIN-', '', $sinistre->reference) }}</span>
            </div>
            <p class="mb-0 opacity-75 ms-5 ps-2 text-muted">Gérez les détails, vérifiez les pièces et validez le dossier.</p>
        </div>
        <div class="d-flex gap-2">
             <button class="btn btn-white bg-white text-dark shadow-sm fw-medium"><i class="bi bi-printer me-2"></i>Imprimer</button>
            
            {{-- Action Buttons Logic --}}
            @if($sinistre->statut == 'en_attente')
                <button type="button" class="btn btn-info text-white shadow-sm" onclick="document.getElementById('analyze-form-{{ $sinistre->id }}').submit();">
                    <i class="bi bi-search me-2"></i>Lancer l'analyse
                </button>
                <form id="analyze-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.analyze', $sinistre) }}" method="POST" class="d-none">@csrf</form>

                <button type="button" class="btn btn-danger text-white shadow-sm bg-gradient" onclick="if(confirm('Rejeter ce sinistre ?')) document.getElementById('reject-form-{{ $sinistre->id }}').submit();">
                    <i class="bi bi-x-circle me-2"></i>Rejeter
                </button>
                <form id="reject-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.reject', $sinistre) }}" method="POST" class="d-none">@csrf</form>
            @endif

            @if($sinistre->statut == 'en_analyse')
                <button type="button" class="btn btn-success text-white shadow-sm bg-gradient" onclick="if(confirm('Approuver ce sinistre ?')) document.getElementById('approve-form-{{ $sinistre->id }}').submit();">
                    <i class="bi bi-check-circle me-2"></i>Approuver
                </button>
                <form id="approve-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.approve', $sinistre) }}" method="POST" class="d-none">@csrf</form>

                <button type="button" class="btn btn-danger text-white shadow-sm bg-gradient" onclick="if(confirm('Rejeter ce sinistre ?')) document.getElementById('reject-form-{{ $sinistre->id }}').submit();">
                    <i class="bi bi-x-circle me-2"></i>Rejeter
                </button>
                <form id="reject-form-{{ $sinistre->id }}" action="{{ route('admin.sinistres.reject', $sinistre) }}" method="POST" class="d-none">@csrf</form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            
            <!-- Context Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                             <span class="text-uppercase small fw-bold text-muted mb-1 d-block">Statut Actuel</span>
                             @if($sinistre->statut == 'approuve')
                                <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fs-6"><i class="bi bi-check-circle-fill me-2"></i>Approuvé</div>
                            @elseif($sinistre->statut == 'en_analyse')
                                <div class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fs-6"><i class="bi bi-search me-2"></i>En cours d'analyse</div>
                            @elseif($sinistre->statut == 'rejete')
                                <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fs-6"><i class="bi bi-x-circle-fill me-2"></i>Rejeté</div>
                            @else
                                <div class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fs-6"><i class="bi bi-hourglass-split me-2"></i>En attente de traitement</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="text-uppercase small fw-bold text-muted mb-1 d-block">Montant Dépensé</span>
                            <h2 class="fw-bold text-dark mb-0">{{ number_format($sinistre->montant_total, 0, ',', ' ') }} <small class="text-muted fs-6">FCFA</small></h2>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                         <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border border-light">
                                <span class="d-block text-muted small mb-1">Date de survenance</span>
                                <span class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ $sinistre->date_sinistre->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border border-light">
                                <span class="d-block text-muted small mb-1">Type de sinistre</span>
                                <span class="fw-bold text-dark text-capitalize"><i class="bi bi-activity me-2 text-primary"></i>{{ str_replace('_', ' ', $sinistre->type_sinistre) }}</span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border border-light">
                                <span class="d-block text-muted small mb-1">Gravité signalée</span>
                                @php
                                    $graviteColor = match($sinistre->gravite) {
                                        'grave' => 'danger',
                                        'moyen' => 'warning',
                                        default => 'success'
                                    };
                                @endphp
                                <span class="fw-bold text-{{ $graviteColor }} text-capitalize"><i class="bi bi-heart-pulse me-2"></i>{{ ucfirst($sinistre->gravite) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Medical Details -->
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0 text-dark">Informations Médicales</h5>
                        </div>
                        <div class="card-body p-4">
                             <div class="mb-4">
                                <label class="small text-uppercase fw-bold text-muted mb-2">Description de l'incident</label>
                                <p class="mb-0 text-dark bg-light p-3 rounded-3">{{ $sinistre->description }}</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                     <label class="small text-uppercase fw-bold text-muted mb-1">Lieu</label>
                                     <p class="fw-medium mb-0">{{ $sinistre->lieu_sinistre }} ({{ $sinistre->ville_pays }})</p>
                                </div>
                                <div class="col-md-6">
                                     <label class="small text-uppercase fw-bold text-muted mb-1">Première consultation</label>
                                     <p class="fw-medium mb-0">{{ $sinistre->premiere_consultation ? 'Oui' : 'Non' }}</p>
                                </div>
                                <div class="col-md-6">
                                     <label class="small text-uppercase fw-bold text-muted mb-1">Médecin Traitant</label>
                                     <p class="fw-medium mb-0">{{ $sinistre->medecin_traitant ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                     <label class="small text-uppercase fw-bold text-muted mb-1">Diagnostic</label>
                                     <p class="fw-medium mb-0">{{ $sinistre->diagnostic ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                     <label class="small text-uppercase fw-bold text-muted mb-1">Traitement prescrit</label>
                                     <p class="fw-medium mb-0">{{ $sinistre->traitement_prescrit ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Documents & Preuves</h5>
                    <span class="badge bg-light text-dark">{{ $sinistre->documents->count() }} pièces</span>
                </div>
                <div class="card-body p-4">
                    @if($sinistre->documents->isNotEmpty())
                        <div class="row g-3">
                            @foreach($sinistre->documents as $document)
                                <div class="col-sm-6 col-md-4">
                                    <div class="card h-100 border transition-hover shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="mb-3">
                                                 <i class="bi bi-file-earmark-pdf text-danger fs-1 opacity-75"></i>
                                            </div>
                                            <h6 class="card-title fw-bold text-truncate mb-1" title="{{ $document->nomDocument }}">{{ $document->nomDocument }}</h6>
                                            <p class="card-text small text-muted mb-3">{{ str_replace('_', ' ', $document->typeDocument) }}</p>
                                            <a href="{{ asset('storage/' . $document->cheminDocument) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                                Voir le document
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder2-open text-muted fs-1 mb-3 d-block opacity-25"></i>
                            <p class="text-muted">Aucun document joint au dossier.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: Profiles -->
        <div class="col-lg-4">
            
            <!-- Beneficiary Profile -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                     @php
                        $user = $sinistre->beneficiaire ?? $sinistre->police->user;
                        $isBeneficiary = (bool) $sinistre->beneficiaire;
                        $name = $isBeneficiary ? $user->nomBeneficiaire : $user->name;
                        $surname = $isBeneficiary ? $user->prenomBeneficiaire : $user->prenom;
                        $relation = $isBeneficiary ? $user->relationBeneficiaire : 'Assuré Principal';
                    @endphp
                    
                    <div class="mb-3 position-relative d-inline-block">
                         <div class="avatar-placeholder rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-2" style="width: 80px; height: 80px;">
                            {{ substr($name, 0, 1) }}{{ substr($surname, 0, 1) }}
                        </div>
                        @if($isBeneficiary)
                            <span class="position-absolute bottom-0 end-0 bg-warning border border-white rounded-circle p-1" style="width: 24px; height: 24px;" title="Bénéficiaire"></span>
                        @else
                            <span class="position-absolute bottom-0 end-0 bg-primary border border-white rounded-circle p-1" style="width: 24px; height: 24px;" title="Assuré Principal"></span>
                        @endif
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $name }} {{ $surname }}</h5>
                    <p class="text-muted small mb-3">{{ $relation }}</p>
                    
                    <hr class="opacity-10 my-3">
                    
                    <div class="text-start">
                         <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Assuré Principal</span>
                            <span class="fw-medium small text-end">{{ $sinistre->police->user->name }} {{ $sinistre->police->user->prenom }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Téléphone</span>
                            <span class="fw-medium small text-end">{{ $sinistre->police->user->telephone ?? '-' }}</span>
                        </div>
                         <div class="d-flex justify-content-between">
                            <span class="text-muted small">Email</span>
                            <span class="fw-medium small text-end text-truncate" style="max-width: 150px;">{{ $sinistre->police->user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policy Info -->
             <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h6 class="fw-bold mb-0 text-dark">Police d'Assurance</h6>
                </div>
                <div class="card-body p-4">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check text-success fs-3 me-3"></i>
                            <div>
                                <span class="d-block small text-muted text-uppercase fw-bold">Numéro de Police</span>
                                <a href="{{ route('admin.polices.show', $sinistre->police) }}" class="fw-bold text-dark text-decoration-none fs-5">{{ $sinistre->police->numeroPolice }}</a>
                            </div>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Formule</span>
                            <span class="fw-bold text-dark">{{ $sinistre->police->couverture }}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span class="text-muted">Type</span>
                            <span class="fw-bold text-dark">{{ $sinistre->police->typePolice }}</span>
                        </li>
                    </ul>
                </div>
            </div>

             <!-- Declarant Info (if different) -->
            @if($sinistre->is_declarant_different)
             <div class="card border-0 shadow-sm rounded-4">
                 <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Déclaré par</h6>
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                             <span class="d-block fw-bold text-dark">{{ $sinistre->declarant_nom }}</span>
                             <span class="small text-muted">{{ $sinistre->declarant_relation }}</span>
                        </div>
                    </div>
                 </div>
             </div>
            @endif

        </div>
    </div>
</div>
@endsection
