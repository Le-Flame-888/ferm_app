<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
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
            'intervention_id' => $this->intervention_id,
            'date' => $this->date->format('Y-m-d'),
            'montant' => (float) $this->montant,
            'mode' => $this->mode,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'intervention' => $this->whenLoaded('intervention', function () {
                return [
                    'id' => $this->intervention->id,
                    'date_maintenance' => $this->intervention->date_maintenance?->format('Y-m-d'),
                    'description_panne' => $this->intervention->description_panne,
                    'statut' => $this->intervention->statut,
                    'equipement' => $this->intervention->equipement ? [
                        'id' => $this->intervention->equipement->id,
                        'code_ref' => $this->intervention->equipement->code_ref,
                        'categorie' => $this->intervention->equipement->categorie,
                        'site' => $this->intervention->equipement->site,
                    ] : null,
                ];
            }),
        ];
    }
}
