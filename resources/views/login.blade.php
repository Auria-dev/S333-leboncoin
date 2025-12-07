@extends('layout')

@section('title', 'Connexion')

@section('content')
    <form method="post" action="{{ url('login') }}" class="login-form">
        @csrf

        @error('email')
            <div class="text-error mb-sm">{{ $message }}</div>
        @enderror

        <div class="full-width">
            <label for="email">Adresse e-mail</label>
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
        
        <div>
            <p>Pas encore de compte ? <a href="{{ url('register') }}" class="hyperlink" wire:navigate>Créez-en un ici</a>.</p>
        </div>
    </form>
@endsection