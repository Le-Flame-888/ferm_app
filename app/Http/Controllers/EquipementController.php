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
        'categorie' => 'required|string',
        'type_emplacement' => 'required|string',
        'site' => 'required|string',
        'emplacement' => 'required|string',
        'lot' => 'required|string',
        'photo' => 'nullable|image|max:5120', // 5MB max
        'description' => 'nullable|string',
        'type_huile' => 'nullable|string',
        'duree_controle' => 'nullable|integer',
        'ref_courroie' => 'nullable|string',
        'ref_roulement' => 'nullable|string',
        'numero_serie' => 'nullable|string',
        'marque' => 'nullable|string',
        'date_achat' => 'nullable|date',
        'fournisseur_id' => 'nullable|exists:fournisseurs,id',
        'date_mise_service' => 'nullable|date',
        'prix_achat' => 'nullable|numeric',
    ]);

    // Générer automatiquement le code_ref
    $validated['code_ref'] = $this->genererCodeRef(
        $validated['categorie'],
        $validated['site'],
        $validated['emplacement']
    );

    // Gérer l'upload de la photo
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('equipements', 'public');
    }

    $equipement = Equipement::create($validated);

    return redirect()->route('equipements.index')
        ->with('success', 'Équipement créé avec succès. Code: ' . $equipement->code_ref);
}

/**
 * Générer un code de référence unique
 * Format: {CATEGORIE_ABREGE}-{SITE_ABREGE}-{EMPLACEMENT_ABREGE}-{NUMERO}
 */
    private function genererCodeRef($categorie, $site, $emplacement)
    {
        // Abréviations de catégorie
        $categorieMap = [
            'Moteur' => 'MOT',
            'Réducteur' => 'RED',
            'Moteur Réducteur' => 'MOTRED',
            'Moteur immergé' => 'MOTIMM',
            'Pompe immergée' => 'POMPIMM',
            'Pompe Fosse' => 'POMPFOS',
            'Pompe Calpeda' => 'POMPCAL',
        ];

        // Abréviations de site
        $siteMap = [
            'Ain Aouda Ps' => 'AAP',
            'Ain Aouda Pn' => 'AAN',
            'Zhiliga' => 'ZHI',
            'Khemisset' => 'KHE',
        ];

        // Abréviations d'emplacement
        $emplacementMap = [
            'Batiment' => 'BAT',
            'Magasin' => 'MAG',
            'Chateau' => 'CHA',
            'Bassin' => 'BAS',
            'Usine' => 'USI',
        ];

        $catAbrege = $categorieMap[$categorie] ?? strtoupper(substr($categorie, 0, 3));
        $siteAbrege = $siteMap[$site] ?? strtoupper(substr($site, 0, 3));
        $empAbrege = $emplacementMap[$emplacement] ?? strtoupper(substr($emplacement, 0, 3));

        // Trouver le dernier numéro pour ce type de code
        $prefix = "{$catAbrege}-{$siteAbrege}-{$empAbrege}";
        $lastEquipement = Equipement::where('code_ref', 'LIKE', "{$prefix}-%")
            ->orderBy('code_ref', 'desc')
            ->first();

        if ($lastEquipement) {
            // Extraire le numéro du dernier code
            $lastNumber = (int) substr($lastEquipement->code_ref, strrpos($lastEquipement->code_ref, '-') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: MOT-AAP-BAT-001
        return sprintf("%s-%03d", $prefix, $newNumber);
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
            'categorie' => 'required|string',
            'type_emplacement' => 'required|string',
            'site' => 'required|string',
            'emplacement' => 'required|string',
            'lot' => 'required|string',
            'photo' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'type_huile' => 'nullable|string',
            'duree_controle' => 'nullable|integer',
            'ref_courroie' => 'nullable|string',
            'ref_roulement' => 'nullable|string',
            'numero_serie' => 'nullable|string',
            'marque' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_mise_service' => 'nullable|date',
            'prix_achat' => 'nullable|numeric',
        ]);
    
        // Si catégorie, site ou emplacement changent, régénérer le code_ref
        if ($equipement->categorie !== $validated['categorie'] || 
            $equipement->site !== $validated['site'] || 
            $equipement->emplacement !== $validated['emplacement']) {
            
            $validated['code_ref'] = $this->genererCodeRef(
                $validated['categorie'],
                $validated['site'],
                $validated['emplacement']
            );
        }
    
        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($equipement->photo) {
                Storage::disk('public')->delete($equipement->photo);
            }
            $validated['photo'] = $request->file('photo')->store('equipements', 'public');
        }
    
        $equipement->update($validated);
    
        return redirect()->route('equipements.index')
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
