<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiaire extends Model
{
    protected $fillable = [
        'police_id',
        'nomBeneficiaire',
        'prenomBeneficiaire',
        'relationBeneficiaire',
        'dateNaissanceBeneficiaire',
        'telephoneBeneficiaire',
        'genreBeneficiaire',
        'statutBeneficiaire',
    ];

    protected $casts = [
        'dateNaissanceBeneficiaire' => 'date',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }
}
