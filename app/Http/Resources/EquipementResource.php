<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipementResource extends JsonResource
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
            'categorie' => $this->categorie,
            'type_emplacement' => $this->type_emplacement,
            'site' => $this->site,
            'emplacement' => $this->emplacement,
            'lot' => $this->lot,
            'code_ref' => $this->code_ref,
            'photo' => $this->photo ? asset('storage/' . $this->photo) : null,
            'description' => $this->description,
            'type_huile' => $this->type_huile,
            'duree_controle' => $this->duree_controle,
            'ref_courroie' => $this->ref_courroie,
            'ref_roulement' => $this->ref_roulement,
            'numero_serie' => $this->numero_serie,
            'marque' => $this->marque,
            'date_achat' => $this->date_achat?->format('Y-m-d'),
            'date_mise_service' => $this->date_mise_service?->format('Y-m-d'),
            'prix_achat' => (float) $this->prix_achat,
            'fournisseur' => $this->whenLoaded('fournisseur', function () {
                return [
                    'id' => $this->fournisseur->id,
                    'nom_societe' => $this->fournisseur->nom_societe,
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
