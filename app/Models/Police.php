<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Police extends Model
{
    protected $table = 'police'; // Nom de la table au singulier dans la migration
    
    protected $fillable = [
        'user_id',
        'numeroPolice',
        'typePolice',
        'formule_id',
        'couverture',
        'dateDebut',
        'dateFin',
        'primeMensuelle',
        'frequence_paiement',
        'statut',
        'etat',
        // Infos médicales
        'antecedents_medicaux',
        'medicaments_actuels',
        'allergies',
        'habitudes_vie',
    ];

    protected $casts = [
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
