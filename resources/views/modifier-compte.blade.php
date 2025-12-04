@extends('layout')

@section('title', 'Modifier mon compte')

@section('content')
    {{-- Dependencies --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        :root {
            --input-bg-disabled: #f9fafb;
            --input-border-disabled: transparent;
            --input-text-disabled: #a38c83ff;
        }

        .form-container { display: flex; flex-direction: column; gap: 1.5rem; max-width: 800px; margin: 0 auto; }
        .row-group { display: flex; gap: 1.5rem; }
        .row-group > * { flex: 1; }
        .separator { height: 1px; background-color: #e5e7eb; margin: 0.5rem 0; }

        .field-group { position: relative; display: flex; flex-direction: column; gap: 0.35rem; }
        
        .input-wrapper { position: relative; display: flex; align-items: center; }

        .input-wrapper input:disabled, 
        .input-wrapper select:disabled {
            background-color: var(--input-bg-disabled);
            color: var(--input-text-disabled);
            cursor: default;
            font-weight: 500;
            box-shadow: none;
        }

        .input-wrapper input:not(:disabled), 
        .input-wrapper select:not(:disabled) {
            background-color: var(--input-focus);
            border: 1px solid var(--primary);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
            color: #000;
            cursor: text;
        }

        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding: 0.625rem 2.5rem 0.625rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }

        .action-btn {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            padding: 4px; border-radius: 4px; cursor: pointer; color: #9ca3af;
            transition: color 0.2s, background 0.2s;
            background: none; border: none; z-index: 10;
        }
        
        .action-btn:hover { color: var(--primary); background: #f3f4f6; }
        
        input:not(:disabled) ~ .action-btn.edit-trigger { display: none; }

        .text-error { color: #dc2626; font-size: 0.85rem; margin-top: 2px; }
        .is-invalid { border-color: #dc2626 !important; }

        .dropdown-results {
            position: absolute; top: 100%; left: 0; right: 0; z-index: 50;
            background: white; border: 1px solid #e5e7eb; border-radius: 6px;
            margin-top: 4px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            max-height: 250px; overflow-y: auto;
        }
        .dropdown-item { padding: 0.75rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
        .dropdown-item:hover { background-color: #f9fafb; }
        
        .password-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .password-section-title { font-weight: bold; font-size: 1.1em; }
        .password-edit-btn { 
            color: var(--primary); font-size: 0.9em; cursor: pointer; text-decoration: underline; background: none; border: none; 
        }
        .password-edit-btn:hover { color: var(--primary-hover); }
        .password-container {
            padding: 1rem; border: 1px solid var(--border-default); border-radius: 6px;
            background-color: #fafafa; transition: background-color 0.3s;
        }
        .password-container.is-editing { background-color: #fff; border-color: var(--primary); }
    </style>

    <form method="POST" action="{{ url('upload') }}" enctype="multipart/form-data">  
        @csrf  
        <input type="file" name="file" accept="image/png, image/jpeg, image/jpg">  
        <button type="submit">Télécharger</button>  
    </form>

    @if(isset($user->photo_profil) && $user->photo_profil == null)
        <p> pas de photo </p>
    @else
    <img src="{{ $user->photo_profil }}"/ style="width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:1rem;">
    @endif
    
    <form action="{{ url('modifier_compte/update') }}" method="POST" class="form-container"
          x-data="formManager()"
          @submit.prevent="submitForm">
        
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:6px;">
                Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif
        @if(session('success'))
            <div style="background:#d1fae5; color:#065f46; padding:1rem; border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="row-group">
            <div class="field-group" x-data="inputField()">
                <label class="font-bold">Prénom</label>
                <div class="input-wrapper">
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom_utilisateur) }}"
                           :disabled="!editing" x-ref="input" @input="touch()">
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                @error('prenom') <div class="text-error">{{ $message }}</div> @enderror
            </div>

            <div class="field-group" x-data="inputField()">
                <label class="font-bold">Nom</label>
                <div class="input-wrapper">
                    <input type="text" name="nom" value="{{ old('nom', $user->nom_utilisateur) }}"
                           :disabled="!editing" x-ref="input" @input="touch()">
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                @error('nom') <div class="text-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field-group" x-data="inputField()">
            <label class="font-bold">Email</label>
            <div class="input-wrapper">
                <input type="email" name="email" value="{{ old('email', $user->mail) }}"
                       :disabled="!editing" x-ref="input" 
                       @input="touch(); validateEmail($el.value)"
                       :class="{ 'is-invalid': error }">
                <button type="button" class="action-btn edit-trigger" @click="enable()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
            </div>
            <div class="text-error" x-show="error" x-text="error"></div>
            @error('email') <div class="text-error">{{ $message }}</div> @enderror
        </div>

        <div class="field-group" x-data="inputField()">
            <label class="font-bold">Téléphone</label>
            <div class="input-wrapper">
                <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                       :disabled="!editing" x-ref="input" maxlength="10"
                       @input="touch(); validatePhone($el.value)"
                       :class="{ 'is-invalid': error }">
                <button type="button" class="action-btn edit-trigger" @click="enable()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
            </div>
            <div class="text-error" x-show="error" x-text="error"></div>
            @error('telephone') <div class="text-error">{{ $message }}</div> @enderror
        </div>

        <div class="field-group" 
             x-data="addressField('{{ old('adresse', $user->adresse_utilisateur) }}', '{{ old('ville', $ville->nom_ville ?? '') }}', '{{ old('code_postal', $ville->code_postal ?? '') }}')"
             @click.outside="closeDropdown()">
            
            <label class="font-bold">Adresse</label>
            <div class="input-wrapper">
                <input type="text" name="adresse" x-model="display"
                       placeholder="Rechercher..." autocomplete="off"
                       :disabled="!editing" x-ref="input"
                       @input="touch(); search()"
                       :class="{ 'is-invalid': error }">
                
                <input type="hidden" name="ville" x-model="city">
                <input type="hidden" name="code_postal" x-model="zip">

                <button type="button" class="action-btn edit-trigger" @click="enable()" style="display:block;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
            </div>

            <div class="dropdown-results" x-show="results.length > 0">
                <template x-for="item in results" :key="item.properties.id">
                    <div class="dropdown-item" @mousedown.prevent="" @click="select(item)">
                        <div class="font-bold" x-text="item.properties.label"></div>
                        <div class="text-sm text-gray-500">
                            <span x-text="item.properties.postcode"></span> 
                            <span x-text="item.properties.city"></span>
                        </div>
                    </div>
                </template>
            </div>
            @error('adresse') <div class="text-error">{{ $message }}</div> @enderror
        </div>

        @if($isEntreprise)
            <div class="separator"></div>
            <div class="field-group" x-data="inputField()">
                <label class="font-bold">Numéro SIRET</label>
                <div class="input-wrapper">
                    <input type="text" name="siret" value="{{ old('siret', $entreprise->numsiret ?? '') }}"
                           :disabled="!editing" x-ref="input" @input="touch()">
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                @error('siret') <div class="text-error">{{ $message }}</div> @enderror
            </div>

            <div class="field-group" x-data="inputField()">
                <label class="font-bold">Secteur</label>
                <div class="input-wrapper">
                    <select name="secteur" :disabled="!editing" x-ref="input" @change="touch()">
                        @foreach($secteurs as $s)
                            <option value="{{ $s->nom_secteur }}" {{ ($entreprise->idsecteur ?? '') == $s->idsecteur ? 'selected' : '' }}>
                                {{ $s->nom_secteur }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
            </div>
            <div class="separator"></div>
        @endif

        <div x-data="passwordManager()" class="field-group">
            <div class="password-section-header">
                <span class="password-section-title">Sécurité</span>
                <button type="button" class="password-edit-btn" 
                        x-show="!editing" 
                        @click="enable()">
                    Modifier le mot de passe
                </button>
            </div>
            
            <div class="password-container" :class="{ 'is-editing': editing }">
                <div class="row-group">
                    <div class="field-group">
                        <label class="font-bold">Nouveau mot de passe</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" x-model="p1" placeholder="********"
                                   :disabled="!editing" x-ref="p1input" 
                                   @input="check();"
                                   :class="{ 'is-invalid': errorType === 'length' }">
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="font-bold">Confirmation</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" x-model="p2" placeholder="********"
                                   :disabled="!editing"
                                   @input="check();"
                                   :class="{ 'is-invalid': errorType === 'match' }">
                        </div>
                    </div>
                </div>

                <div class="text-error" x-show="errorType === 'length'" style="margin-top: 10px;">
                    Le mot de passe doit contenir au moins 8 caractères.
                </div>
                <div class="text-error" x-show="errorType === 'match'" style="margin-top: 10px;">
                    Les mots de passe ne correspondent pas.
                </div>
            </div>
        </div>

        <div class="row-group" style="justify-content: space-between; align-items: center; margin-top: 1rem;">
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-800">Annuler</a>
            
            <button type="submit" class="submit-btn" 
                    :disabled="!isGlobalDirty || globalErrors"
                    style="padding: 10px 20px; background: var(--primary); color: white; border-radius: 6px; opacity: 0.5;"
                    :style="(!isGlobalDirty || globalErrors) ? 'opacity: 0.5; cursor: not-allowed' : 'opacity: 1; cursor: pointer'">
                Enregistrer les modifications
            </button>
        </div>

    </form>

    <script type="text/html" id="icon-pencil">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            
            Alpine.data('formManager', () => ({
                isGlobalDirty: false,
                globalErrors: false,

                init() {
                    this.$el.addEventListener('field-touched', () => { this.isGlobalDirty = true; });
                    this.$el.addEventListener('field-error', (e) => { this.globalErrors = e.detail; });
                },

                submitForm() {
                    if(this.globalErrors) return;

                    this.$el.querySelectorAll(':disabled').forEach(el => {
                        el.disabled = false;
                    });

                    this.$el.submit();
                }
            }));

            Alpine.data('inputField', () => ({
                editing: false,
                error: null,

                enable() {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.input.focus());
                },

                touch() { this.$dispatch('field-touched'); },

                validateEmail(val) {
                    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
                    this.error = valid ? null : 'Email invalide';
                    this.$dispatch('field-error', !valid);
                },

                validatePhone(val) {
                    const valid = /^0[0-9]{9}$/.test(val);
                    this.error = valid ? null : 'Doit comporter 10 caractères et commencer par un 0';
                    this.$dispatch('field-error', !valid);
                }
            }));

            Alpine.data('addressField', (initAddr, initCity, initZip) => ({
                editing: false,
                display: initAddr,
                city: initCity,
                zip: initZip,
                results: [],
                error: null,

                enable() {
                    this.editing = true;
                    this.$nextTick(() => {
                        this.$refs.input.focus();
                        this.$refs.input.select();
                    });
                },

                touch() { this.$dispatch('field-touched'); },

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
                    this.touch();
                },

                closeDropdown() {
                    this.results = [];
                }
            }));

            Alpine.data('passwordManager', () => ({
                editing: false,
                p1: '',
                p2: '',
                errorType: null,

                enable() {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.p1input.focus());
                },

                check() {
                    this.$dispatch('field-touched');
                    
                    if (this.p1.length > 0) {
                        if (this.p1.length < 8) {
                            this.errorType = 'length';
                            this.$dispatch('field-error', true);
                            return;
                        }

                        if (this.p1 !== this.p2) {
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
@endsection