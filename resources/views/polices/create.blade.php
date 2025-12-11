@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8" style="margin-top: 20px;">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark mb-2">Souscription Assurance Santé</h1>
            <p class="text-muted">Complétez les étapes ci-dessous pour finaliser votre adhésion.</p>
        </div>

        <!-- Stepper Navigation -->
        <div class="position-relative mb-5">
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="progressBar"></div>
            </div>
            <div class="d-flex justify-content-between position-absolute top-0 w-100 start-0 translate-middle-y">
                <div class="step-indicator active" data-step="1">1</div>
                <div class="step-indicator" data-step="2">2</div>
                <div class="step-indicator" data-step="3">3</div>
                <div class="step-indicator" data-step="4">4</div>
                <div class="step-indicator" data-step="5">5</div>
            </div>
        </div>

        <form action="{{ route('polices.store') }}" method="POST" enctype="multipart/form-data" id="wizardForm">
            @csrf

            <!-- STEP 1: Identification -->
            <div class="step-content" id="step1">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-primary">Qui êtes-vous ?</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control bg-light border-0" value="{{ old('nom', Auth::user()->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control bg-light border-0" value="{{ old('prenom', Auth::user()->prenom) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Date de naissance <span class="text-danger">*</span></label>
                                <input type="date" name="date_naissance" class="form-control bg-light border-0" value="{{ old('date_naissance') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Sexe <span class="text-danger">*</span></label>
                                <select name="sexe" class="form-select bg-light border-0" required>
                                    <option value="">Choisir...</option>
                                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                             <div class="col-12">
                                <label class="form-label small fw-bold">Photo de profil <span class="text-danger">*</span></label>
                                <input type="file" name="photo_profil" class="form-control bg-light border-0" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Type de pièce <span class="text-danger">*</span></label>
                                <select name="type_piece" class="form-select bg-light border-0" required>
                                    <option value="CNI">CNI</option>
                                    <option value="Passeport">Passeport</option>
                                    <option value="Carte Electeur">Carte Electeur</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Numéro <span class="text-danger">*</span></label>
                                <input type="text" name="numero_piece" class="form-control bg-light border-0" value="{{ old('numero_piece') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Expiration <span class="text-danger">*</span></label>
                                <input type="date" name="date_expiration_piece" class="form-control bg-light border-0" value="{{ old('date_expiration_piece') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Contact & Situation -->
            <div class="step-content d-none" id="step2">
                 <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-primary">Vos Coordonnées & Situation</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" name="telephone" class="form-control bg-light border-0" value="{{ old('telephone', Auth::user()->telephone) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light border-0" value="{{ old('email', Auth::user()->email) }}" required readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Adresse <span class="text-danger">*</span></label>
                                <input type="text" name="adresse" class="form-control bg-light border-0" value="{{ old('adresse') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Ville <span class="text-danger">*</span></label>
                                <input type="text" name="ville" class="form-control bg-light border-0" value="{{ old('ville') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Quartier <span class="text-danger">*</span></label>
                                <input type="text" name="quartier" class="form-control bg-light border-0" value="{{ old('quartier') }}" required>
                            </div>

                            <div class="col-12"><hr></div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Statut Marital <span class="text-danger">*</span></label>
                                <select name="statut_matrimonial" class="form-select bg-light border-0" required>
                                    <option value="celibataire">Célibataire</option>
                                    <option value="marie">Marié(e)</option>
                                    <option value="divorce">Divorcé(e)</option>
                                    <option value="veuf">Veuf/Veuve</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Enfants</label>
                                <input type="number" name="nombre_enfants" class="form-control bg-light border-0" value="{{ old('nombre_enfants', 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Profession <span class="text-danger">*</span></label>
                                <input type="text" name="profession" class="form-control bg-light border-0" value="{{ old('profession') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Employeur</label>
                                <input type="text" name="employeur" class="form-control bg-light border-0" value="{{ old('employeur') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Revenu Mensuel</label>
                                <input type="number" name="revenu_mensuel" class="form-control bg-light border-0" value="{{ old('revenu_mensuel') }}" placeholder="FCFA">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Santé -->
            <div class="step-content d-none" id="step3">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-primary">Profil Médical</h4>
                         <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold">Antécédents médicaux</label>
                                <textarea name="antecedents_medicaux" class="form-control bg-light border-0" rows="2" placeholder="Diabète, hypertension, chirurgies..."></textarea>
                            </div>
                            <div class="col-12">
                                 <label class="form-label small fw-bold">Médicaments actuels</label>
                                <textarea name="medicaments_actuels" class="form-control bg-light border-0" rows="2" placeholder="Traitements en cours..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Allergies</label>
                                <textarea name="allergies" class="form-control bg-light border-0" rows="2" placeholder="Pénicilline, arachides..."></textarea>
                            </div>
                             <div class="col-12">
                                <label class="form-label small fw-bold">Habitudes de vie</label>
                                <textarea name="habitudes_vie" class="form-control bg-light border-0" rows="2" placeholder="Fumeur, alcool, sport..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Formule -->
            <div class="step-content d-none" id="step4">
                 <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-primary">Choisissez votre couverture</h4>
                        
                         <div class="row g-3 mb-4">
                            @foreach($formules as $f)
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="formule" id="formule_{{ $f->nom }}" value="{{ $f->nom }}" {{ old('formule') == $f->nom || ($loop->first && !old('formule')) ? 'checked' : '' }} onchange="updatePrice()">
                                <label class="card h-100 border cursor-pointer formule-card" for="formule_{{ $f->nom }}">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title fw-bold mb-1">{{ $f->nom }}</h5>
                                            <ul class="list-unstyled small mb-0 text-muted">
                                                @if($f->nom == 'Basique') <li>Soins essentiels</li> @endif
                                                @if($f->nom == 'Standard') <li>+ Hospitalisation</li> @endif
                                                @if($f->nom == 'Confort') <li>+ Maternité</li> @endif
                                                @if($f->nom == 'Premium') <li>+ Tout inclus</li> @endif
                                                @if(!in_array($f->nom, ['Basique', 'Standard', 'Confort', 'Premium'])) <li>{{ $f->description }}</li> @endif
                                            </ul>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="text-primary fw-bold mb-0">{{ number_format($f->prix_mensuel, 0, ',', ' ') }}</h5>
                                            <small class="text-muted">FCFA</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Fréquence de paiement <span class="text-danger">*</span></label>
                            <select name="frequence_paiement" class="form-select bg-light border-0" required>
                                <option value="Mensuel">Mensuel</option>
                                <option value="Trimestriel">Trimestriel</option>
                                <option value="Annuel">Annuel</option>
                            </select>
                        </div>

                        <!-- Bénéficiaires -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label small fw-bold mb-0">Bénéficiaires (+5 000 FCFA/pers)</label>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addBeneficiary()"><i class="bi bi-plus"></i> Ajouter</button>
                            </div>
                            <div id="beneficiaries-container"></div>
                        </div>

                         <div class="alert alert-primary border-0 rounded-3 d-flex justify-content-between align-items-center mt-4">
                            <span class="fw-medium">Total estimé (Mensuel) :</span>
                            <span class="h4 fw-bold mb-0"><span id="total-price">15 000</span> FCFA</span>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- STEP 5: Récapitulatif & Confirmation -->
            <div class="step-content d-none" id="step5">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-primary text-center">Récapitulatif de votre demande</h4>
                        
                        <div class="row g-4">
                            <!-- Identité -->
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2">Identité</h6>
                                <p class="mb-1"><strong>Nom & Prénom:</strong> <span id="recap-nom-prenom"></span></p>
                                <p class="mb-1"><strong>Né(e) le:</strong> <span id="recap-naissance"></span> <span id="recap-sexe"></span></p>
                                <p class="mb-1"><strong>Pièce:</strong> <span id="recap-piece"></span> (<span id="recap-numero-piece"></span>)</p>
                            </div>

                            <!-- Contact -->
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2">Contact & Situation</h6>
                                <p class="mb-1"><strong>Tél:</strong> <span id="recap-tel"></span></p>
                                <p class="mb-1"><strong>Email:</strong> <span id="recap-email"></span></p>
                                <p class="mb-1"><strong>Adresse:</strong> <span id="recap-adresse"></span></p>
                                <p class="mb-1"><strong>Situation:</strong> <span id="recap-situation"></span></p>
                            </div>

                            <!-- Santé -->
                            <div class="col-12">
                                <h6 class="fw-bold border-bottom pb-2">Informations Médicales</h6>
                                <p class="mb-1 small text-muted">Avez-vous des antécédents, allergies ou traitements ?</p>
                                <p class="fst-italic" id="recap-sante">Aucun renseigné.</p>
                            </div>

                            <!-- Offre -->
                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3">
                                    <h6 class="fw-bold border-bottom pb-2 mb-3">Offre Sélectionnée</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-5 fw-bold text-primary" id="recap-formule"></span>
                                        <span class="badge bg-primary rounded-pill" id="recap-frequence"></span>
                                    </div>
                                    
                                    <div id="recap-beneficiaires-list" class="mb-3 ps-3 border-start border-3 border-primary">
                                        <!-- Bénéficiaires ajoutés ici -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                        <span class="fw-bold">Montant estimé par période :</span>
                                        <span class="h4 fw-bold text-success mb-0"><span id="recap-total"></span> FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check text-start bg-white border p-3 rounded-3 mt-4">
                            <input class="form-check-input mt-1" type="checkbox" name="consentement_conditions" id="consent" required>
                            <label class="form-check-label ms-2 small" for="consent">
                                Je certifie l'exactitude des informations fournies ci-dessus et j'accepte les conditions générales d'utilisation ainsi que la politique de confidentialité d'AssurTogo.
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4 mb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" id="prevBtn" onclick="nextStep(-1)" disabled>Précédent</button>
                <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" id="nextBtn" onclick="nextStep(1)">Suivant</button>
                <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold d-none" id="submitBtn">Soumettre</button>
            </div>

        </form>
    </div>
