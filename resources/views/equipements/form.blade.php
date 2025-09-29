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

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code_ref" class="form-label">Référence *</label>
                            <input type="text" class="form-control @error('code_ref') is-invalid @enderror" 
                                   id="code_ref" name="code_ref" 
                                   value="{{ old('code_ref', $equipement->code_ref ?? '') }}" required>
                            @error('code_ref')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categorie" class="form-label">Catégorie *</label>
                            <input type="text" class="form-control @error('categorie') is-invalid @enderror" 
                                   id="categorie" name="categorie" 
                                   value="{{ old('categorie', $equipement->categorie ?? '') }}" required>
                            @error('categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type_emplacement" class="form-label">Type d'emplacement</label>
                            <input type="text" class="form-control @error('type_emplacement') is-invalid @enderror" 
                                   id="type_emplacement" name="type_emplacement" 
                                   value="{{ old('type_emplacement', $equipement->type_emplacement ?? '') }}">
                            @error('type_emplacement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site" class="form-label">Site *</label>
                            <input type="text" class="form-control @error('site') is-invalid @enderror" 
                                   id="site" name="site" 
                                   value="{{ old('site', $equipement->site ?? '') }}" required>
                            @error('site')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="emplacement" class="form-label">Emplacement *</label>
                            <input type="text" class="form-control @error('emplacement') is-invalid @enderror" 
                                   id="emplacement" name="emplacement" 
                                   value="{{ old('emplacement', $equipement->emplacement ?? '') }}" required>
                            @error('emplacement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lot" class="form-label">Lot *</label>
                            <input type="text" class="form-control @error('lot') is-invalid @enderror" 
                                   id="lot" name="lot" 
                                   value="{{ old('lot', $equipement->lot ?? '') }}" required>
                            @error('lot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fournisseur_id" class="form-label">Fournisseur</label>
                            <select class="form-select @error('fournisseur_id') is-invalid @enderror" 
                                    id="fournisseur_id" name="fournisseur_id">
                                <option value="">Sélectionner un fournisseur</option>
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

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo">
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
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $equipement->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
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
