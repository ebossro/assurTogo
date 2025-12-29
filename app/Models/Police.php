<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Police extends Model
{
    use HasFactory;
    protected $table = 'police'; 

    protected $fillable = [
        'user_id',
        'numeroPolice',
        'typePolice',
        'formule_id',
        'couverture',
        'dateDebut',
        'dateFin',
        'primeMensuelle',
        'statut',
        'date_rendez_vous',
        'antecedents_medicaux',
        'medicaments_actuels',
        'allergies',
        'habitudes_vie',
    ];

    protected $casts = [
        'date_rendez_vous' => 'datetime',
        'dateDebut' => 'date',
        'dateFin' => 'date',
        'primeMensuelle' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formule()
    {
        return $this->belongsTo(Formule::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function beneficiaires()
    {
        return $this->hasMany(Beneficiaire::class);
    }

    public function sinistres()
    {
        return $this->hasMany(Sinistre::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
