<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'police_id',
        'montant',
        'reference',
        'type_paiement',
        'mode_paiement',
        'date_paiement',
        'date_echeance',
        'statut',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'date_echeance' => 'date',
        'montant' => 'decimal:2',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }
}
