@extends('layout')

@section('title', 'Modération des Avis')

@section('content')
<div class="container" style="padding: 20px;">
    <h1>Espace Modération : Avis en attente</h1>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa; text-align: left;">
                <th style="padding: 10px; border: 1px solid #ddd;">Date</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Auteur</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Annonce</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Note</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Commentaire</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($avisEnAttente as $avis)
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    {{ \Carbon\Carbon::parse($avis->date_depot)->format('d/m/Y') }}
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    {{ $avis->utilisateur->prenom_utilisateur }} {{ $avis->utilisateur->nom_utilisateur }}
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    {{ $avis->reservation->annonce->titre_annonce ?? 'Annonce supprimée' }}
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    {{ $avis->note }}/5
                </td>
                <td style="padding: 10px; border: 1px solid #ddd; max-width: 300px;">
                    {{ $avis->commentaire }}
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <div style="display: flex; gap: 5px;">
                        
                        <form action="{{ route('admin.avis.update', $avis->idavis) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="valider">
                            <button type="submit" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                                Valider
                            </button>
                        </form>

                        <form action="{{ route('admin.avis.update', $avis->idavis) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="refuser">
                            <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                                Refuser
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">
                    Aucun avis en attente de validation.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection