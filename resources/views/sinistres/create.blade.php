@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-primary text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-file-medical me-2"></i>Déclaration de Sinistre</h5>
                        <p class="small mb-0 opacity-75">Veuillez remplir le formulaire ci-dessous avec précision pour
                            accélérer le traitement.</p>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('sinistres.store') }}" method="POST" enctype="multipart/form-data"
                            id="sinistreForm">
                            @csrf

                            <!-- 1. Informations sur l'assuré -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">1. Informations sur
                                    l'assuré</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="police_id" class="form-label">Police d'assurance <span
                                                class="text-danger">*</span></label>
                                        <select name="police_id" id="police_id" class="form-select" required
                                            onchange="updateBeneficiaries()">
                                            <option value="{{ $police->id }}" selected
                                                data-beneficiaires='{{ json_encode($police->beneficiaires) }}'>
                                                {{ $police->numeroPolice }} - {{ $police->typePolice }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="beneficiaire_id" class="form-label">Personne concernée <span
                                                class="text-danger">*</span></label>
                                        <select name="beneficiaire_id" id="beneficiaire_id" class="form-select">
                                            <option value="">Moi-même (Assuré principal)</option>
                                            <!-- Populated via JS -->
                                        </select>
                                        <div class="form-text">Si le sinistre concerne un bénéficiaire, sélectionnez-le ici.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Nature du Sinistre -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">2. Nature du
                                    Sinistre</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Type de sinistre <span
                                                class="text-danger">*</span></label>
                                        <select name="type_sinistre" class="form-select" required>
                                            <option value="">Choisir...</option>
                                            <option value="maladie" {{ old('type_sinistre') == 'maladie' ? 'selected' : '' }}>
                                                Maladie</option>
                                            <option value="accident" {{ old('type_sinistre') == 'accident' ? 'selected' : '' }}>Accident</option>
                                            <option value="hospitalisation" {{ old('type_sinistre') == 'hospitalisation' ? 'selected' : '' }}>Hospitalisation</option>
                                            <option value="maternite" {{ old('type_sinistre') == 'maternite' ? 'selected' : '' }}>Maternité</option>
                                            <option value="chirurgie" {{ old('type_sinistre') == 'chirurgie' ? 'selected' : '' }}>Chirurgie</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gravité</label>
                                        <select name="gravite" class="form-select" required>
                                            <option value="leger" {{ old('gravite') == 'leger' ? 'selected' : '' }}>Léger
                                            </option>
                                            <option value="moyen" {{ old('gravite') == 'moyen' ? 'selected' : '' }}>Moyen
                                            </option>
                                            <option value="grave" {{ old('gravite') == 'grave' ? 'selected' : '' }}>Grave
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Date et Lieu -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">3. Date et Lieu</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="date_sinistre" class="form-label">Date de survenance <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_sinistre" name="date_sinistre"
                                            value="{{ old('date_sinistre') }}" required max="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lieu_sinistre" class="form-label">Lieu (Hôpital, Clinique...) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lieu_sinistre" name="lieu_sinistre"
                                            value="{{ old('lieu_sinistre') }}" required placeholder="Ex: CHU Campus">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ville_pays" class="form-label">Ville / Pays <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ville_pays" name="ville_pays"
                                            value="{{ old('ville_pays') }}" required placeholder="Ex: Lomé, Togo">
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Détails Médicaux -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">4. Détails Médicaux
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Première consultation pour ce motif ?</label>
                                    <div class="form-check form-check-inline ms-3">
                                        <input class="form-check-input" type="radio" name="premiere_consultation"
                                            id="cons_oui" value="1" {{ old('premiere_consultation', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cons_oui">Oui</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="premiere_consultation"
                                            id="cons_non" value="0" {{ old('premiere_consultation') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cons_non">Non</label>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="medecin_traitant" class="form-label">Médecin Traitant</label>
                                        <input type="text" class="form-control" id="medecin_traitant"
                                            name="medecin_traitant" value="{{ old('medecin_traitant') }}"
                                            placeholder="Dr. Nom Prénom">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="diagnostic" class="form-label">Diagnostic (si connu)</label>
                                        <input type="text" class="form-control" id="diagnostic" name="diagnostic"
                                            value="{{ old('diagnostic') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description / Symptômes <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required
                                        placeholder="Décrivez les symptômes ou les circonstances...">{{ old('description') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="traitement_prescrit" class="form-label">Traitement Prescrit</label>
                                    <textarea class="form-control" id="traitement_prescrit" name="traitement_prescrit"
                                        rows="2">{{ old('traitement_prescrit') }}</textarea>
                                </div>
                            </div>

                            <!-- 5. Détails Financiers -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">5. Détails
                                    Financiers</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="montant_total" class="form-label">Montant Total des frais (FCFA) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="montant_total" name="montant_total"
                                            value="{{ old('montant_total') }}" required min="0">
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>
                            </div>

                            <!-- 6. Pièces Justificatives -->
                            <div class="section mb-4">
                                <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">6. Pièces
                                    Justificatives</h6>
                                <p class="text-muted small">Veuillez joindre tous les documents nécessaires (Factures,
                                    Ordonnances, Résultats...). Formats: PDF, JPG, PNG.</p>

                                <div id="documents-container">
                                    <div class="document-row row g-3 mb-2 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label small">Type de document</label>
                                            <select name="documents[0][type]" class="form-select form-select-sm" required>
                                                <option value="certificat_medical">Certificat Médical</option>
                                                <option value="facture">Facture de soins</option>
                                                <option value="ordonnance">Ordonnance</option>
                                                <option value="examen">Résultat d'examen</option>
                                                <option value="rapport_medical">Rapport Médical</option>
                                                <option value="identite">Pièce d'identité</option>
                                                <option value="autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label small">Fichier</label>
                                            <input type="file" name="documents[0][file]"
                                                class="form-control form-control-sm" required accept=".pdf,.jpg,.jpeg,.png"
                                                onchange="validateFileSize(this)">
                                            <div class="invalid-feedback">Le fichier ne doit pas dépasser 5 Mo.</div>
                                        </div>
                                        <!-- No delete button for the first row -->
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                    onclick="addDocumentRow()">
                                    <i class="bi bi-plus-circle me-1"></i> Ajouter un document
                                </button>
                            </div>

                            <!-- 7. Informations sur le déclarant -->
                            <div class="section mb-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_declarant_different"
                                        name="is_declarant_different" value="1" {{ old('is_declarant_different') ? 'checked' : '' }} onchange="toggleDeclarant()">
                                    <label class="form-check-label fw-bold" for="is_declarant_different">
                                        Je déclare ce sinistre pour quelqu'un d'autre (je ne suis pas l'assuré)
                                    </label>
                                </div>
                                <div id="declarant-fields" class="row g-3" style="display: none;">
                                    <div class="col-md-6">
                                        <label for="declarant_nom" class="form-label">Nom complet du déclarant</label>
                                        <input type="text" class="form-control" id="declarant_nom" name="declarant_nom"
                                            value="{{ old('declarant_nom') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="declarant_relation" class="form-label">Relation avec l'assuré</label>
                                        <input type="text" class="form-control" id="declarant_relation"
                                            name="declarant_relation" value="{{ old('declarant_relation') }}"
                                            placeholder="Ex: Parent, Conjoint...">
                                    </div>
                                </div>
                            </div>

                            <!-- 8. Commentaires & Consentement -->
                            <div class="section mb-4">
                                <div class="mb-3">
                                    <label for="commentaires" class="form-label">Commentaires supplémentaires
                                        (Optionnel)</label>
                                    <textarea class="form-control" id="commentaires" name="commentaires"
                                        rows="2">{{ old('commentaires') }}</textarea>
                                </div>
                                <div class="form-check bg-light p-3 rounded border">
                                    <input class="form-check-input" type="checkbox" name="consentement" id="consentement"
                                        required>
                                    <label class="form-check-label" for="consentement">
                                        Je certifie que les informations fournies sont exactes et j'autorise la compagnie à
                                        effectuer les vérifications nécessaires. <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('sinistres.index') }}" class="btn btn-light border px-4">Annuler</a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold">Soumettre la déclaration</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateBeneficiaries() {
            const policeSelect = document.getElementById('police_id');
            const beneficiarySelect = document.getElementById('beneficiaire_id');
            const selectedOption = policeSelect.options[policeSelect.selectedIndex];

            // Clear current beneficiaries
            beneficiarySelect.innerHTML = '<option value="">Moi-même (Assuré principal)</option>';

            if (selectedOption && selectedOption.dataset.beneficiaires) {
                const beneficiaries = JSON.parse(selectedOption.dataset.beneficiaires);
                beneficiaries.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.id;
                    option.textContent = b.prenomBeneficiaire + ' ' + b.nomBeneficiaire + ' (' + (b.relationBeneficiaire || 'Autre') + ')';
                    beneficiarySelect.appendChild(option);
                });
            }
        }

        function toggleDeclarant() {
            const checkbox = document.getElementById('is_declarant_different');
            const fields = document.getElementById('declarant-fields');
            fields.style.display = checkbox.checked ? 'flex' : 'none';

            const inputs = fields.querySelectorAll('input');
            inputs.forEach(input => input.required = checkbox.checked);
        }

        let docIndex = 1;
        // Updated addDocumentRow to include validation
        function addDocumentRow() {
            const container = document.getElementById('documents-container');
            const row = document.createElement('div');
            row.className = 'document-row row g-3 mb-2 align-items-end';
            row.innerHTML = `
                <div class="col-md-4">
                    <select name="documents[${docIndex}][type]" class="form-select form-select-sm" required>
                        <option value="certificat_medical">Certificat Médical</option>
                        <option value="facture">Facture de soins</option>
                        <option value="ordonnance">Ordonnance</option>
                        <option value="examen">Résultat d'examen</option>
                        <option value="rapport_medical">Rapport Médical</option>
                        <option value="identite">Pièce d'identité</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <input type="file" name="documents[${docIndex}][file]" class="form-control form-control-sm" required accept=".pdf,.jpg,.jpeg,.png" onchange="validateFileSize(this)">
                    <div class="invalid-feedback">Le fichier ne doit pas dépasser 5 Mo.</div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.document-row').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            docIndex++;
        }

        // File validation function
        function validateFileSize(input) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (input.files && input.files[0]) {
                if (input.files[0].size > maxSize) {
                    input.classList.add('is-invalid');
                    input.value = ''; // Clear the input
                    alert('Le fichier est trop volumineux. La taille maximale autorisée est de 5 Mo.');
                } else {
                    input.classList.remove('is-invalid');
                }
            }
        }

        // Bind validation to initial file input
        document.addEventListener('DOMContentLoaded', function () {
            toggleDeclarant();
            updateBeneficiaries();

            // Attach validation to existing inputs
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function () {
                    validateFileSize(this);
                });
            });
        });
    </script>
@endsection