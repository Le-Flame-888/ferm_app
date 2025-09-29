<?php

namespace App\Http\Controllers\Api;

use App\Models\Technicien;
use App\Http\Resources\TechnicienResource;
use App\Http\Resources\TechnicienCollection;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class TechnicienController
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
        $techniciens = Technicien::withCount('interventions')
            ->latest()
            ->paginate($perPage);

        return $this->successResponse(
            new TechnicienCollection($techniciens),
            'Techniciens retrieved successfully.'
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
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'specialite' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:techniciens,email',
            'adresse' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'tarif_horaire' => 'nullable|numeric|min:0',
        ]);

        $technicien = Technicien::create($validated);

        return $this->createdResponse(
            new TechnicienResource($technicien),
            'Technicien created successfully.'
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
     * @param  \App\Models\Technicien  $technicien
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Technicien $technicien)
    {
        return $this->successResponse(
            new TechnicienResource($technicien->load('interventions.equipement')),
            'Technicien retrieved successfully.'
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
     * @param  \App\Models\Technicien  $technicien
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Technicien $technicien)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'specialite' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:techniciens,email,' . $technicien->id,
            'adresse' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'tarif_horaire' => 'nullable|numeric|min:0',
        ]);

        $technicien->update($validated);

        return $this->updatedResponse(
            new TechnicienResource($technicien),
            'Technicien updated successfully.'
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
     * @param  \App\Models\Technicien  $technicien
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Technicien $technicien)
    {
        $technicien->delete();
        return $this->deletedResponse();
    }
}
