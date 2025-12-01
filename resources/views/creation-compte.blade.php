@extends('layout')

@section('title', 'Créer un compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!--     
    idutilisateur
    idville => ville
    tel
    mail
    addr
    date creation
    date latest login

    particulier:
    - code particulier

    entreprise:
    - num siret
    - id secteur  => secteur -->

<form action="{{ url('register') }}" method="POST" style="display: flex;flex-direction: column; gap: 1rem;">
    @csrf
    
    <div class="side-by-side">
        <div class="side-by-side center">
            <input type="radio" id="compteParticulier" name="typeCompte" value="particulier" onclick="toggleEntreprise(false)" {{ old('typeCompte', 'particulier') == 'particulier' ? 'checked' : '' }}/>
            <label class="nopad nomarg" for="compteParticulier">Compte particulier</label>
        </div>
        <div class="side-by-side center">
            <input type="radio" id="compteEntreprise" name="typeCompte" value="entreprise" onclick="toggleEntreprise(true)" {{ old('typeCompte') == 'entreprise' ? 'checked' : '' }} />
            <label class="nopad nomarg" for="compteEntreprise">Compte entreprise</label>
        </div>
    </div>

    <input placeholder="Nom" type="text" id="nom" name="nom" value="{{ old('nom') }}" autofocus required>
    <input placeholder="Prenom" type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
    <input placeholder="Numéro de telephone" type="text" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
    <input placeholder="Email" type="text" id="email" name="email" value="{{ old('email') }}" required>
    <input placeholder="Address" type="text" id="address" name="address" value="{{ old('address') }}" required>
    
    <div id="entrepriseFields" style="display: none; flex-direction: column; gap: 1rem;">
        <input placeholder="Numéro SIRET" type="text" id="siret" name="siret" value="{{ old('siret') }}">
        <select name="secteur" id="secteur">
            <option value="" disabled selected>Choisir un secteur</option>
            <option value="tech">Technologie</option>
            </select>
    </div>
    
    <input placeholder="Mot de pass" type="password" id="password" name="password" value="{{ old('password') }}" required>
    <input placeholder="Confirmer le mot de passe" type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
    <input type=submit value="Register">
    <div>
        <p>Déjà un compte ? <a href="{{ url('login') }}" class="hyperlink" wire:navigate>Connectez-vous ici</a>.</p>
    </div>
</form>
<script>
    function toggleEntreprise(isEntreprise) {
        const container = document.getElementById('entrepriseFields');
        const siretInput = document.getElementById('siret');
        const secteurInput = document.getElementById('secteur');

        if (isEntreprise) {
            container.style.display = 'flex';
            siretInput.setAttribute('required', 'required');
            secteurInput.setAttribute('required', 'required');
        } else {
            container.style.display = 'none';
            siretInput.removeAttribute('required');
            secteurInput.removeAttribute('required');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const isEntreprise = document.getElementById('compteEntreprise').checked;
        toggleEntreprise(isEntreprise);
    });
</script>
@endsection