@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: 100vh;">
        <!-- Left Side: Form -->
        <div class="col-lg-6 d-flex flex-column justify-content-center px-4 px-lg-5 py-5 bg-white">
            <div class="mx-auto w-100" style="max-width: 500px;">
                <a href="/" class="text-decoration-none text-muted mb-4 d-inline-flex align-items-center">
                    <i class="bi bi-arrow-left me-2"></i> Retour à l'accueil
                </a>
                
                <h2 class="fw-bold mb-2 text-dark">Assur<span class="text-primary">Togo</span></h2>
                <h1 class="fw-bold mb-3">Créez votre compte</h1>
                <p class="text-muted mb-4">Commencez à gérer votre assurance santé dès aujourd'hui</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Prénom</label>
                            <input type="text" name="prenom" class="form-control py-2" placeholder="Emmanuel" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nom</label>
                            <input type="text" name="nom" class="form-control py-2" placeholder="Bossro" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Adresse email</label>
                        <input type="email" name="email" class="form-control py-2" placeholder="ebossro@gmail.com" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Numéro de téléphone</label>
                        <input type="tel" name="telephone" class="form-control py-2" placeholder="+228 XX XX XX XX">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Mot de passe</label>
                        <input type="password" name="password" class="form-control py-2" placeholder="........" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control py-2" placeholder="........" required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label small text-muted" for="terms">
                            J'accepte les <a href="#" class="text-primary text-decoration-none">conditions d'utilisation</a> et la <a href="#" class="text-primary text-decoration-none">politique de confidentialité</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">S'inscrire</button>

                    <p class="text-center small text-muted">
                        Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Se connecter</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right Side: Info (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center bg-primary text-white px-5 text-center position-relative overflow-hidden">
            <div class="position-relative z-1" style="max-width: 500px;">
                <h2 class="display-5 fw-bold mb-4">Rejoignez des milliers d'assurés satisfaits</h2>
                <ul class="list-unstyled text-start mx-auto d-inline-block fs-5">
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Souscription en ligne en quelques minutes</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Suivi en temps réel de vos remboursements</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Support client disponible 7j/7</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
