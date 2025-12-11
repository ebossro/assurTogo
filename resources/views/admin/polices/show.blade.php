@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <a href="{{ route('admin.polices') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold text-dark mb-0">Police {{ $police->numeroPolice }}</h2>
            </div>
            <p class="text-muted ms-5 ps-2">Détails de la souscription et de l'assuré</p>
        </div>
        <div class="d-flex gap-2">
            @if($police->statut === 'en_attente')
            <form action="{{ route('admin.polices.validate', $police) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary shadow-sm"><i class="bi bi-check-lg me-2"></i>Valider la demande</button>
            </form>
            <form action="{{ route('admin.polices.reject', $police) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?');">
                @csrf
                <button type="submit" class="btn btn-danger shadow-sm"><i class="bi bi-x-lg me-2"></i>Rejeter</button>
            </form>
            @endif
            <button class="btn btn-light border shadow-sm"><i class="bi bi-printer me-2"></i>Imprimer</button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details & Beneficiaries -->
    <div class="col-lg-8">
        <!-- Policy Info -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Informations de la police</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Plan d'assurance</label>
                        <p class="fw-bold fs-5 mb-0">{{ $police->formule ? $police->formule->nom : $police->typePolice }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Couverture</label>
                        <p class="fw-bold fs-5 mb-0">{{ $police->couverture }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date de début</label>
                        <p class="fw-medium mb-0">{{ $police->dateDebut ? $police->dateDebut->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Date de fin (Renouvellement)</label>
                        <p class="fw-medium mb-0">{{ $police->dateFin ? $police->dateFin->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-12">
                         <div class="bg-light rounded-3 p-3 d-flex justify-content-between align-items-center">
                             <span class="fw-bold text-muted">Prime Mensuelle</span>
                             <span class="fw-bold text-primary fs-4">{{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Médicales -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Informations Médicales</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Antécédents médicaux</label>
                        <p class="mb-0">{{ $police->antecedents_medicaux ?: 'Néant' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Médicaments actuels</label>
                        <p class="mb-0">{{ $police->medicaments_actuels ?: 'Néant' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Allergies</label>
                        <p class="mb-0">{{ $police->allergies ?: 'Néant' }}</p>
                    </div>
                     <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Habitudes de vie</label>
                        <p class="mb-0">{{ $police->habitudes_vie ?: 'Néant' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Beneficiaries -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Bénéficiaires <span class="badge bg-light text-dark ms-2">{{ $police->beneficiaires->count() }}</span></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small py-3 border-0">Nom complet</th>
                                <th class="text-muted small py-3 border-0">Relation</th>
                                <th class="text-muted small py-3 border-0">Date de naissance</th>
                                <th class="text-muted small py-3 border-0">Sexe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($police->beneficiaires as $beneficiaire)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $beneficiaire->prenomBeneficiaire }} {{ $beneficiaire->nomBeneficiaire }}</td>
                                <td>{{ $beneficiaire->relationBeneficiaire ?? '-' }}</td>
                                <td class="text-muted">{{ $beneficiaire->dateNaissanceBeneficiaire ? \Carbon\Carbon::parse($beneficiaire->dateNaissanceBeneficiaire)->format('d/m/Y') : '-' }}</td>
                                <td>{{ ucfirst($beneficiaire->genreBeneficiaire) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Aucun bénéficiaire enregistré
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Subscriber Info -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="fw-bold mb-0">Informations de l'assuré</h5>
            </div>
            <div class="card-body p-4 pt-0 text-center">
                 <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($police->user->prenom ?? 'U', 0, 1) }}{{ substr($police->user->name ?? 'U', 0, 1) }}
                </div>
                <h5 class="fw-bold">{{ $police->user->prenom }} {{ $police->user->name }}</h5>
                <p class="text-muted mb-4">{{ $police->user->email }}</p>

                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-telephone text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Téléphone</span>
                            <span class="fw-medium">{{ $police->user->telephone ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-geo-alt text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Adresse</span>
                            <span class="fw-medium">{{ $police->user->adresse ?? 'Non renseignée' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Né(e) le</span>
                            <span class="fw-medium">{{ $police->user->date_naissance ? \Carbon\Carbon::parse($police->user->date_naissance)->format('d/m/Y') : '-' }} ({{ $police->user->sexe }})</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-vcard text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Pièce d'identité</span>
                            <span class="fw-medium">{{ $police->user->type_piece }} - {{ $police->user->numero_piece }}</span>
                        </div>
                    </div>
                     <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-hearts text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Situation</span>
                            <span class="fw-medium">{{ ucfirst($police->user->statut_matrimonial) }} ({{ $police->user->nombre_enfants }} enf.)</span>
                        </div>
                    </div>
                     <div class="d-flex align-items-center">
                        <i class="bi bi-briefcase text-muted me-3 fs-5"></i>
                        <div>
                            <span class="d-block small text-muted">Profession</span>
                            <span class="fw-medium">{{ $police->user->profession }}</span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <a href="#" class="btn btn-outline-primary w-100">Voir le profil complet</a>
            </div>
        </div>
    </div>
</div>
@endsection
