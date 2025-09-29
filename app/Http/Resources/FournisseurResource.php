<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FournisseurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom_societe' => $this->nom_societe,
            'contact' => $this->contact,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'delai_livraison' => $this->delai_livraison,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'equipements_count' => $this->whenCounted('equipements'),
            'equipements' => $this->whenLoaded('equipements', function () {
                return $this->equipements->map(function ($equipement) {
                    return [
                        'id' => $equipement->id,
                        'code_ref' => $equipement->code_ref,
                        'categorie' => $equipement->categorie,
                        'type_emplacement' => $equipement->type_emplacement,
                        'site' => $equipement->site,
                        'emplacement' => $equipement->emplacement,
                        'marque' => $equipement->marque,
                        'date_achat' => $equipement->date_achat?->format('Y-m-d'),
                        'prix_achat' => (float) $equipement->prix_achat,
                    ];
                });
            }),
        ];
    }
}
