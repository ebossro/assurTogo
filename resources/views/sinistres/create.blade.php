@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Déclarer un sinistre</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('sinistres.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="police_id" class="form-label">Police concernée</label>
                            <select name="police_id" id="police_id" class="form-select @error('police_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez une police</option>
                                @foreach($polices as $police)
                                    <option value="{{ $police->id }}" {{ old('police_id') == $police->id ? 'selected' : '' }}>
                                        {{ $police->numeroPolice }} - {{ $police->typePolice }}
                                    </option>
                                @endforeach
                            </select>
                            @error('police_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_sinistre" class="form-label">Date du sinistre</label>
                            <input type="date" class="form-control @error('date_sinistre') is-invalid @enderror" id="date_sinistre" name="date_sinistre" value="{{ old('date_sinistre') }}" required max="{{ date('Y-m-d') }}">
                            @error('date_sinistre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="montant_estime" class="form-label">Montant estimé (FCFA)</label>
                            <input type="number" class="form-control @error('montant_estime') is-invalid @enderror" id="montant_estime" name="montant_estime" value="{{ old('montant_estime') }}" required min="0">
                            @error('montant_estime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proof_file" class="form-label">Preuve du sinistre (Image ou PDF)</label>
                            <input type="file" class="form-control @error('proof_file') is-invalid @enderror" id="proof_file" name="fichier_preuve" required accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Formats acceptés : JPG, PNG, PDF. Max 2Mo.</div>
                            @error('proof_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description du sinistre</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required minlength="10" placeholder="Décrivez les circonstances du sinistre...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sinistres.index') }}" class="btn btn-light border">Annuler</a>
                            <button type="submit" class="btn btn-primary px-4">Soumettre la déclaration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
