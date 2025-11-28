@extends('layout')

@section('title', 'Connexion')

@section('content')
    {{-- MODIFICATION ICI : action pointe vers la route de login --}}
    <form method="post" action="{{ url('login') }}" style="width: 100%; max-width: 400px; margin: 0 auto;">
        @csrf

        {{-- Affichage des erreurs de connexion s'il y en a --}}
        @error('email')
            <div style="color: red; margin-bottom: 10px;">{{ $message }}</div>
        @enderror

        <div class="full-width">
            <label for="email">Adresse e-mail</label>
            {{-- Garde 'value' pour ne pas que l'user retape tout en cas d'erreur --}}
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}" 
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
        
        </form>
@endsection