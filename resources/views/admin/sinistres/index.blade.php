@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <h2 class="fw-bold text-dark mb-1">Gestion des sinistres</h2>
    <p class="text-muted">Traitez et gérez toutes les demandes de remboursement</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-1">En attente</p>
                <h2 class="fw-bold mb-3">{{ $pendingCount }}</h2>
                <div class="text-muted small">
                    Nécessitent une action
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-1">Approuvés ce mois</p>
                <h2 class="fw-bold mb-3">{{ $approvedCount }}</h2>
                <div class="text-success small fw-medium">
                    <i class="bi bi-arrow-up-short"></i> +12% vs mois dernier
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <p class="text-muted small mb-1">Montant total</p>
                <h2 class="fw-bold mb-3">{{ number_format($totalAmount, 0, ',', '.') }}M</h2>
                <div class="text-muted small">
                    FCFA ce mois
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Filtres et recherche</h6>
        <p class="text-muted small mb-3">Recherchez et filtrez les sinistres</p>
        
        <form action="{{ route('admin.sinistres') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search" placeholder="Rechercher par numéro, assuré..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select text-muted" name="statut" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Approuvé</option>
                    <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select text-muted">
                    <option>Tous les types</option>
                    <option>Consultation</option>
                    <option>Hospitalisation</option>
                    <option>Médicaments</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-light w-100 border"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Liste des sinistres <span class="text-muted small fw-normal ms-2">{{ $sinistres->total() }} sinistres récents</span></h5>
        <button class="btn btn-light border btn-sm px-3 rounded-pill bg-white shadow-sm"><i class="bi bi-download me-2"></i>Exporter</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small py-3 border-0">Numéro</th>
                        <th class="text-muted small py-3 border-0">Police</th>
                        <th class="text-muted small py-3 border-0">Assuré</th>
                        <th class="text-muted small py-3 border-0">Description</th>
                        <th class="text-muted small py-3 border-0">Montant</th>
                        <th class="text-muted small py-3 border-0">Date</th>
                        <th class="text-muted small py-3 border-0">Preuve</th>
                        <th class="text-muted small py-3 border-0">Statut</th>
                        <th class="text-muted small py-3 border-0 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sinistres as $sinistre)
                    <tr>
                        <td class="ps-4 fw-medium">{{ $sinistre->reference }}</td>
                        <td class="text-muted">{{ $sinistre->police->numeroPolice ?? '-' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($sinistre->police->user->prenom ?? 'U', 0, 1) }}{{ substr($sinistre->police->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="fw-medium">{{ $sinistre->police->user->prenom ?? '' }} {{ $sinistre->police->user->name ?? 'Utilisateur Inconnu' }}</span>
                            </div>
                        </td>
                        <td>{{ Str::limit($sinistre->description, 20) }}</td>
                        <td class="fw-bold">{{ number_format($sinistre->montant_estime, 0, ',', ' ') }} FCFA</td>
                        <td class="text-muted small">{{ $sinistre->date_sinistre ? $sinistre->date_sinistre->format('Y-m-d') : '-' }}</td>
                        <td>
                            @if($sinistre->fichier_preuve)
                                <a href="{{ Storage::url($sinistre->fichier_preuve) }}" target="_blank" class="btn btn-sm btn-light border" title="Voir la preuve">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span @class([
                                'badge rounded-pill px-3 py-2',
                                'bg-warning' => $sinistre->statut == 'en_cours', 
                                'bg-success' => $sinistre->statut == 'valide', 
                                'bg-danger' => $sinistre->statut == 'rejete',
                            ])>
                                {{ $sinistre->statut == 'en_cours' ? 'En cours' : ($sinistre->statut == 'valide' ? 'Approuvé' : ucfirst($sinistre->statut)) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.sinistres.show', $sinistre) }}" class="btn btn-light btn-sm text-muted border-0 rounded-circle" title="Voir"><i class="bi bi-eye"></i></a>
                                @if($sinistre->statut == 'en_cours')
                                <form action="{{ route('admin.sinistres.approve', $sinistre) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm text-success border-0 rounded-circle" title="Approuver"><i class="bi bi-check-circle"></i></button>
                                </form>
                                <form action="{{ route('admin.sinistres.reject', $sinistre) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm text-danger border-0 rounded-circle" title="Rejeter"><i class="bi bi-x-circle"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Aucun sinistre trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 d-flex justify-content-center">
            {{ $sinistres->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
