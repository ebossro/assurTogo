@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center" style="margin-top: -70px;">
    <div class="col">
        <h1 class="h3 text-gray-800 mb-0" style="font-weight: bold;">Historique des Données</h1>
        <p class="text-muted mb-0">Archives des polices expirées/résiliées et des sinistres clôturés.</p>
    </div>
</div>

<div class="card shadow-sm mb-4 border-0 rounded-4">
    <div class="card-header bg-white border-0 py-3 rounded-top-4">
        <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-medium px-4" id="polices-tab" data-bs-toggle="tab" data-bs-target="#polices" type="button" role="tab" aria-controls="polices" aria-selected="true">
                    <i class="bi bi-file-earmark-text me-2"></i>Polices Archivées
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-medium px-4" id="sinistres-tab" data-bs-toggle="tab" data-bs-target="#sinistres" type="button" role="tab" aria-controls="sinistres" aria-selected="false">
                    <i class="bi bi-exclamation-triangle me-2"></i>Sinistres Archivés
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="historyTabContent">
            <!-- Polices Tab -->
            <div class="tab-pane fade show active" id="polices" role="tabpanel" aria-labelledby="polices-tab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>N° Police</th>
                                <th>Assuré</th>
                                <th>Dates</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($archivedPolices as $police)
                            <tr>
                                <td class="fw-bold">{{ $police->numeroPolice }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px">
                                            {{ substr($police->user->prenom, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $police->user->prenom }} {{ $police->user->name }}</div>
                                            <div class="small text-muted">{{ $police->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>Du: {{ \Carbon\Carbon::parse($police->dateDebut)->format('d/m/Y') }}</div>
                                        <div>Au: {{ \Carbon\Carbon::parse($police->dateFin)->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($police->statut === 'expire')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Expirée</span>
                                    @elseif($police->statut === 'resilie')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Résiliée</span>
                                    @elseif($police->statut === 'suspendu')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Suspendue</span>
                                    @else
                                        <span class="badge bg-light text-dark border rounded-pill px-3">{{ ucfirst($police->statut) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.polices.show', $police) }}" class="btn btn-sm btn-light text-primary rounded-circle" title="Voir les détails">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x display-6 d-block mb-3"></i>
                                    Aucune police archivée trouvée.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Polices -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $archivedPolices->appends(['sinistres_page' => request('sinistres_page')])->links() }}
                </div>
            </div>

            <!-- Sinistres Tab -->
            <div class="tab-pane fade" id="sinistres" role="tabpanel" aria-labelledby="sinistres-tab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Référence</th>
                                <th>Police / Assuré</th>
                                <th>Montant</th>
                                <th>Date Réclamation</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($archivedSinistres as $sinistre)
                            <tr>
                                <td class="fw-bold">{{ $sinistre->reference }}</td>
                                <td>
                                    <div class="mb-1 fw-bold">{{ $sinistre->police->numeroPolice }}</div>
                                    <div class="small text-muted">{{ $sinistre->police->user->prenom }} {{ $sinistre->police->user->name }}</div>
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ number_format($sinistre->montant_demande, 0, ',', ' ') }} FCFA
                                </td>
                                <td>{{ $sinistre->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($sinistre->statut === 'approuve')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Approuvé</span>
                                    @elseif($sinistre->statut === 'rejete')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Rejeté</span>
                                    @else
                                        <span class="badge bg-light text-dark border rounded-pill px-3">{{ ucfirst($sinistre->statut) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.sinistres.show', $sinistre) }}" class="btn btn-sm btn-light text-primary rounded-circle" title="Voir">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x display-6 d-block mb-3"></i>
                                    Aucun sinistre archivé trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Sinistres -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $archivedSinistres->appends(['polices_page' => request('polices_page')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
