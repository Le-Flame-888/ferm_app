<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipement extends Model
{
    use HasFactory;
    protected $fillable = [
        'categorie',
        'type_emplacement',
        'site',
        'emplacement',
        'lot',
        'code_ref',
        'photo',
        'description',
        'type_huile',
        'duree_controle',
        'ref_courroie',
        'ref_roulement',
        'numero_serie',
        'marque',
        'date_achat',
        'fournisseur_id',
        'date_mise_service',
        'prix_achat',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'date_mise_service' => 'date',
        'duree_controle' => 'integer',
        'prix_achat' => 'decimal:2',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
}
