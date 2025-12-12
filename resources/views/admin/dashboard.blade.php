@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <h2 class="fw-bold text-dark mb-1">Espace Compagnie</h2>
    <p class="text-muted">Gérez vos polices, sinistres et suivez vos performances</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small">Assurés actifs</span>
                    <i class="bi bi-people text-muted"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ number_format($stats['users_count'], 0, '.', ',') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
             <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small">Polices en cours</span>
                    <i class="bi bi-file-text text-muted"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ number_format($stats['polices_actives'], 0, '.', ',') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
             <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small">Sinistres en attente</span>
                    <i class="bi bi-graph-up-arrow text-muted"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $stats['sinistres_en_cours'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
             <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small">Revenus mensuels</span>
                    <i class="bi bi-currency-dollar text-muted"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ number_format($stats['revenu_mensuel'], 0, ',', ' ') }} FCFA</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Chart Section -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Revenus mensuels (Millions FCFA)</h6>
                
                <!-- CSS Bar Chart Mockup -->
                <div class="d-flex justify-content-between align-items-end" style="height: 300px; padding-bottom: 20px;">
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 50%;"></div>
                        <span class="text-muted small">Jan</span>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 65%;"></div>
                        <span class="text-muted small">Fév</span>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 75%;"></div>
                        <span class="text-muted small">Mar</span>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 85%;"></div>
                        <span class="text-muted small">Avr</span>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 80%;"></div>
                        <span class="text-muted small">Mai</span>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2" style="width: 12%;">
                        <div class="bg-black rounded-top" style="width: 100%; height: 95%;"></div>
                        <span class="text-muted small">Juin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sinistres à traiter -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Sinistres à traiter</h6>
                
                <div class="d-flex flex-column gap-3">
                    @forelse($recentSinistres as $sinistre)
                    <div class="border rounded-3 p-3 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                             <div>
                                 <h6 class="fw-bold mb-0 text-dark">{{ $sinistre->police->user->name ?? 'Utilisateur Inconnu' }}</h6>
                                 <small class="text-muted">CLM-{{ $sinistre->id }}</small>
                             </div>
                             <span class="badge bg-success rounded-pill px-2">En attente</span>
                        </div>
                        <div class="mb-3">
                            <span class="d-block small text-muted">{{ $sinistre->libelle }}</span>
                            <span class="fw-bold text-dark">{{ number_format($sinistre->montant, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-check-lg me-1"></i> Approuver</button>
                            <button class="btn btn-light border btn-sm flex-grow-1 text-danger"><i class="bi bi-x-lg me-1"></i> Rejeter</button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        Aucun sinistre en attente
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Polices -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Polices récentes</h5>
        <button class="btn btn-light border btn-sm px-3 rounded-pill bg-white shadow-sm">Voir toutes</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small py-3 border-0">ID Police</th>
                        <th class="text-muted small py-3 border-0">Titulaire</th>
                        <th class="text-muted small py-3 border-0">Plan</th>
                        <th class="text-muted small py-3 border-0">Période</th>
                        <th class="text-muted small py-3 border-0">Prime</th>
                        <th class="text-muted small py-3 border-0">Statut</th>
                        <th class="text-muted small py-3 border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPolices as $police)
                    <tr>
                        <td class="ps-4 fw-medium">{{ $police->numeroPolice }}</td>
                        <td>{{ $police->user->name ?? '-' }}</td>
                        <td>{{ $police->typePolice }}</td>
                        <td class="text-muted small">{{ $police->dateDebut->format('d/m/Y') }} - {{ $police->dateFin->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($police->statut === 'actif')
                                <span class="badge bg-primary rounded-pill px-3 py-2">Actif</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-2">{{ ucfirst($police->statut) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a  href="{{ route('admin.polices.show', $police->id) }}"><button class="btn btn-light btn-sm text-muted"><i class="bi bi-eye"></i></button></a>
                                <button class="btn btn-light btn-sm text-muted"><i class="bi bi-download"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
