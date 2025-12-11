@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">Gestion des polices</h2>
            <p class="text-muted">Gérez toutes les polices d'assurance</p>
        </div>
        <a href="{{ route('polices.create') }}" class="btn btn-primary fw-bold"><i class="bi bi-plus-lg me-2"></i>Nouvelle police</a>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Filtres et recherche</h6>
        <p class="text-muted small mb-3">Recherchez et filtrez les polices d'assurance</p>
        
        <form action="{{ route('admin.polices') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search" placeholder="Rechercher par nom, numéro de police..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select text-muted" name="statut" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select text-muted">
                    <option>Tous les types</option>
                    <!-- Mock types -->
                    <option>Essentiel</option>
                    <option>Confort</option>
                    <option>Premium</option>
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
        <h5 class="fw-bold mb-0">Liste des polices <span class="text-muted small fw-normal ms-2">{{ $polices->total() }} polices au total</span></h5>
        <button class="btn btn-light border btn-sm px-3 rounded-pill bg-white shadow-sm"><i class="bi bi-download me-2"></i>Exporter</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small py-3 border-0">Numéro</th>
                        <th class="text-muted small py-3 border-0">Assuré</th>
                        <th class="text-muted small py-3 border-0">Type</th>
                        <th class="text-muted small py-3 border-0">Statut</th>
                        <th class="text-muted small py-3 border-0">Date début</th>
                        <th class="text-muted small py-3 border-0">Date fin</th>
                        <th class="text-muted small py-3 border-0">Prime</th>
                        <th class="text-muted small py-3 border-0">Bénéficiaires</th>
                        <th class="text-muted small py-3 border-0 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polices as $police)
                    <tr>
                        <td class="ps-4 fw-medium">{{ $police->numeroPolice }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($police->user->prenom ?? 'U', 0, 1) }}{{ substr($police->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="fw-medium">{{ $police->user->prenom ?? '' }} {{ $police->user->name ?? 'Utilisateur Inconnu' }}</span>
                            </div>
                        </td>
                        <td>{{ $police->typePolice }}</td>
                        <td>
                            <span @class([
                                'badge rounded-pill px-3 py-2',
                                'bg-primary' => $police->statut === 'actif',
                                'bg-success' => $police->statut === 'en_attente',
                                'bg-danger'  => $police->statut === 'inactif',
                                'bg-light text-dark' => !in_array($police->statut, ['actif', 'en_attente', 'inactif']),
                            ])>
                                {{ ucfirst(str_replace('_', ' ', $police->statut)) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $police->dateDebut ? $police->dateDebut->format('Y-m-d') : '-' }}</td>
                        <td class="text-muted small">{{ $police->dateFin ? $police->dateFin->format('Y-m-d') : '-' }}</td>
                        <td class="fw-bold">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ $police->beneficiaires_count }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.polices.show', $police) }}" class="btn btn-light btn-sm text-muted border-0" title="Voir"><i class="bi bi-eye"></i></a>
                                <a href="#" class="btn btn-light btn-sm text-muted border-0" title="Editer"><i class="bi bi-pencil-square"></i></a>
                                <button class="btn btn-light btn-sm text-danger border-0" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">Aucune police trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 d-flex justify-content-center">
            {{ $polices->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