</div>

<style>
    .step-indicator {
        width: 35px;
        height: 35px;
        background-color: #fff;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #adb5bd;
        transition: all 0.3s ease;
    }
    .step-indicator.active {
        border-color: var(--bs-primary);
        background-color: var(--bs-primary);
        color: white;
    }
    .cursor-pointer { cursor: pointer; }
    .btn-check:checked + .card {
        border-color: var(--bs-primary) !important;
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
</style>

<script>
    let currentStep = 1;
    const totalSteps = 5;

    function nextStep(n) {
        // Validation simple avant d'avancer
        if (n === 1 && currentStep < totalSteps && !validateStep(currentStep)) return;

        // Hide current step
        document.getElementById('step' + currentStep).classList.add('d-none');
        
        // Update step
        currentStep += n;

        // If entering Step 5, populate Recap
        if (currentStep === 5) {
            updateRecap();
        }
        
        // Show new step
        document.getElementById('step' + currentStep).classList.remove('d-none');
        
        // Update UI
        updateProgressBar();
        updateButtons();
    }

    function validateStep(step) {
        const stepContent = document.getElementById('step' + step);
        const inputs = stepContent.querySelectorAll('input[required], select[required]');
        let valid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                valid = false;
            }
        });
        return valid;
    }

    function updateProgressBar() {
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
        
        document.querySelectorAll('.step-indicator').forEach(indicator => {
            const step = parseInt(indicator.dataset.step);
            if (step <= currentStep) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
    }

    function updateButtons() {
        document.getElementById('prevBtn').disabled = (currentStep === 1);
        
        if (currentStep === totalSteps) {
            document.getElementById('nextBtn').classList.add('d-none');
            document.getElementById('submitBtn').classList.remove('d-none');
        } else {
            document.getElementById('nextBtn').classList.remove('d-none');
            document.getElementById('submitBtn').classList.add('d-none');
        }
    }

    /* Pricing Logic */
    const prices = {!! json_encode($formules->pluck('prix_mensuel', 'nom')) !!};
    const beneficiaryPrice = 5000;

    function updatePrice() {
        const selectedFormulaRadio = document.querySelector('input[name="formule"]:checked');
        if(!selectedFormulaRadio) return; 

        const basePrice = parseFloat(prices[selectedFormulaRadio.value]);
        const beneficiaryCount = document.querySelectorAll('.beneficiary-item').length;
        const total = basePrice + (beneficiaryCount * beneficiaryPrice);
        
        document.getElementById('total-price').innerText = new Intl.NumberFormat('fr-FR').format(total).replace(/\u202f/g, ' ');
    }

    function addBeneficiary() {
        const container = document.getElementById('beneficiaries-container');
        const index = Date.now();
        const html = `
            <div class="beneficiary-item card bg-light border-0 p-3 mb-2" id="ben_${index}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Bénéficiaire</h6>
                    <button type="button" class="btn btn-sm text-danger p-0" onclick="removeBeneficiary('${index}')"><i class="bi bi-x-circle-fill fs-5"></i></button>
                </div>
                <div class="row g-2">
                    <div class="col-6"><input type="text" name="beneficiaires[${index}][nom]" class="form-control form-control-sm border-0" placeholder="Nom" required></div>
                    <div class="col-6"><input type="text" name="beneficiaires[${index}][prenom]" class="form-control form-control-sm border-0" placeholder="Prénom" required></div>
                    <div class="col-4"><input type="date" name="beneficiaires[${index}][date_naissance]" class="form-control form-control-sm border-0" required></div>
                    <div class="col-4">
                        <select name="beneficiaires[${index}][sexe]" class="form-select form-select-sm border-0" required>
                            <option value="">Sexe</option>
                            <option value="masculin">M</option>
                            <option value="feminin">F</option>
                        </select>
                    </div>
                    <div class="col-4"><input type="text" name="beneficiaires[${index}][relation]" class="form-control form-control-sm border-0" placeholder="Lien" required></div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        updatePrice();
    }

    function updateRecap() {
        // Identité
        document.getElementById('recap-nom-prenom').textContent = 
            document.querySelector('[name="nom"]').value + ' ' + document.querySelector('[name="prenom"]').value;
        document.getElementById('recap-naissance').textContent = document.querySelector('[name="date_naissance"]').value;
        const sexe = document.querySelector('[name="sexe"]').value;
        document.getElementById('recap-sexe').textContent = (sexe === 'M' ? '(Masculin)' : (sexe === 'F' ? '(Féminin)' : ''));
        document.getElementById('recap-piece').textContent = document.querySelector('[name="type_piece"]').value;
        document.getElementById('recap-numero-piece').textContent = document.querySelector('[name="numero_piece"]').value;

        // Contact
        document.getElementById('recap-tel').textContent = document.querySelector('[name="telephone"]').value;
        document.getElementById('recap-email').textContent = document.querySelector('[name="email"]').value;
        document.getElementById('recap-adresse').textContent = document.querySelector('[name="adresse"]').value + ', ' + document.querySelector('[name="ville"]').value;
        document.getElementById('recap-situation').textContent = document.querySelector('[name="statut_matrimonial"]').value + 
            (parseInt(document.querySelector('[name="nombre_enfants"]').value) > 0 ? ' (' + document.querySelector('[name="nombre_enfants"]').value + ' enfants)' : '');

        // Santé (Résumé simple)
        const antecedents = document.querySelector('[name="antecedents_medicaux"]').value;
        const medicaments = document.querySelector('[name="medicaments_actuels"]').value;
        const allergies = document.querySelector('[name="allergies"]').value;
        let santeMsg = [];
        if(antecedents) santeMsg.push("Antécédents: Oui");
        if(medicaments) santeMsg.push("Traitements: Oui");
        if(allergies) santeMsg.push("Allergies: Oui");
        document.getElementById('recap-sante').textContent = santeMsg.length > 0 ? santeMsg.join(', ') + " (Voir détails)" : "Aucun renseigné particulièrement.";

        // Offre
        const selectedFormule = document.querySelector('input[name="formule"]:checked');
        if(selectedFormule) {
            document.getElementById('recap-formule').textContent = 'Formule ' + selectedFormule.value;
        }
        document.getElementById('recap-frequence').textContent = document.querySelector('[name="frequence_paiement"]').value;
        
        // Beneficiaires
        const benContainer = document.getElementById('recap-beneficiaires-list');
        benContainer.innerHTML = '';
        const benInputs = document.querySelectorAll('.beneficiary-item');
        if(benInputs.length > 0) {
            benInputs.forEach((item, index) => {
                const nom = item.querySelector(`input[name*="[nom]"]`).value;
                const prenom = item.querySelector(`input[name*="[prenom]"]`).value;
                benContainer.innerHTML += `<div class="small text-muted">+ ${nom} ${prenom}</div>`;
            });
        } else {
            benContainer.innerHTML = '<div class="small text-muted fst-italic">Aucun bénéficiaire ajouté.</div>';
        }

        // Prix Total (Récupéré du calcul déjà fait)
        document.getElementById('recap-total').textContent = document.getElementById('total-price').textContent;
    }

    function removeBeneficiary(id) {
        document.getElementById('ben_' + id).remove();
        updatePrice();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updatePrice();
        // Prevent enter key submission except on last step
        document.getElementById('wizardForm').addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && currentStep < totalSteps && event.target.tagName !== 'TEXTAREA') {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
