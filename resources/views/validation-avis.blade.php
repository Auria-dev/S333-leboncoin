@extends('layout')

@section('title', 'Modération des Avis')

@section('content')
<div class="container" style="padding: 20px;">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Auteur</th>
                <th>Annonce</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($avisEnAttente as $avis)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($avis->date_depot)->format('d/m/Y') }}
                </td>
                <td>
                    {{ $avis->utilisateur->prenom_utilisateur }} {{ $avis->utilisateur->nom_utilisateur }}
                </td>
                <td>
                    {{ $avis->reservation->annonce->titre_annonce ?? 'Annonce supprimée' }}
                </td>
                <td>
                    {{ $avis->note }}/5
                </td>
                <td>
                    {{ $avis->commentaire }}
                </td>
                <td>
                    <div style="display: flex; justify-content: space-between;">
                        
                        <form action="{{ route('admin.avis.update', $avis->idavis) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="valider">
                            <button type="submit" class="status-dot st-accepted" style="border: none">
                                Valider
                            </button>
                        </form>

                        <form action="{{ route('admin.avis.update', $avis->idavis) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="refuser">
                            <button type="submit" class="status-dot st-pending" style="border: none">
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
@endsection