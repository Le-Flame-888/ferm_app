<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipements = Equipement::with('fournisseur')
            ->latest()
            ->paginate(10);
            
        return view('equipements.index', compact('equipements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom_societe')->get();
        return view('equipements.form', [
            'title' => 'Ajouter un équipement',
            'equipement' => new Equipement(),
            'fournisseurs' => $fournisseurs,
            'action' => route('equipements.store'),
            'method' => 'POST'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_ref' => 'required|string|max:255|unique:equipements,code_ref',
            'categorie' => 'required|string|max:255',
            'type_emplacement' => 'nullable|string|max:255',
            'site' => 'required|string|max:255',
            'emplacement' => 'required|string|max:255',
            'lot' => 'required|string|max:50',
            'description' => 'nullable|string',
            'type_huile' => 'nullable|string|max:100',
            'duree_controle' => 'nullable|integer|min:1',
            'ref_courroie' => 'nullable|string|max:100',
            'ref_roulement' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:100',
            'marque' => 'nullable|string|max:100',
            'date_achat' => 'nullable|date',
            'date_mise_service' => 'nullable|date|after_or_equal:date_achat',
            'prix_achat' => 'nullable|numeric|min:0',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('equipements', 'public');
            $validated['photo'] = $path;
        }

        $equipement = Equipement::create($validated);

        return redirect()
            ->route('equipements.show', $equipement->id)
            ->with('success', 'Équipement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipement $equipement)
    {
        $equipement->load('fournisseur');
        return view('equipements.show', compact('equipement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipement $equipement)
    {
        $fournisseurs = Fournisseur::orderBy('nom_societe')->get();
        return view('equipements.form', [
            'title' => 'Modifier l\'équipement ' . $equipement->code_ref,
            'equipement' => $equipement,
            'fournisseurs' => $fournisseurs,
            'action' => route('equipements.update', $equipement->id),
            'method' => 'PUT'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipement $equipement)
    {
        $validated = $request->validate([
            'code_ref' => 'required|string|max:255|unique:equipements,code_ref,' . $equipement->id,
            'categorie' => 'required|string|max:255',
            'type_emplacement' => 'nullable|string|max:255',
            'site' => 'required|string|max:255',
            'emplacement' => 'required|string|max:255',
            'lot' => 'required|string|max:50',
            'description' => 'nullable|string',
            'type_huile' => 'nullable|string|max:100',
            'duree_controle' => 'nullable|integer|min:1',
            'ref_courroie' => 'nullable|string|max:100',
            'ref_roulement' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:100',
            'marque' => 'nullable|string|max:100',
            'date_achat' => 'nullable|date',
            'date_mise_service' => 'nullable|date|after_or_equal:date_achat',
            'prix_achat' => 'nullable|numeric|min:0',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($equipement->photo) {
                Storage::disk('public')->delete($equipement->photo);
            }
            
            $path = $request->file('photo')->store('equipements', 'public');
            $validated['photo'] = $path;
        } elseif ($request->has('remove_photo') && $equipement->photo) {
            // Remove photo if the remove_photo checkbox is checked
            Storage::disk('public')->delete($equipement->photo);
            $validated['photo'] = null;
        }

        $equipement->update($validated);

        return redirect()
            ->route('equipements.show', $equipement->id)
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipement $equipement)
    {
        // Delete photo if exists
        if ($equipement->photo) {
            Storage::disk('public')->delete($equipement->photo);
        }
        
        $equipement->delete();
        
        return redirect()
            ->route('equipements.index')
            ->with('success', 'Équipement supprimé avec succès.');
    }
}
