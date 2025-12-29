@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 80px);">
    <div class="card border-0 shadow-sm p-4" style="max-width: 700px; width: 100%; border-radius: 12px;">
        <div class="card-body">
            <h5 class="fw-bold mb-2 mt-3">Assur<span class="text-primary">Togo</span></h5>
            <h2 class="fw-bold mb-3">Définir un nouveau mot de passe</h2>
            <p class="text-muted mb-4">Veuillez choisir votre nouveau mot de passe.</p>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Adresse email</label>
                    <input type="email" name="email" class="form-control py-2 bg-light border-0" value="{{ $email ?? old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control py-2 bg-light border-0" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control py-2 bg-light border-0" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">Réinitialiser le mot de passe</button>
            </form>
        </div>
    </div>
</div>
@endsection
