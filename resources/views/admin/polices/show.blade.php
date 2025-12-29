@extends('layouts.admin')

@section('content')
<div class="mb-5" style="margin-top: -70px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <a href="{{ route('admin.polices') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold text-dark mb-0">Police {{ $police->numeroPolice }}</h2>
                @php
                    $badgeClass = match($police->statut) {
                        'en_attente' => 'bg-warning text-dark',
                        'rendez_vous_planifie' => 'bg-info text-dark',
                        'actif' => 'bg-success text-white',
                        'suspendu' => 'bg-danger text-white',
                        'resilie' => 'bg-dark text-white',
                        default => 'bg-secondary text-white'
                    };
                    $statusLabel = match($police->statut) {
                        'en_attente' => 'En attente',
                        'rendez_vous_planifie' => 'RDV Planifié',
                        'actif' => 'Actif',
                        'suspendu' => 'Suspendu',
                        'resilie' => 'Résilié',
                        default => $police->statut
                    };
                @endphp
                <span class="badge {{ $badgeClass }} fs-7 px-3 py-2 rounded-pill ms-2">{{ $statusLabel }}</span>
            </div>
            <p class="text-muted ms-5 ps-2">
                @switch($police->statut)
                    @case('en_attente')
                        En attente de validation par l'administrateur.
                        @break
                    @case('rendez_vous_planifie')
                        Rendez-vous fixé. En attente de la visite client pour activation.
                        @if($police->date_rendez_vous)
                            <strong>({{ $police->date_rendez_vous->format('d/m/Y H:i') }})</strong>
                        @endif
                        @break
                    @case('actif')
                        Police en vigueur. Couverture active.
                        @break
                    @case('suspendu')
                        Police temporairement désactivée.
                        @break
                    @case('resilie')
                        Contrat terminé définitivement.
                        @break
                @endswitch
            </p>
        </div>
        <div class="d-flex gap-2">
            @if($police->statut === 'en_attente')
                @if($isRenewal)
                    <!-- RENOUVELLEMENT -> Valider directement -->
                    <form action="{{ route('admin.polices.validate_subscription', $police) }}" method="POST" onsubmit="return confirm('Confirmer la validation du renouvellement et l\'activation du compte ?');">
                        @csrf
                        <button type="submit" class="btn btn-success shadow-sm"><i class="bi bi-check-circle me-2"></i>Valider</button>
                    </form>
                @else
                    <!-- NOUVELLE SOUSCRIPTION -> Planifier RDV -->
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
                    <i class="bi bi-calendar-event me-2"></i>Rendez-vous
                </button>
                @endif
                
                <form action="{{ route('admin.polices.reject', $police) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?');">
                    @csrf
                    <button type="submit" class="btn btn-danger shadow-sm"><i class="bi bi-x-lg me-2"></i>Rejeter</button>
                </form>

            @elseif($police->statut === 'rendez_vous_planifie')
                <!-- RDV PLANIFIE -> Valider -->
                 <form action="{{ route('admin.polices.validate_subscription', $police) }}" method="POST" onsubmit="return confirm('Confirmer la validation du dossier et l\'activation du compte ?');">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm"><i class="bi bi-check-circle me-2"></i>Valider</button>
                </form>
                 <form action="{{ route('admin.polices.reject', $police) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ?');">
                     @csrf
                     <button type="submit" class="btn btn-danger shadow-sm"><i class="bi bi-x-lg me-2"></i>Rejeter</button>
                 </form>

            @elseif($police->statut === 'actif')
                <!-- ACTIF -->
                <form action="{{ route('admin.polices.suspend', $police) }}" method="POST" onsubmit="return confirm('Voulez-vous suspendre cette police ?');">
                    @csrf
                    <button type="submit" class="btn btn-warning text-white shadow-sm"><i class="bi bi-pause-circle me-2"></i>Suspendre</button>
                </form>
                <form action="{{ route('admin.polices.resiliate', $police) }}" method="POST" onsubmit="return confirm('Attention : Cette action est irréversible. Voulez-vous résilier ce contrat ?');">
                    @csrf
                    <button type="submit" class="btn btn-danger shadow-sm"><i class="bi bi-x-circle me-2"></i>Résilier</button>
                </form>
            @elseif($police->statut === 'suspendu')
                <!-- SUSPENDU -->
                <form action="{{ route('admin.polices.reactivate', $police) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm"><i class="bi bi-play-circle me-2"></i>Réactiver</button>
                </form>
                <form action="{{ route('admin.polices.resiliate', $police) }}" method="POST" onsubmit="return confirm('Attention : Cette action est irréversible. Voulez-vous résilier ce contrat ?');">
                    @csrf
                    <button type="submit" class="btn btn-danger shadow-sm"><i class="bi bi-x-circle me-2"></i>Résilier</button>
                </form>
            @elseif($police->statut === 'resilie')
                
            @endif
            <button type="button" class="btn btn-light border shadow-sm"><i class="bi bi-printer me-2"></i>Imprimer</button>
        </div>
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
                             <span class="fw-bold text-muted">Prime mensuelle</span>
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
                    @if ($police->user->photo_profil)
                        <img src="{{ asset('storage/' . $police->user->photo_profil) }}" alt="Photo" class="rounded-circle" style="width: 80px; height: 80px;">
                    @else
                        {{ substr($police->user->prenom ?? 'U', 0, 1) }}{{ substr($police->user->name ?? 'U', 0, 1) }}
                    @endif
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
            </div>
        </div>
    </div>
</div>
<!-- Modal Planification RDV -->
<div class="modal fade" id="scheduleMeetingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Planifier un rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.polices.schedule', $police) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Sélectionnez la date et l'heure du rendez-vous pour la validation du dossier en agence.</p>
                    <div class="mb-3">
                        <label for="date_rendez_vous" class="form-label">Date et Heure</label>
                        <input type="datetime-local" class="form-control" name="date_rendez_vous" required min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    @if($errors->has('date_rendez_vous'))
                        <div class="text-danger">{{ $errors->first('date_rendez_vous') }}</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer l'invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation JS
        const scheduleForm = document.querySelector('form[action*="schedule"]');
        if (scheduleForm) {
            const dateInput = scheduleForm.querySelector('input[name="date_rendez_vous"]');
            
            scheduleForm.addEventListener('submit', function(e) {
                const selectedDate = new Date(dateInput.value);
                const now = new Date();
                
                // Clear previous errors
                dateInput.classList.remove('is-invalid');
                let existingError = dateInput.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.style.display = 'none';
                }

                if (selectedDate <= now) {
                    e.preventDefault();
                    dateInput.classList.add('is-invalid');
                    
                    if (!existingError) {
                        existingError = document.createElement('div');
                        existingError.className = 'invalid-feedback';
                        dateInput.parentNode.appendChild(existingError);
                    }
                    existingError.textContent = "La date du rendez-vous doit être ultérieure à l'instant présent.";
                    existingError.style.display = 'block';
                } else {
                    // Success / Loading state
                    const btn = scheduleForm.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Traitement...';
                }
            });
        }

        // Success Confirmation (SweetAlert2) for Meeting Schedule
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Invitation envoyée !',
                text: {!! json_encode(session('success')) !!},
                timer: 4000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#fff',
                iconColor: '#10b981'
            });
        @endif
    });
</script>
@endsection
@endsection
