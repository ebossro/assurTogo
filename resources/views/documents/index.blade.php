@extends('layouts.dashboard')

@section('content')
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Mes Documents</h2>
            <p class="text-muted mb-0">Retrouvez tous vos documents contractuels et justificatifs</p>
        </div>
        <!-- <button class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-cloud-upload"></i> Envoyer un document
        </button> -->
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control bg-light border-start-0" placeholder="Rechercher un document...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select bg-light border-0">
                        <option value="">Tous les types</option>
                        <option value="contrat">Contrats</option>
                        <option value="attestation">Attestations</option>
                        <option value="releve">Relevés</option>
                        <option value="justificatif">Justificatifs</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    @if($documents->count() > 0)
        <div class="row g-4">
            @foreach($documents as $document)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                @if(Str::contains($document->typeDocument, ['pdf']))
                                    <i class="bi bi-file-earmark-pdf fs-3"></i>
                                @elseif(Str::contains($document->typeDocument, ['image', 'jpg', 'png']))
                                    <i class="bi bi-file-earmark-image fs-3"></i>
                                @else
                                    <i class="bi bi-file-earmark-text fs-3"></i>
                                @endif
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                    <li><a class="dropdown-item" href="{{ route('documents.show', $document->id) }}"><i class="bi bi-eye me-2"></i>Aperçu</a></li>
                                    <li><a class="dropdown-item" href="{{ route('documents.download', $document->id) }}"><i class="bi bi-download me-2"></i>Télécharger</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('documents.destroy', $document->id) }}"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
                                </ul>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $document->nomDocument }}">{{ $document->nomDocument }}</h6>
                        <p class="text-muted small mb-3">{{ $document->typeDocument }} • {{ $document->tailleDocument ?? 'N/A' }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted bg-light rounded-pill px-2 py-1">
                                {{ $document->created_at->format('d/m/Y') }}
                            </small>
                            <a href="{{ route('documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-folder2-open text-muted display-6"></i>
            </div>
            <h5 class="fw-bold text-dark">Aucun document</h5>
            <p class="text-muted">Vous n'avez pas encore de documents disponibles.</p>
        </div>
    @endif
</div>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection
