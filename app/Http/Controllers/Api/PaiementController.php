<?php

namespace App\Http\Controllers\Api;

use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\PaiementCollection;

class PaiementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $paiements = Paiement::with(['interventions.equipement', 'interventions.technicien'])
            ->latest()
            ->paginate($perPage);
            
        return $this->sendResponse(
            new PaiementCollection($paiements), 
            'Paiements retrieved successfully.'
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
            'date' => 'required|date',
            'montant' => 'required|numeric|min:0.01',
            'mode' => 'required|in:especes,virement,cheque,carte',
            'intervention_ids' => 'required|array',
            'intervention_ids.*' => 'exists:interventions,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paiement = Paiement::create([
            'date' => $input['date'],
            'montant' => $input['montant'],
            'mode' => $input['mode'],
        ]);

        $paiement->interventions()->attach($input['intervention_ids']);

        return $this->sendResponse(
            new PaiementResource($paiement->load(['interventions.equipement', 'interventions.technicien'])), 
            'Paiement created successfully.', 
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
        $paiement = Paiement::with('interventions.equipement', 'interventions.technicien')->find($id);

        if (is_null($paiement)) {
            return $this->sendError('Paiement not found.');
        }

        return $this->sendResponse(
            new PaiementResource($paiement->load(['interventions.equipement', 'interventions.technicien'])), 
            'Paiement retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paiement $paiement)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'date' => 'sometimes|required|date',
            'montant' => 'sometimes|required|numeric|min:0.01',
            'mode' => 'sometimes|required|in:especes,virement,cheque,carte',
            'intervention_ids' => 'sometimes|array',
            'intervention_ids.*' => 'exists:interventions,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paiement->update([
            'date' => $input['date'] ?? $paiement->date,
            'montant' => $input['montant'] ?? $paiement->montant,
            'mode' => $input['mode'] ?? $paiement->mode,
        ]);

        if (isset($input['intervention_ids'])) {
            $paiement->interventions()->sync($input['intervention_ids']);
        }

        return $this->sendResponse(
            new PaiementResource($paiement->load(['interventions.equipement', 'interventions.technicien'])), 
            'Paiement updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paiement $paiement)
    {
        $paiement->interventions()->detach();
        $paiement->delete();
        return $this->sendResponse([], 'Paiement deleted successfully.');
    }
}
