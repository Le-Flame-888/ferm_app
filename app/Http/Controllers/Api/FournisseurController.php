<?php

namespace App\Http\Controllers\Api;

use App\Models\Fournisseur;
use App\Http\Resources\FournisseurResource;
use App\Http\Resources\FournisseurCollection;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class FournisseurController
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $fournisseurs = Fournisseur::withCount('equipements')
            ->latest()
            ->paginate($perPage);

        return $this->successResponse(
            new FournisseurCollection($fournisseurs),
            'Fournisseurs retrieved successfully.'
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_societe' => 'required|string|max:100',
            'contact' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:fournisseurs,email',
            'adresse' => 'nullable|string|max:255',
            'delai_livraison' => 'nullable|integer|min:0',
        ]);

        $fournisseur = Fournisseur::create($validated);

        return $this->createdResponse(
            new FournisseurResource($fournisseur),
            'Fournisseur created successfully.'
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
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Fournisseur $fournisseur)
    {
        return $this->successResponse(
            new FournisseurResource($fournisseur->load('equipements')),
            'Fournisseur retrieved successfully.'
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom_societe' => 'sometimes|required|string|max:100',
            'contact' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:fournisseurs,email,' . $fournisseur->id,
            'adresse' => 'nullable|string|max:255',
            'delai_livraison' => 'nullable|integer|min:0',
        ]);

        $fournisseur->update($validated);

        return $this->updatedResponse(
            new FournisseurResource($fournisseur),
            'Fournisseur updated successfully.'
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
     * @param  \App\Models\Fournisseur  $fournisseur
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return $this->deletedResponse();
    }
}
