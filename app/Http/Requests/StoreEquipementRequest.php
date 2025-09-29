<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categorie' => 'required|string|max:50',
            'type_emplacement' => 'required|string|max:50',
            'site' => 'required|string|max:50',
            'emplacement' => 'required|string|max:50',
            'lot' => 'required|string|max:20',
            'code_ref' => 'required|string|max:50|unique:equipements',
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
            'type_huile' => 'nullable|string|max:50',
            'duree_controle' => 'nullable|integer|min:0',
            'ref_courroie' => 'nullable|string|max:50',
            'ref_roulement' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:100',
            'marque' => 'nullable|string|max:50',
            'date_achat' => 'nullable|date',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_mise_service' => 'nullable|date|after_or_equal:date_achat',
            'prix_achat' => 'nullable|numeric|min:0',
        ];
    }
}
