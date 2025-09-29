<?php

namespace App\Http\Controllers\Api;

use App\Models\Intervention;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\InterventionResource;
use App\Http\Resources\InterventionCollection;

class InterventionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $interventions = Intervention::with(['equipement', 'technicien', 'paiements'])
            ->latest()
            ->paginate($perPage);
            
        return $this->sendResponse(
            new InterventionCollection($interventions), 
            'Interventions retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'equipement_id' => 'required|exists:equipements,id',
            'technicien_id' => 'required|exists:techniciens,id',
            'date_controle' => 'nullable|date',
            'date_maintenance' => 'nullable|date|after_or_equal:date_controle',
            'date_retour' => 'nullable|date|after_or_equal:date_maintenance',
            'description_panne' => 'nullable|string',
            'photo_avant' => 'nullable|string',
            'photo_apres' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0',
            'statut' => 'in:en_cours,termine,attente',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $intervention = Intervention::create($input);

        return $this->sendResponse(
            new InterventionResource($intervention->load(['equipement', 'technicien', 'paiements'])), 
            'Intervention created successfully.', 
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $intervention = Intervention::with(['equipement', 'technicien', 'paiements'])->find($id);

        if (is_null($intervention)) {
            return $this->sendError('Intervention not found.');
        }

        return $this->sendResponse(
            new InterventionResource($intervention->load(['equipement', 'technicien', 'paiements'])), 
            'Intervention retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Intervention $intervention)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'equipement_id' => 'sometimes|required|exists:equipements,id',
            'technicien_id' => 'sometimes|required|exists:techniciens,id',
            'date_controle' => 'nullable|date',
            'date_maintenance' => 'nullable|date|after_or_equal:date_controle',
            'date_retour' => 'nullable|date|after_or_equal:date_maintenance',
            'description_panne' => 'nullable|string',
            'photo_avant' => 'nullable|string',
            'photo_apres' => 'nullable|string',
            'cout' => 'nullable|numeric|min:0',
            'statut' => 'sometimes|in:en_cours,termine,attente',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $intervention->update($input);

        return $this->sendResponse(
            new InterventionResource($intervention->load(['equipement', 'technicien', 'paiements'])), 
            'Intervention updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Intervention $intervention)
    {
        $intervention->delete();
        return $this->sendResponse([], 'Intervention deleted successfully.');
    }

    /**
     * Add a payment to the intervention.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addPayment(Request $request, $id)
    {
        $intervention = Intervention::find($id);
        
        if (is_null($intervention)) {
            return $this->sendError('Intervention not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'date' => 'required|date',
            'montant' => 'required|numeric|min:0.01',
            'mode' => 'required|in:especes,virement,cheque,carte',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paiement = Paiement::create($input);
        $intervention->paiements()->attach($paiement->id);

        // Reload the intervention with the new payment
        $intervention->load(['paiements', 'equipement', 'technicien']);

        return $this->sendResponse(
            new InterventionResource($intervention),
            'Payment added to intervention successfully.',
            201
        );
    }
}
