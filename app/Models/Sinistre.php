<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sinistre extends Model
{
    protected $fillable = [
        'police_id',
        'reference',
        'description',
        'date_sinistre',
        'montant_estime',
        'statut',
        'fichier_preuve',
    ];

    protected $casts = [
        'date_sinistre' => 'date',
        'montant_estime' => 'decimal:2',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
