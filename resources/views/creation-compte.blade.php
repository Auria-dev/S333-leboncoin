@extends('layout')

@section('title', 'Créer un compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Optionnel: lier a l'ajout de carte banquaire -->
    <form method="post" action="{{ url('resultats') }}" style="width: 100%; max-width: 400px; margin: 0 auto;">
        @csrf
        
        {{-- TODO (auria): Check with RGPD complience for what we require the user to accept so its only the bare minimum. put everything for now --}}

        <div class="full-width">
            <label for="pseudonyme">Pseudonyme</label>
            <input
                type="text" 
                id="pseudonyme" 
                name="pseudonyme" 
                placeholder="Votre pseudonyme..." 
                required
            />
        </div>

        <div class="full-width side-by-side">
            <div>
                <label for="nom_utilisateur">Nom</label>
                <input 
                    type="text" 
                    id="nom_utilisateur" 
                    name="nom_utilisateur" 
                    placeholder="Votre nom..."
                    required
                />
            </div>

            <div>
                <label for="prenom_utilisateur">Prénom</label>
                <input 
                    type="text" 
                    id="prenom_utilisateur" 
                    name="prenom_utilisateur" 
                    placeholder="Votre prénom..."
                    required
                />
            </div>
        </div>

        <div class="full-width">
            <label for="telephone">Numéro de téléphone</label>
            <input 
                type="tel" 
                id="telephone" 
                name="telephone" 
                placeholder="Votre numéro de téléphone..." 
                required
            />
        </div>

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
            <label>
                <input type="checkbox" name="accept_terms" required />
                &nbsp; J'accepte les <a href="#" class="hyperlink">conditions générales</a> et la <a href="#" class="hyperlink">politique de confidentialité</a>.
            </label>
        </div>

        <div class="full-width">
            <input type="submit" class="submit-btn" value="Créer un compte" />
        </div>

        <div>
            <p>Déjà un compte ? <a href="{{ url('login') }}" class="hyperlink" wire:navigate>Connectez-vous ici</a>.</p>
        </div>
    </form>
@endsection