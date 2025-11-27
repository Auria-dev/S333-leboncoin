@extends('layout')

@section('title', 'Connexion')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <form method="post" action="{{ url('resultats') }}" style="width: 100%; max-width: 400px; margin: 0 auto;">
        @csrf

        <div class="full-width">
            <label for="email">Adresse e-mail</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Votre adresse e-mail..." 
                required
            />
        </div>

        <div class="full-width">
            <label for="mot_de_passe">Mot de passe</label>
            <input 
                type="password" 
                id="mot_de_passe" 
                name="mot_de_passe" 
                placeholder="Votre mot de passe..." 
                required
            />
        </div>

        <div class="full-width">
            <input type="submit" class="submit-btn" value="Se connecter" />
        </div>

        <div>
            <p>Pas encore de compte ? <a href="{{ url('creation-compte') }}" class="hyperlink" wire:navigate>Créez-en un ici</a>.</p>
        </div>

    </form>
@endsection