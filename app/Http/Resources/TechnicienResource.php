<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicienResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->prenom . ' ' . $this->nom,
            'specialite' => $this->specialite,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'disponible' => (bool) $this->disponible,
            'tarif_horaire' => (float) $this->tarif_horaire,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'interventions_count' => $this->whenCounted('interventions'),
            'interventions' => $this->whenLoaded('interventions', function () {
                return $this->interventions->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'equipement_id' => $intervention->equipement_id,
                        'date_controle' => $intervention->date_controle?->format('Y-m-d'),
                        'date_maintenance' => $intervention->date_maintenance?->format('Y-m-d'),
                        'statut' => $intervention->statut,
                        'cout' => (float) $intervention->cout,
                    ];
                });
            }),
        ];
    }
}
