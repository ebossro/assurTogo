@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Gestion des Polices</h2>
            <p class="text-secondary opacity-75 mb-0">Administrez les contrats et suivez les souscriptions</p>
        </div>
        <a href="{{ route('polices.create') }}" class="btn shadow-sm rounded-pill px-4 fw-medium" style="background-color: #6d28d9; color: white;"><i class="bi bi-plus-lg me-2"></i>Nouvelle Police</a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-2">
            <form action="{{ route('admin.polices') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 ps-2 py-2" name="search" placeholder="Rechercher (Nom, Numéro...)" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-auto border-start d-none d-md-block" style="height: 24px;"></div>
                <div class="col-md-3">
                    <select class="form-select border-0 py-2 text-muted" name="statut" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="rendez_vous_planifie" {{ request('statut') == 'rendez_vous_planifie' ? 'selected' : '' }}>RDV Planifié</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="resilie" {{ request('statut') == 'resilie' ? 'selected' : '' }}>Résilié</option>
                    </select>
                </div>
                <div class="col-md-3 text-end d-flex gap-2 justify-content-end">
                     <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm d-md-none">Filtrer</button>
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('admin.polices') }}" class="btn btn-light rounded-pill px-3 py-1 btn-sm text-muted ms-auto d-flex align-items-center"><i class="bi bi-x-lg me-2"></i>Effacer</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 400px;"> 
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase fw-bold border-0">Police / Assuré</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Formule</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Dates</th>
                             <th class="py-3 text-muted small text-uppercase fw-bold border-0">Montant</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Statut</th>
                            <th class="text-end pe-4 py-3 text-muted small text-uppercase fw-bold border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($polices as $police)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                        {{ substr($police->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.polices.show', $police) }}" class="text-dark fw-bold text-decoration-none d-block">{{ $police->numeroPolice }}</a>
                                        <span class="text-muted small">{{ $police->user->name ?? 'N/A' }} {{ $police->user->prenom ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal">{{ $police->couverture }} / {{ $police->typePolice }}</span>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <span class="d-block"><i class="bi bi-calendar-check me-1 success"></i> {{ $police->dateDebut->format('d/m/Y') }}</span>
                                    <span class="d-block"><i class="bi bi-calendar-x me-1 text-danger"></i> {{ $police->dateFin->format('d/m/Y') }}</span>
                                </div>
                            </td>
                             <td>
                                <span class="fw-bold text-dark">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</span>
                                <span class="d-block small text-muted">par mois</span>
                            </td>
                            <td>
                                 @if($police->statut === 'actif')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Actif</span>
                                @elseif($police->statut === 'en_attente')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">En attente</span>
                                @elseif($police->statut === 'rendez_vous_planifie')
                                     <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">RDV Planifié</span>
                                @elseif($police->statut === 'resilie')
                                     <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Résilié</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">{{ ucfirst($police->statut) }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('admin.polices.show', $police) }}"><i class="bi bi-eye me-2"></i>Détails</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-folder2-open text-muted fs-1 opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Aucune police trouvée pour ces critères.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($polices->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $polices->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
