<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technicien extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'email',
        'adresse',
        'disponible',
        'tarif_horaire',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'tarif_horaire' => 'decimal:2',
    ];

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
}
