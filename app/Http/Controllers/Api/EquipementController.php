<?php

namespace App\Http\Controllers\Api;

use App\Models\Equipement;
use App\Http\Resources\EquipementResource;
use App\Http\Resources\EquipementCollection;
use App\Http\Requests\StoreEquipementRequest;
use App\Http\Requests\UpdateEquipementRequest;
use App\Services\ReferenceGeneratorService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class EquipementController
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a paginated listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $equipements = Equipement::with('fournisseur')
            ->latest()
            ->paginate($perPage);

        return $this->successResponse(
            new EquipementCollection($equipements),
            'Equipements retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEquipementRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEquipementRequest $request)
    {
        $validated = $request->validated();
        
        // Generate reference code if not provided
        if (empty($validated['code_ref'])) {
            $validated['code_ref'] = ReferenceGeneratorService::generateEquipementReference(
                $validated['categorie'],
                $validated['site']
            );
        }

        $equipement = Equipement::create($validated);

        return $this->createdResponse(
            new EquipementResource($equipement->load('fournisseur')),
            'Equipement created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipement  $equipement
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Equipement $equipement)
    {
        return $this->successResponse(
            new EquipementResource($equipement->load('fournisseur', 'interventions.technicien')),
            'Equipement retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEquipementRequest  $request
     * @param  \App\Models\Equipement  $equipement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEquipementRequest $request, Equipement $equipement)
    {
        $validated = $request->validated();
        
        // Generate reference code if not provided and category or site has changed
        if (empty($validated['code_ref']) && 
            ($validated['categorie'] !== $equipement->categorie || $validated['site'] !== $equipement->site)) {
            $validated['code_ref'] = ReferenceGeneratorService::generateEquipementReference(
                $validated['categorie'],
                $validated['site']
            );
        }

        $equipement->update($validated);

        return $this->updatedResponse(
            new EquipementResource($equipement->load('fournisseur')),
            'Equipement updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipement  $equipement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Equipement $equipement)
    {
        $equipement->delete();
        return $this->deletedResponse();
    }
}
