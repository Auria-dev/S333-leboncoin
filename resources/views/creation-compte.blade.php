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

            {{-- 1. Choix du type de compte --}}
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

            {{-- ========================================== --}}
            {{--   BOUTON GOOGLE PLACÉ EN HAUT (UX)     --}}
            {{-- ========================================== --}}
            
            <div style="margin-top: 20px;">
                <a href="{{ route('login.google') }}" class="google-btn">
                    <svg style="width:18px; height:18px; margin-right:10px;" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                    </svg>
                    S'inscrire avec Google
                </a>
            </div>


            <div style="text-align: center; margin: 25px 0; position: relative; opacity: 0.7;">
                <span style="background-color: inherit; padding: 0 10px; position: relative; z-index: 1; font-weight: bold;">ou avec votre email</span>
                <div style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px solid #ccc; z-index: 0;"></div>
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
                <div class="upload-container">
                    <label>
                        Afin de publier des annonces, la vérification de votre identité est requise. 
                        Vous pouvez transmettre votre pièce d'identité <u>dès maintenant</u> ou <u>ultérieurement</u> sous forme de PDF.
                    </label>

                    <div class="button-group">
                        <button type="button" id="customSelectBtn" class="btn btn-select">
                            Sélectionner un fichier
                        </button>
                    </div>
    
                    <span id="fileChosen">Aucun fichier sélectionné</span>
    
                    <input type="file" name="file" id="realFileInput" accept=".pdf" hidden>
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

    {{-- SCRIPTS ET STYLES (Inchangés) --}}
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

                    // let sum = 0;
                    // for (let i = 0; i < cleanVal.length; i++) {
                    //     let digit = parseInt(cleanVal.charAt(i));
                    //     if (i % 2 === 0) { 
                    //         digit *= 2;
                    //         if (digit > 9) digit -= 9;
                    //     }
                    //     sum += digit;
                    // }
                    
                    // const luhnValid = (sum % 10 === 0);
                    
                    // this.error = luhnValid ? null : 'Numéro SIRET invalide.';
                    // this.$dispatch('field-error', !luhnValid);
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
                    setTimeout(() => { this.results = []; }, 200);
                }
            }));

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


        const realFileBtn = document.getElementById("realFileInput");
        const customBtn = document.getElementById("customSelectBtn");
        const customTxt = document.getElementById("fileChosen");

        customBtn.addEventListener("click", function(e) {
            e.preventDefault(); 
            realFileBtn.click();
        });

        realFileBtn.addEventListener("change", function() {
            if (realFileBtn.files.length > 0) {
                customTxt.textContent = realFileBtn.files[0].name;
            } else {
                customTxt.textContent = "Aucun fichier sélectionné";
            }
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
        
        .google-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 12px;
            background-color: #ffffff;
            color: #757575;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            font-size: 15px;
            transition: all 0.2s ease;
            box-sizing: border-box; 
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            cursor: pointer;
        }
        .google-btn:hover {
            background-color: #f1f1f1;
            border-color: #c6c6c6;
        }
    </style>
@endsection