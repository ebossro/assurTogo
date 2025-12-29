@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -60px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
             <a href="{{ route('admin.users') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="fw-bold text-dark mb-0">Détails utilisateur</h2>
                <p class="text-muted mb-0">Informations complètes sur l'assuré</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger text-white border shadow-sm"><i class="bi bi-trash me-2"></i>Supprimer</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: User Profile & Personal Info -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->photo_profil && file_exists(storage_path('app/public/' . $user->photo_profil)))
                            <img src="{{ asset('storage/' . $user->photo_profil) }}" alt="Profil" class="rounded-circle object-fit-cover shadow-sm" style="width: 120px; height: 120px;">
                        @else
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm mx-auto" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ substr($user->prenom, 0, 1) }}{{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle"></span>
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-1">{{ $user->prenom }} {{ $user->name }}</h4>
                    <p class="text-muted mb-2">USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                        {{ $user->role_id == 2 ? 'Client' : 'Assuré' }} <!-- Assuming role IDs logic -->
                    </span>
                    
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-envelope me-2"></i>Contacter</a>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h6 class="fw-bold mb-0">Coordonnées</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Email</label>
                        <p class="mb-0 fw-medium">{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Téléphone</label>
                        <p class="mb-0 fw-medium">{{ $user->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Adresse</label>
                        <p class="mb-0">{{ $user->adresse ?? '-' }}</p>
                        <p class="mb-0 small text-muted">{{ $user->quartier }} {{ $user->ville ? ', '.$user->ville : '' }}</p>
                    </div>
                </div>
            </div>

             <!-- Personal Info -->
             <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h6 class="fw-bold mb-0">Informations Personnelles</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                         <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Genre</label>
                            <p class="mb-0">{{ $user->sexe == 'M' ? 'Masculin' : ($user->sexe == 'F' ? 'Féminin' : '-') }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Né le</label>
                            <p class="mb-0">{{ $user->date_naissance ? Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Profession</label>
                            <p class="mb-0">{{ $user->profession ?? '-' }}</p>
                        </div>
                         <div class="col-12">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Situation Matrimoniale</label>
                            <p class="mb-0 text-capitalize">{{ str_replace('_', ' ', $user->statut_matrimonial ?? '-') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Policies & History -->
        <div class="col-lg-8">
            <!-- Policies Section -->
             <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Polices Souscrites ({{ $user->polices->count() }})</h5>
                    @if($user->polices->isEmpty())
                        <a href="#" class="btn btn-sm btn-primary">Nouvelle police</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($user->polices->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small text-uppercase border-0">N° Police</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Type</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Couverture</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Dates</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Statut</th>
                                        <th class="text-end pe-4 py-3 text-muted small text-uppercase border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->polices as $police)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $police->numeroPolice }}</td>
                                        <td>{{ $police->typePolice }}</td>
                                        <td>{{ $police->couverture }}</td>
                                        <td>
                                            <div class="small text-muted">
                                                <span class="d-block"><i class="bi bi-calendar-check me-1 text-success"></i> {{ $police->dateDebut ? $police->dateDebut->format('d/m/Y') : '-' }}</span>
                                                <span class="d-block"><i class="bi bi-calendar-x me-1 text-danger"></i> {{ $police->dateFin ? $police->dateFin->format('d/m/Y') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($police->statut == 'actif')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Actif</span>
                                            @elseif($police->statut == 'en_attente')
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">En attente</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3">{{ $police->statut }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.polices.show', $police) }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x text-muted display-4 mb-3"></i>
                            <p class="text-muted">Aucune police trouvée pour cet utilisateur.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Claims (Sinistres) -->
             <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">Historique des Sinistres</h5>
                </div>
                <div class="card-body p-0">
                    @php
                        // Get all sinistres from all polices
                        $sinistres = $user->polices->flatMap(function ($police) {
                            return $police->sinistres;
                        })->sortByDesc('created_at')->take(5);
                    @endphp

                    @if($sinistres->isNotEmpty())
                         <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small text-uppercase border-0">Référence</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Type</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Date</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Montant</th>
                                        <th class="py-3 text-muted small text-uppercase border-0">Statut</th>
                                        <th class="text-end pe-4 py-3 text-muted small text-uppercase border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sinistres as $sinistre)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">{{ $sinistre->reference }}</span>
                                            <small class="d-block text-muted">{{ $sinistre->police->numeroPolice }}</small>
                                        </td>
                                        <td>{{ str_replace('_', ' ', $sinistre->type_sinistre) }}</td>
                                        <td>{{ $sinistre->date_sinistre ? Carbon\Carbon::parse($sinistre->date_sinistre)->format('d/m/Y') : '-' }}</td>
                                        <td class="fw-bold">{{ number_format($sinistre->montant_total, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($sinistre->statut == 'valide' || $sinistre->statut == 'approuve')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Validé</span>
                                            @elseif($sinistre->statut == 'rejete')
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Rejeté</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">En cours</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.sinistres.show', $sinistre) }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-muted display-4 mb-3"></i>
                            <p class="text-muted">Aucun sinistre déclaré.</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
