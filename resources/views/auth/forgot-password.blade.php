@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 80px);">
    <div class="card border-0 shadow-sm p-4" style="max-width: 700px; width: 100%; border-radius: 12px;">
        <div class="card-body">
            <a href="{{ route('login') }}" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Retour à la connexion
            </a>

            <h5 class="fw-bold mb-2 mt-3">Assur<span class="text-primary">Togo</span></h5>
            <h2 class="fw-bold mb-3">Réinitialiser votre mot de passe</h2>
            <p class="text-muted mb-4">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe</p>

            @if (session('status'))
                <div class="alert alert-success mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-medium">Adresse email</label>
                    <input type="email" name="email" class="form-control py-2 bg-light border-0" placeholder="ebossro@gmail.com" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">Envoyer le lien de réinitialisation</button>
            </form>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted small">Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>
@endsection
