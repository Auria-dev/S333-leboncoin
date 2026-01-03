@extends('layout')

@section('title', 'Déposer un avis')

@section('content')
<h2 id="titre-incident">Comment s'est passé votre séjour ?</h2>
<div class="center-container">
    <form action="{{ route('avis.store', $reservation->idreservation) }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1rem;" >
            <label for="note">Note globale</label>
            <select name="note" id="note">
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Très bien</option>
                <option value="3">3 - Bien</option>
                <option value="2">2 - Moyen</option>
                <option value="1">1 - Mauvais</option>
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="commentaire">Votre commentaire</label>
            <textarea name="commentaire" id="commentaire" cols="50" rows="10" placeholder="Racontez votre expérience..."></textarea>
        </div>

        <button type="submit" class="submit-btn">
            Envoyer mon avis
        </button>
    </form>
</div>
@endsection