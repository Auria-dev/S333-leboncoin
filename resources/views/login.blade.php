@extends('layout')

@section('title', 'Connexion')

@section('content')

    @if (session('error'))
        <div class="text-error mb-sm" style="text-align: center; color: #e74c3c; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ url('login') }}" class="login-form">
        @csrf


        @error('email')
            <div class="text-error mb-sm" style="color: #e74c3c;">{{ $message }}</div>
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

        <div class="full-width" style="text-align: center; margin: 20px 0; opacity: 0.6; position: relative;">
            <span style="background: #fff; padding: 0 10px; position: relative; z-index: 1;">ou</span>
            <div style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px solid #ddd; z-index: 0;"></div>
        </div>


        <div class="full-width">
            <a href="{{ route('login.google') }}" class="google-btn">
                {{-- Logo Google SVG --}}
                <svg style="width:18px; height:18px; margin-right:10px;" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                </svg>
                Se connecter avec Google
            </a>
        </div>
        

        <div class="login-link-container" style="margin-top: 20px; text-align: center;">
            <p>Pas encore de compte ? <a href="{{ url('register') }}" class="hyperlink" wire:navigate>Créez-en un ici</a>.</p>
        </div>

    </form>

    {{-- Style CSS intégré pour le bouton Google --}}
    <style>
        .google-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 12px;
            background-color: #ffffff;
            color: #757575;
            border: 1px solid #ddd;
            border-radius: 4px; 
            text-decoration: none;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            transition: all 0.2s ease;
            box-sizing: border-box; 
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .google-btn:hover {
            background-color: #f8f9fa;
            border-color: #c6c6c6;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .text-error {
            color: red;
            font-size: 0.9em;
        }
    </style>

@endsection