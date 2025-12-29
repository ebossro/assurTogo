@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <h2 class="fw-bold text-dark mb-2">Tableau de Bord</h2>
    <p class="text-secondary mb-4">Vue d'ensemble des activités de la compagnie</p>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
             <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2">
                             <i class="bi bi-people fs-5"></i>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Assurés Actifs</span>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark display-6">{{ number_format($stats['users_count'], 0, '.', ',') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                         <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-2">
                             <i class="bi bi-file-earmark-check fs-5"></i>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Polices Actives</span>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark display-6">{{ number_format($stats['polices_actives'], 0, '.', ',') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
             <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                         <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-2">
                             <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Sinistres en Attente</span>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark display-6">{{ number_format($stats['sinistres_en_attente'], 0, '.', ',') }}</h2>
                </div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start">
                 <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                         <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-2">
                             <i class="bi bi-currency-exchange fs-5"></i>
                        </div>
                         <span class="text-muted fw-bold small text-uppercase">Revenus Total</span>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark display-6 fs-3">{{ number_format($revenu_total, 0, ',', ' ') }} <small class="fs-6 text-muted fw-normal">FCFA</small></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Recent Policies -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
             <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                <div>
                     <h5 class="fw-bold mb-0 text-dark">Dernières Souscriptions</h5>
                     <small class="text-muted">Polices en attente de validation</small>
                </div>
                <a href="{{ route('admin.polices') }}" class="btn btn-light bg-light rounded-pill px-3 fw-medium text-dark"><i class="bi bi-arrow-right me-2"></i>Voir tout</a>
            </div>
            <div class="card-body p-0">
                 <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 text-muted small text-uppercase">Police / Client</th>
                                <th class="py-3 border-0 text-muted small text-uppercase">Formule</th>
                                <th class="py-3 border-0 text-muted small text-uppercase">Date</th>
                                <th class="py-3 border-0 text-muted small text-uppercase">Statut</th>
                                <th class="pe-4 py-3 border-0 text-end text-muted small text-uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPolices as $police)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                         <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                            {{ substr($police->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark">{{ $police->user->name ?? 'N/A' }}</span>
                                            <small class="text-muted">{{ $police->numeroPolice }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal">{{ $police->typePolice }}</span>
                                </td>
                                <td class="text-muted small">
                                    {{ $police->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                     <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">En attente</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.polices.show', $police) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Gérer</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Aucune police en attente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pending Claims -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light p-4">
                 <h5 class="fw-bold mb-0 text-dark">Sinistres à Traiter</h5>
                 <small class="text-muted">Déclarations nécessitant une action</small>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    @forelse($recentSinistres as $sinistre)
                    <div class="card border bg-light shadow-none transition-hover">
                         <div class="card-body p-3">
                             <div class="d-flex justify-content-between align-items-start mb-2">
                                 <div>
                                      <h6 class="fw-bold mb-0 text-dark">{{ $sinistre->police->user->name ?? 'Assuré' }}</h6>
                                      <small class="text-muted d-block">Ref: {{ str_replace('SIN-', '', $sinistre->reference) }}</small>
                                 </div>
                                 <span class="badge bg-white border text-dark">{{ $sinistre->created_at->diffForHumans() }}</span>
                             </div>
                             
                             <div class="d-flex align-items-center gap-2 mb-3">
                                 <span class="badge bg-white border text-secondary fw-normal">{{ str_replace('_', ' ', $sinistre->type_sinistre) }}</span>
                                 <span class="fw-bold text-dark ms-auto">{{ number_format($sinistre->montant_total, 0, ',', ' ') }} FCFA</span>
                             </div>

                             <div class="d-grid gap-2">
                                <a href="{{ route('admin.sinistres.show', $sinistre) }}" class="btn btn-sm rounded-3" style="background-color: #6d28d9; color: white;">Examiner le dossier</a>
                             </div>
                         </div>
                    </div>
                    @empty
                     <div class="text-center py-5">
                        <i class="bi bi-check-circle text-muted fs-1 mb-3 d-block opacity-25"></i>
                        <p class="text-muted">Tout est à jour !</p>
                    </div>
                    @endforelse
                </div>
                
                @if($recentSinistres->isNotEmpty())
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.sinistres') }}" class="text-decoration-none fw-bold small" style="color: #6d28d9;">Voir tous les sinistres <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
