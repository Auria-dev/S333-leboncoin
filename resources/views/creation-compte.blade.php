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

    <form action="{{ url('register') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            
            <div class="input-groupe">
                <label>Type de compte</label>
                <div class="radio-group-container">
                    <div class="radio-option">
                        <input type="radio" id="compteParticulier" name="typeCompte" value="particulier" onclick="toggleEntreprise(false)" {{ old('typeCompte', 'particulier') == 'particulier' ? 'checked' : '' }}/>
                        <label for="compteParticulier">Particulier</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="compteEntreprise" name="typeCompte" value="entreprise" onclick="toggleEntreprise(true)" {{ old('typeCompte') == 'entreprise' ? 'checked' : '' }} />
                        <label for="compteEntreprise">Entreprise</label>
                    </div>
                </div>
            </div>

            <div class="side-by-side">
                <div class="input-groupe" style="flex: 1;">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Votre nom" autofocus required>
                </div>
                <div class="input-groupe" style="flex: 1;">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="input-groupe">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
            </div>

            <div class="input-groupe">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" placeholder="06 12 34 56 78" required>
            </div>

            <div class="input-groupe">
                <label for="address">Adresse</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Votre adresse complète" required>
            </div>

            <div id="entrepriseFields" style="display: none; flex-direction: column; gap: 1.25rem;">
                <div style="width: 100%; height: 1px; background: var(--border-default); margin: 0.5rem 0;"></div>
                
                <div class="input-groupe">
                    <label for="siret">Numéro SIRET</label>
                    <input type="text" id="siret" name="siret" value="{{ old('siret') }}" placeholder="14 chiffres">
                </div>
                
                <div class="input-groupe">
                    <label for="secteur">Secteur d'activité</label>
                    <select name="secteur" id="secteur">
                        <option value="" disabled selected>Choisir un secteur</option>
                        @if(isset($secteurs))
                            @foreach($secteurs as $s)
                                <option value="{{ $s->nom_secteur }}">
                                    {{ $s->nom_secteur }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                </div>
                
                <div style="width: 100%; height: 1px; background: var(--border-default); margin: 0.5rem 0;"></div>
            </div>

            <div class="input-groupe" style="flex: 1;">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <div class="input-groupe" style="flex: 1;">
                <label for="password_confirmation">Confirmation</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Votre mot de passe" required>
            </div>

            <div style="margin-top: 1rem;">
                <input type="submit" value="S'inscrire" class="submit-btn">
            </div>

            <div style="text-align: center; margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem;">
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
            setTimeout(() => {
                container.classList.add('visible');
            }, 10);
            
            siretInput.setAttribute('required', 'required');
            secteurInput.setAttribute('required', 'required');
        } else {
            container.classList.remove('visible');
            setTimeout(() => {
                container.style.display = 'none';
            }, 300);
            
            siretInput.removeAttribute('required');
            secteurInput.removeAttribute('required');
            
            siretInput.value = '';
            secteurInput.selectedIndex = 0;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const enterpriseRadio = document.getElementById('compteEntreprise');
        
        if(enterpriseRadio.checked) {
            const container = document.getElementById('entrepriseFields');
            const siretInput = document.getElementById('siret');
            const secteurInput = document.getElementById('secteur');
            
            container.style.display = 'flex';
            container.classList.add('visible');
            siretInput.setAttribute('required', 'required');
            secteurInput.setAttribute('required', 'required');
        }
    });
</script>
@endsection