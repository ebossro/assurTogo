<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sinistre extends Model
{
    use HasFactory;
    protected $fillable = [
        'police_id',
        'beneficiaire_id',
        'reference',
        'type_sinistre',
        'lieu_sinistre',
        'ville_pays',
        'date_sinistre',
        'premiere_consultation',
        'gravite',
        'description',
        'diagnostic',
        'medecin_traitant',
        'traitement_prescrit',
        'montant_total',
        'is_declarant_different',
        'declarant_nom',
        'declarant_relation',
        'commentaires',
        'consentement',
        'statut',
    ];

    protected $casts = [
        'date_sinistre' => 'date',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function beneficiaire()
    {
        return $this->belongsTo(Beneficiaire::class);
    }
}
