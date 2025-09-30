@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title }}</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
                @csrf
                @if(isset($method) && in_array($method, ['PUT', 'PATCH']))
                    @method($method)
                @endif

                <!-- Type d'Équipement -->
                <div class="mb-3">
                    <label for="categorie" class="form-label">Équipement *</label>
                    <select class="form-select @error('categorie') is-invalid @enderror" 
                            id="categorie" name="categorie" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Moteur" {{ old('categorie', $equipement->categorie ?? '') == 'Moteur' ? 'selected' : '' }}>Moteur</option>
                        <option value="Réducteur" {{ old('categorie', $equipement->categorie ?? '') == 'Réducteur' ? 'selected' : '' }}>Réducteur</option>
                        <option value="Moteur Réducteur" {{ old('categorie', $equipement->categorie ?? '') == 'Moteur Réducteur' ? 'selected' : '' }}>Moteur Réducteur</option>
                        <option value="Moteur immergé" {{ old('categorie', $equipement->categorie ?? '') == 'Moteur immergé' ? 'selected' : '' }}>Moteur immergé</option>
                        <option value="Pompe immergée" {{ old('categorie', $equipement->categorie ?? '') == 'Pompe immergée' ? 'selected' : '' }}>Pompe immergée</option>
                        <option value="Pompe Fosse" {{ old('categorie', $equipement->categorie ?? '') == 'Pompe Fosse' ? 'selected' : '' }}>Pompe Fosse</option>
                        <option value="Pompe Calpeda" {{ old('categorie', $equipement->categorie ?? '') == 'Pompe Calpeda' ? 'selected' : '' }}>Pompe Calpeda</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Emplacement Section -->
                <h5 class="mb-3">Emplacement</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="type_emplacement" class="form-label">Type *</label>
                        <select class="form-select @error('type_emplacement') is-invalid @enderror" 
                                id="type_emplacement" name="type_emplacement" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Extracteur" {{ old('type_emplacement', $equipement->type_emplacement ?? '') == 'Extracteur' ? 'selected' : '' }}>Extracteur</option>
                            <option value="Tapis Fiente" {{ old('type_emplacement', $equipement->type_emplacement ?? '') == 'Tapis Fiente' ? 'selected' : '' }}>Tapis Fiente</option>
                            <option value="Tapis Ramassage Oeuf" {{ old('type_emplacement', $equipement->type_emplacement ?? '') == 'Tapis Ramassage Oeuf' ? 'selected' : '' }}>Tapis Ramassage Oeuf</option>
                            <option value="Chariot Aliment" {{ old('type_emplacement', $equipement->type_emplacement ?? '') == 'Chariot Aliment' ? 'selected' : '' }}>Chariot Aliment</option>
                            <option value="Trap" {{ old('type_emplacement', $equipement->type_emplacement ?? '') == 'Trap' ? 'selected' : '' }}>Trap</option>
                        </select>
                        @error('type_emplacement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="site" class="form-label">Site *</label>
                        <select class="form-select @error('site') is-invalid @enderror" 
                                id="site" name="site" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Ain Aouda Ps" {{ old('site', $equipement->site ?? '') == 'Ain Aouda Ps' ? 'selected' : '' }}>Ain Aouda Ps</option>
                            <option value="Ain Aouda Pn" {{ old('site', $equipement->site ?? '') == 'Ain Aouda Pn' ? 'selected' : '' }}>Ain Aouda Pn</option>
                            <option value="Zhiliga" {{ old('site', $equipement->site ?? '') == 'Zhiliga' ? 'selected' : '' }}>Zhiliga</option>
                            <option value="Khemisset" {{ old('site', $equipement->site ?? '') == 'Khemisset' ? 'selected' : '' }}>Khemisset</option>
                        </select>
                        @error('site')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="emplacement" class="form-label">Emplacement *</label>
                        <select class="form-select @error('emplacement') is-invalid @enderror" 
                                id="emplacement" name="emplacement" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Batiment" {{ old('emplacement', $equipement->emplacement ?? '') == 'Batiment' ? 'selected' : '' }}>Batiment</option>
                            <option value="Magasin" {{ old('emplacement', $equipement->emplacement ?? '') == 'Magasin' ? 'selected' : '' }}>Magasin</option>
                            <option value="Chateau" {{ old('emplacement', $equipement->emplacement ?? '') == 'Chateau' ? 'selected' : '' }}>Chateau</option>
                            <option value="Bassin" {{ old('emplacement', $equipement->emplacement ?? '') == 'Bassin' ? 'selected' : '' }}>Bassin</option>
                            <option value="Usine" {{ old('emplacement', $equipement->emplacement ?? '') == 'Usine' ? 'selected' : '' }}>Usine</option>
                        </select>
                        @error('emplacement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="lot" class="form-label">Lot *</label>
                        <input type="text" class="form-control @error('lot') is-invalid @enderror" 
                               id="lot" name="lot" 
                               value="{{ old('lot', $equipement->lot ?? '') }}" 
                               placeholder="Ex: Z1, Z2, C C C..." required>
                        @error('lot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Photo -->
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                           id="photo" name="photo" accept="image/*">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(isset($equipement) && $equipement->photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $equipement->photo) }}" alt="Photo de l'équipement" 
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $equipement->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Informations Techniques -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="type_huile" class="form-label">Type d'Huile</label>
                        <input type="text" class="form-control @error('type_huile') is-invalid @enderror" 
                               id="type_huile" name="type_huile" 
                               value="{{ old('type_huile', $equipement->type_huile ?? '') }}">
                        @error('type_huile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="duree_controle" class="form-label">Durée de Contrôle (jours)</label>
                        <input type="number" class="form-control @error('duree_controle') is-invalid @enderror" 
                               id="duree_controle" name="duree_controle" 
                               value="{{ old('duree_controle', $equipement->duree_controle ?? '') }}">
                        @error('duree_controle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="ref_courroie" class="form-label">Référence Courroie</label>
                        <input type="text" class="form-control @error('ref_courroie') is-invalid @enderror" 
                               id="ref_courroie" name="ref_courroie" 
                               value="{{ old('ref_courroie', $equipement->ref_courroie ?? '') }}">
                        @error('ref_courroie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="ref_roulement" class="form-label">Référence Roulement</label>
                        <input type="text" class="form-control @error('ref_roulement') is-invalid @enderror" 
                               id="ref_roulement" name="ref_roulement" 
                               value="{{ old('ref_roulement', $equipement->ref_roulement ?? '') }}">
                        @error('ref_roulement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Informations Fournisseur et Achat -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="numero_serie" class="form-label">Numéro de Série</label>
                        <input type="text" class="form-control @error('numero_serie') is-invalid @enderror" 
                               id="numero_serie" name="numero_serie" 
                               value="{{ old('numero_serie', $equipement->numero_serie ?? '') }}">
                        @error('numero_serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control @error('marque') is-invalid @enderror" 
                               id="marque" name="marque" 
                               value="{{ old('marque', $equipement->marque ?? '') }}">
                        @error('marque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="date_achat" class="form-label">Date d'Achat</label>
                        <input type="date" class="form-control @error('date_achat') is-invalid @enderror" 
                               id="date_achat" name="date_achat" 
                               value="{{ old('date_achat', $equipement->date_achat ?? '') }}">
                        @error('date_achat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fournisseur_id" class="form-label">Fournisseur</label>
                        <select class="form-select @error('fournisseur_id') is-invalid @enderror" 
                                id="fournisseur_id" name="fournisseur_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" 
                                    {{ old('fournisseur_id', $equipement->fournisseur_id ?? '') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->nom_societe }}
                                </option>
                            @endforeach
                        </select>
                        @error('fournisseur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dernière ligne -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="date_mise_service" class="form-label">Date de Mise en Service</label>
                        <input type="date" class="form-control @error('date_mise_service') is-invalid @enderror" 
                               id="date_mise_service" name="date_mise_service" 
                               value="{{ old('date_mise_service', $equipement->date_mise_service ?? '') }}">
                        @error('date_mise_service')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="prix_achat" class="form-label">Prix d'Achat</label>
                        <input type="number" step="0.01" class="form-control @error('prix_achat') is-invalid @enderror" 
                               id="prix_achat" name="prix_achat" 
                               value="{{ old('prix_achat', $equipement->prix_achat ?? '') }}">
                        @error('prix_achat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('equipements.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection