@extends('layout')

@section('title', 'Déposer un avis')

@section('content')
<div class="container" style="max-width: 600px; margin-top: 2rem;">
    <h2>Votre avis nous intéresse</h2>
    <p>Comment s'est passé votre séjour ?</p>

    <form action="{{ route('avis.store', $reservation->idreservation) }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1rem;">
            <label for="note" style="display: block; font-weight: bold;">Note globale</label>
            <select name="note" id="note" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Très bien</option>
                <option value="3">3 - Bien</option>
                <option value="2">2 - Moyen</option>
                <option value="1">1 - Mauvais</option>
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="commentaire" style="display: block; font-weight: bold;">Votre commentaire</label>
            <textarea name="commentaire" id="commentaire" rows="5" 
                style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;"
                placeholder="Racontez votre expérience..."></textarea>
        </div>

        <button type="submit" class="button" style="background-color: #ff6e14; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
            Envoyer mon avis
        </button>
    </form>
</div>
@endsection