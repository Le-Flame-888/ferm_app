<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intervention extends Model
{
    protected $fillable = [
        'equipement_id',
        'technicien_id',
        'date_controle',
        'date_maintenance',
        'date_retour',
        'description_panne',
        'photo_avant',
        'photo_apres',
        'cout',
        'statut',
    ];

    protected $casts = [
        'date_controle' => 'date',
        'date_maintenance' => 'date',
        'date_retour' => 'date',
        'cout' => 'decimal:2',
    ];

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipement::class);
    }

    public function technicien(): BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }

    public function paiements(): BelongsToMany
    {
        return $this->belongsToMany(Paiement::class, 'intervention_paiement')
            ->withTimestamps();
    }
}
