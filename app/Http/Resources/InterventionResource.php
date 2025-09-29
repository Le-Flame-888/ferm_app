<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterventionResource extends JsonResource
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
            'equipement_id' => $this->equipement_id,
            'technicien_id' => $this->technicien_id,
            'date_controle' => $this->date_controle?->format('Y-m-d'),
            'date_maintenance' => $this->date_maintenance?->format('Y-m-d'),
            'date_retour' => $this->date_retour?->format('Y-m-d'),
            'description_panne' => $this->description_panne,
            'photo_avant' => $this->photo_avant ? asset('storage/' . $this->photo_avant) : null,
            'photo_apres' => $this->photo_apres ? asset('storage/' . $this->photo_apres) : null,
            'cout' => (float) $this->cout,
            'statut' => $this->statut,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'equipement' => $this->whenLoaded('equipement', function () {
                return [
                    'id' => $this->equipement->id,
                    'code_ref' => $this->equipement->code_ref,
                    'categorie' => $this->equipement->categorie,
                    'site' => $this->equipement->site,
                    'emplacement' => $this->equipement->emplacement,
                ];
            }),
            'technicien' => $this->whenLoaded('technicien', function () {
                return [
                    'id' => $this->technicien->id,
                    'nom_complet' => $this->technicien->prenom . ' ' . $this->technicien->nom,
                    'specialite' => $this->technicien->specialite,
                    'telephone' => $this->technicien->telephone,
                ];
            }),
            'paiements' => $this->whenLoaded('paiements', function () {
                return $this->paiements->map(function ($paiement) {
                    return [
                        'id' => $paiement->id,
                        'date' => $paiement->date->format('Y-m-d'),
                        'montant' => (float) $paiement->montant,
                        'mode' => $paiement->mode,
                    ];
                });
            }),
        ];
    }
}
