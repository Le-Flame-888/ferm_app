@extends('layouts.app')

@section('title', 'Liste des Équipements')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des Équipements</h1>
        <a href="{{ route('equipements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter un équipement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Référence</th>
                    <th>Catégorie</th>
                    <th>Site</th>
                    <th>Emplacement</th>
                    <th>Lot</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipements as $equipement)
                    <tr>
                        <td>{{ $equipement->code_ref }}</td>
                        <td>{{ $equipement->categorie }}</td>
                        <td>{{ $equipement->site }}</td>
                        <td>{{ $equipement->emplacement }}</td>
                        <td>{{ $equipement->lot }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('equipements.show', $equipement->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                                <a href="{{ route('equipements.edit', $equipement->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form action="{{ route('equipements.destroy', $equipement->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun équipement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($equipements->hasPages())
        <div class="d-flex justify-content-center">
            {{ $equipements->links() }}
        </div>
    @endif
@endsection
