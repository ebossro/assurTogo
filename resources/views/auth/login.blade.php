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
                <h1 class="fw-bold mb-3">Bon retour parmi nous</h1>
                <p class="text-muted mb-4">Connectez-vous pour accéder à votre espace personnel</p>

                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Adresse email</label>
                        <input type="email" name="email" class="form-control py-2" placeholder="ebossro@gmail.com" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-medium mb-0">Mot de passe</label>
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small">Mot de passe oublié ?</a>
                        </div>
                        <input type="password" name="password" class="form-control py-2" placeholder="........" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkbox hidden in design but good to keep for functionality if needed later, 
                         or we can assume it's implied/not needed as per mockup -->
                    <!-- 
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Se souvenir de moi</label>
                    </div> 
                    -->

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">Se connecter</button>

                    <p class="text-center small text-muted">
                        Vous n'avez pas de compte ? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">Créer un compte</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right Side: Info (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center bg-primary text-white px-5 text-center position-relative overflow-hidden">
            <div class="position-relative z-1" style="max-width: 500px;">
                <h2 class="display-5 fw-bold mb-4">Gérez votre assurance santé en toute simplicité</h2>
                <p class="fs-5 opacity-75">Accédez à vos contrats, suivez vos remboursements et renouvelez votre assurance en quelques clics.</p>
            </div>
            <!-- Decorative circles -->
            <div class="position-absolute top-0 end-0 translate-middle rounded-circle bg-white opacity-10" style="width: 300px; height: 300px;"></div>
            <div class="position-absolute bottom-0 start-0 translate-middle rounded-circle bg-white opacity-10" style="width: 400px; height: 400px;"></div>
        </div>
    </div>
</div>
@endsection
