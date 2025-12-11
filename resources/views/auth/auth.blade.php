@extends('layouts.app')

@section('content')
<style>
    /* Sliding Form CSS */
    .auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px); /* Hauteur moins navbar */
        background: #f6f5f7;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 20px;
    }

    .container-auth {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
                    0 10px 10px rgba(0,0,0,0.22);
        position: relative;
        overflow: hidden;
        width: 100%; /* Plus large pour le confort */
        max-width: 100%;
        min-height: 650px; /* Plus haut pour les champs */
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .container-auth.right-panel-active .sign-in-container {
        transform: translateX(100%);
    }

    .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .container-auth.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show 0.6s;
    }

    @keyframes show {
        0%, 49.99% { opacity: 0; z-index: 1; }
        50%, 100% { opacity: 1; z-index: 5; }
    }

    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .container-auth.right-panel-active .overlay-container {
        transform: translateX(-100%);
    }

    .overlay {
        background: #007acc;
        background: -webkit-linear-gradient(to right, #0062a3, #007acc);
        background: linear-gradient(to right, #0062a3, #007acc);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #FFFFFF;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .container-auth.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .overlay-left {
        transform: translateX(-20%);
    }

    .container-auth.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .overlay-right {
        right: 0;
        transform: translateX(0);
    }

    .container-auth.right-panel-active .overlay-right {
        transform: translateX(20%);
    }

    .auth-form {
        background-color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }
    
    .btn-ghost {
        background-color: transparent;
        border-color: #FFFFFF;
        border-radius: 20px;
        border: 1px solid #FFFFFF;
        color: #FFFFFF;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in;
        margin-top: 20px;
    }
    
    .btn-ghost:active { transform: scale(0.95); }
    .btn-ghost:focus { outline: none; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .container-auth {
            min-height: 800px;
            width: 100%;
        }
        .form-container {
            width: 100%;
            position: relative;
            height: auto;
            padding: 20px 0;
        }
        .sign-in-container, .sign-up-container {
            width: 100%;
            position: relative;
            opacity: 1;
            z-index: 1;
            transform: none !important;
            animation: none;
        }
        .overlay-container { display: none; }
        .auth-wrapper { display: block; height: auto; }
    }
</style>

<div class="auth-wrapper">
    <div class="container-auth {{ $action === 'register' ? 'right-panel-active' : '' }}" id="container">
        
        <!-- Sign Up Form (Inscription) -->
        <div class="form-container sign-up-container" style="{{ $action === 'register' ? 'animation: none; opacity: 1; z-index: 5;' : '' }}">
            <form action="{{ route('register.post') }}" method="POST" class="auth-form h-100">
                @csrf
                <h2 class="fw-bold mb-3">Créer un compte</h2>
                <p class="text-muted small mb-3">Utilisez votre email pour vous inscrire</p>
                
                <div class="row g-2 w-100 mb-3">
                    <div class="col-6">
                        <input type="text" name="prenom" class="form-control bg-light border-0 py-2" placeholder="Prénom" required />
                    </div>
                    <div class="col-6">
                        <input type="text" name="nom" class="form-control bg-light border-0 py-2" placeholder="Nom" required />
                    </div>
                </div>
                
                <div class="w-100 mb-3">
                    <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="Email" required />
                </div>
                
                <div class="w-100 mb-3">
                    <input type="tel" name="telephone" class="form-control bg-light border-0 py-2" placeholder="Téléphone (optionnel)" />
                </div>
                
                <div class="w-100 mb-3">
                    <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="Mot de passe" required />
                </div>
                
                <div class="w-100 mb-3">
                    <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-2" placeholder="Confirmer le mot de passe" required />
                </div>

                <div class="form-check mb-3 text-start w-100">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small text-muted" for="terms">
                        J'accepte les conditions d'utilisation
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">S'inscrire</button>
                
                <!-- Mobile only link -->
                <p class="d-block d-md-none mt-3 text-muted">
                    Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </form>
        </div>

        <!-- Sign In Form (Connexion) -->
        <div class="form-container sign-in-container">
            <form action="{{ route('login.post') }}" method="POST" class="auth-form h-100">
                @csrf
                <h2 class="fw-bold mb-3">Se connecter</h2>
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('google.redirect') }}" class="btn btn-outline-light border text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-google"></i></a>
                </div>
                <p class="text-muted small mb-4">ou utilisez votre compte</p>
                
                <div class="w-100 mb-3">
                    <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="Email" required />
                </div>
                <div class="w-100 mb-3">
                    <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="Mot de passe" required />
                </div>
                
                <a href="{{ route('password.request') }}" class="text-muted small mb-4">Mot de passe oublié ?</a>
                
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">Se connecter</button>

                <!-- Mobile only link -->
                <p class="d-block d-md-none mt-3 text-muted">
                    Pas de compte ? <a href="{{ route('register') }}">S'inscrire</a>
                </p>
            </form>
        </div>

        <!-- Overlay Container -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h2 class="fw-bold text-white mb-3">Bon retour !</h2>
                    <p class="text-white mb-4">Accédez à vos contrats, suivez vos remboursements et renouvelez votre assurance en quelques clics.</p>
                    <button class="btn btn-ghost" id="signIn">Se connecter</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h2 class="fw-bold text-white mb-3">Bonjour !</h2>
                    <p class="text-white mb-4">Entrez vos détails personnels et rejoignez des milliers d'assurés satisfaits</p>
                    <ul class="list-unstyled text-start mx-auto d-inline-block">
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Souscription en ligne en quelques minutes</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Suivi en temps réel de vos remboursements</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-3"></i>Support client disponible 7j/7</li>
                    </ul>
                    <button class="btn btn-ghost" id="signUp">Créer un compte</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    if(signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }
</script>
@endsection
