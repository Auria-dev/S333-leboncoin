@extends('layout')

@section('title', 'Créer un compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="center-container">

    <form action="{{ url('register') }}" method="POST" class="register-form"
          x-data="registerForm()"
          @submit.prevent="submitForm" enctype="multipart/form-data">
            
            @csrf
            <p class="subtitle">Les champs obligatoires sont marqués d'un astérisque (*) </p>
            
            @if ($errors->any())
                <div class="alert-error">
                    <strong>Oups !</strong> Il y a des problèmes avec votre saisie.
                </div>
            @endif

            <div class="input-groupe">
                <label>Type de compte</label>
                <div class="radio-group-container">
                    <div class="radio-option">
                        <input type="radio" id="compteParticulier" name="typeCompte" value="particulier" 
                        onclick="toggleEntreprise(false)" {{ old('typeCompte') == 'entreprise' ? 'checked' : '' }}
                               x-model="accountType">
                        <label for="compteParticulier">Particulier</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="compteEntreprise" name="typeCompte" value="entreprise" 
                        onclick="toggleEntreprise(true)" {{ old('typeCompte') == 'entreprise' ? 'checked' : '' }}
                               x-model="accountType">
                        <label for="compteEntreprise">Entreprise</label>
                    </div>
                </div>
            </div>

            <div class="side-by-side">
                <div class="input-groupe flex-1" x-data="inputField()">
                    <label for="prenom">Prénom*</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" 
                           placeholder="Votre prénom" required autofocus
                           class="@error('prenom') is-invalid @enderror">
                    @error('prenom') <div class="text-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="input-groupe flex-1" x-data="inputField()">
                    <label for="nom">Nom*</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" 
                           placeholder="Votre nom" required
                           class="@error('nom') is-invalid @enderror">
                    @error('nom') <div class="text-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="input-groupe" x-data="inputField()">
                <label for="email">Email*</label>
                <div class="text-error" x-show="error" x-text="error"></div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       placeholder="exemple@email.com" required
                       @input="validateEmail($el.value)"
                       :class="{ 'is-invalid': error, '@error('email') is-invalid @enderror': !error }">
                
                @error('email') <div class="text-error">{{ $message }}</div> @enderror
            </div>

            <div class="input-groupe" x-data="inputField()">
                <label for="telephone">Téléphone*</label>
                <div class="text-error" x-show="error" x-text="error"></div>
                <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" 
                       placeholder="06 12 34 56 78" required maxlength="10"
                       @input="validatePhone($el.value)"
                       :class="{ 'is-invalid': error, '@error('telephone') is-invalid @enderror': !error }">
                
                @error('telephone') <div class="text-error">{{ $message }}</div> @enderror
            </div>

            <div class="input-groupe relative" 
                 x-data="addressField('{{ old('adresse') }}', '{{ old('ville') }}', '{{ old('code_postal') }}')"
                 @click.outside="closeDropdown()">
                 
                 <label for="adresse">Adresse*</label>
                 @error('adresse') <div class="text-error">{{ $message }}</div> @enderror
                <input type="text" id="adresse" name="adresse" x-model="display"
                       placeholder="9 Rue de l'Arc en Ciel, 74940 Annecy" required autocomplete="off"
                       @input="search()"
                       class="@error('adresse') is-invalid @enderror">

                <input type="hidden" name="ville" x-model="city">
                <input type="hidden" name="code_postal" x-model="zip">

                <div class="autocomplete-dropdown" x-show="results.length > 0" x-transition>
                    <template x-for="item in results" :key="item.properties.id">
                        <div class="suggestion-item" @click="select(item)">
                            <div class="font-bold" x-text="item.properties.label"></div>
                            <div class="text-sm text-gray-500">
                                <span x-text="item.properties.postcode"></span> 
                                <span x-text="item.properties.city"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="accountType === 'entreprise'" x-transition class="entreprise-fields">
                <div class="separator"></div>
                
                <div class="input-groupe" x-data="siretField()">
                    <label for="siret">Numéro SIRET</label>
                    <div class="text-error" x-show="error" x-text="error"></div>
                    <input type="text" id="siret" name="siret" value="{{ old('siret') }}" placeholder="14 chiffres"
                           :required="accountType === 'entreprise'"
                            @input="validateSiret($el.value)"
                           :class="{ 'is-invalid': error, '@error('siret') is-invalid @enderror': !error }">
                    @error('siret') <div class="text-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="input-groupe">
                    <label for="secteur">Secteur d'activité</label>
                    @error('secteur') <div class="text-error">{{ $message }}</div> @enderror
                    <select name="secteur" id="secteur" class="@error('secteur') is-invalid @enderror"
                            :required="accountType === 'entreprise'">
                        <option value="" disabled selected>Choisir un secteur</option>
                        @if(isset($secteurs))
                            @foreach($secteurs as $s)
                                <option value="{{ $s->nom_secteur }}" {{ old('secteur') == $s->nom_secteur ? 'selected' : '' }}>
                                    {{ $s->nom_secteur }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div style="width: 100%; height: 1px; background: var(--border-default); margin: 0.5rem 0;"></div>
            </div>

            <div x-data="passwordManager()" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="input-groupe" style="flex: 1;">
                    <label for="password">Mot de passe*</label>
                     <div class="text-error mt-sm" x-show="errorType === 'length'">
                        Le mot de passe doit contenir au moins 8 caractères.
                    </div>
                    @error('password') <div class="text-error">{{ $message }}</div> @enderror
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required
                           x-model="p1" @input="check()"
                           :class="{ 'is-invalid': errorType === 'length', '@error('password') is-invalid @enderror': !errorType }">
                </div>

                <div class="input-groupe flex-1">
                    <label for="password_confirmation">Confirmation*</label>
                    
                    <div class="text-error mt-sm" x-show="errorType === 'match'">
                        Les mots de passe ne correspondent pas.
                    </div>

                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Votre mot de passe" required
                           x-model="p2" @input="check()"
                           :class="{ 'is-invalid': errorType === 'match', '@error('password_confirmation') is-invalid @enderror': !errorType }">
                    
                </div>
            </div>

            <div x-show="accountType === 'particulier'">
                <div class="input-groupe">
                    <label>Afin de publier des annonces, la vérification de votre identité est requise. Vous pouvez transmettre votre pièce d'identité <u>dès maintenant</u> ou <u>ultérieurement</u> sous forme de PDF.</label>
                    <input type="file" name="file" id="fileInput" accept=".pdf">
                </div>
            </div>

            <div class="mt-md">
                <input type="submit" value="S'inscrire" class="submit-btn"
                       :disabled="globalErrors"
                       :style="globalErrors ? 'opacity: 0.5; cursor: not-allowed' : ''">
            </div>

            <div class="login-link-container">
                <p>Déjà un compte ? <a href="{{ url('login') }}" class="hyperlink" wire:navigate>Connectez-vous ici</a>.</p>
            </div>
        </form>

    </div>

    <script>
        function toggleEntreprise(isEntreprise) {
            const container = document.querySelector('.entreprise-fields');
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

        document.addEventListener('alpine:init', () => {
            Alpine.data('registerForm', () => ({
                accountType: '{{ old('typeCompte', 'particulier') }}',
                globalErrors: false,

                init() {
                    this.$el.addEventListener('field-error', (e) => { 
                        this.globalErrors = e.detail; 
                    });
                },

                submitForm() {
                    if(this.globalErrors) return;
                    this.$el.submit();
                }
            }));

            Alpine.data('inputField', () => ({
                error: null,

                validateEmail(val) {
                    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
                    this.error = valid ? null : 'Email invalide. Format attendu: nom@mail.com';
                    this.$dispatch('field-error', !valid);
                },

                validatePhone(val) {
                    const valid = /^0[0-9]{9}$/.test(val);
                    this.error = valid ? null : 'Doit comporter 10 caractères et commencer par un 0';
                    this.$dispatch('field-error', !valid);
                }
            }));

            Alpine.data('siretField', () => ({
                error: null,

                validateSiret(val) {
                    const cleanVal = val.replace(/\s/g, '');
                    const validLength = /^[0-9]{14}$/.test(cleanVal);
                    
                    if (!validLength) {
                         this.error = 'Le SIRET doit comporter exactement 14 chiffres.';
                         this.$dispatch('field-error', true);
                         return;
                    }

                    let sum = 0;
                    for (let i = 0; i < cleanVal.length; i++) {
                        let digit = parseInt(cleanVal.charAt(i));
                        if (i % 2 === 0) { 
                            digit *= 2;
                            if (digit > 9) digit -= 9;
                        }
                        sum += digit;
                    }
                    
                    const luhnValid = (sum % 10 === 0);
                    
                    this.error = luhnValid ? null : 'Numéro SIRET invalide.';
                    this.$dispatch('field-error', !luhnValid);
                }
            }));

            Alpine.data('addressField', (initAddr, initCity, initZip) => ({
                display: initAddr || '',
                city: initCity || '',
                zip: initZip || '',
                results: [],

                async search() {
                    if (this.display.length < 3) { this.results = []; return; }
                    try {
                        let res = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(this.display)}&limit=5&autocomplete=1`);
                        let data = await res.json();
                        this.results = data.features;
                    } catch (e) { console.error(e); }
                },

                select(item) {
                    this.display = item.properties.label;
                    this.city = item.properties.city;
                    this.zip = item.properties.postcode;
                    this.results = [];
                },

                closeDropdown() {
                    // Small delay to allow click event to register on items
                    setTimeout(() => { this.results = []; }, 200);
                }
            }));

            // Password Validation Logic
            Alpine.data('passwordManager', () => ({
                p1: '',
                p2: '',
                errorType: null,

                check() {
                    if (this.p1.length > 0) {
                        if (this.p1.length < 8) {
                            this.errorType = 'length';
                            this.$dispatch('field-error', true);
                            return;
                        }
                        if (this.p2.length > 0 && this.p1 !== this.p2) {
                            this.errorType = 'match';
                            this.$dispatch('field-error', true);
                            return;
                        }
                        this.errorType = null;
                        this.$dispatch('field-error', false);
                    } else {
                        this.errorType = null;
                        this.$dispatch('field-error', false);
                    }
                }
            }));
        });
    </script>

    <style>
        .autocomplete-dropdown {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            width: 100%;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            border-radius: 4px;
            margin-top: 2px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }
        .suggestion-item:hover {
            background-color: #f9fafb;
        }
    </style>
@endsection