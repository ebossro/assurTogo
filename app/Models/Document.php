<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'police_id',
        'sinistre_id',
        'user_id',
        'nomDocument',
        'typeDocument',
        'cheminDocument',
        'statutDocument',
        'dateTeleversementDocument',
        'tailleDocument',
        'formatDocument',
    ];

    protected $casts = [
        'dateTeleversementDocument' => 'date',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }

    public function sinistre()
    {
        return $this->belongsTo(Sinistre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
