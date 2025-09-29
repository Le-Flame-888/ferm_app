@extends('layouts.app')

@section('title', 'Détails de l\'équipement')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Détails de l'équipement : {{ $equipement->code_ref }}</h5>
            <div>
                <a href="{{ route('equipements.edit', $equipement->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <form action="{{ route('equipements.destroy', $equipement->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations générales</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%;">Référence</th>
                            <td>{{ $equipement->code_ref }}</td>
                        </tr>
                        <tr>
                            <th>Catégorie</th>
                            <td>{{ $equipement->categorie }}</td>
                        </tr>
                        <tr>
                            <th>Type d'emplacement</th>
                            <td>{{ $equipement->type_emplacement ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Site</th>
                            <td>{{ $equipement->site }}</td>
                        </tr>
                        <tr>
                            <th>Emplacement</th>
                            <td>{{ $equipement->emplacement }}</td>
                        </tr>
                        <tr>
                            <th>Lot</th>
                            <td>{{ $equipement->lot }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Détails techniques</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%;">Type d'huile</th>
                            <td>{{ $equipement->type_huile ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Durée de contrôle (jours)</th>
                            <td>{{ $equipement->duree_controle ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Référence courroie</th>
                            <td>{{ $equipement->ref_courroie ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Référence roulement</th>
                            <td>{{ $equipement->ref_roulement ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Numéro de série</th>
                            <td>{{ $equipement->numero_serie ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Marque</th>
                            <td>{{ $equipement->marque ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h6>Informations d'achat</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%;">Date d'achat</th>
                            <td>{{ $equipement->date_achat ? $equipement->date_achat->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date de mise en service</th>
                            <td>{{ $equipement->date_mise_service ? $equipement->date_mise_service->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Prix d'achat</th>
                            <td>{{ $equipement->prix_achat ? number_format($equipement->prix_achat, 2, ',', ' ') . ' €' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fournisseur</th>
                            <td>
                                @if($equipement->fournisseur)
                                    {{ $equipement->fournisseur->nom_societe }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                @if($equipement->photo)
                    <div class="col-md-6">
                        <h6>Photo</h6>
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $equipement->photo) }}" 
                                 alt="Photo de l'équipement" 
                                 class="img-fluid rounded" 
                                 style="max-height: 300px;">
                        </div>
                    </div>
                @endif
            </div>

            @if($equipement->description)
                <div class="mt-4">
                    <h6>Description</h6>
                    <div class="card">
                        <div class="card-body">
                            {!! nl2br(e($equipement->description)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('equipements.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
@endsection
