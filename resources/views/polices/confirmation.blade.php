@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-lg display-4" style="color: #fff; position: relative; top: -10px;"></i>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold mb-3">Demande reçue avec succès !</h2>
                    
                    <p class="text-muted mb-4 fs-5">
                        Votre demande de souscription a été enregistrée et est en cours de traitement.
                    </p>

                    <div class="alert alert-info border-0 d-inline-block text-start" role="alert">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-3 fs-3"></i>
                            <div>
                                <h5 class="alert-heading fw-bold">Prochaine étape</h5>
                                <p class="mb-0">
                                    Veuillez consulter votre boîte mail dans les <strong>24 prochaines heures</strong>. 
                                    Vous recevrez un lien de paiement sécurisé après la validation de votre dossier par nos administrateurs.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary px-5 py-2">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
