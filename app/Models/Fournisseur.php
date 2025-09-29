<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_societe',
        'contact',
        'telephone',
        'email',
        'adresse',
        'delai_livraison',
    ];

    protected $casts = [
        'delai_livraison' => 'integer',
    ];

    public function equipements(): HasMany
    {
        return $this->hasMany(Equipement::class);
    }
}
