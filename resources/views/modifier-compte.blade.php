@extends('layout')

@section('title', 'Modifier mon compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .text-danger { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; animation: fadeIn 0.3s ease-in; }
        .is-invalid { border-color: #dc3545 !important; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        input[readonly], select:disabled {
            color: var(--text-muted);
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-input);
            cursor: default;
        }

        input:not([readonly]), select:not([disabled]) {
            border-color: var(--primary);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding-right: 40px;
        }

        .edit-icon {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.2s;
            padding: 5px;
        }

        .edit-icon:hover {
            color: var(--primary-hover);
        }
    </style>

    <form action="{{ url('compte/update') }}" method="POST" 
          style="display: flex; flex-direction: column; gap: 1.25rem;"
          x-data="{
              isDirty: false,
              initialState: '',
              
              init() {
                  this.$nextTick(() => {
                      this.initialState = JSON.stringify(Object.fromEntries(new FormData($el).entries()));
                  });
              },

              checkChanges() {
                  const currentState = JSON.stringify(Object.fromEntries(new FormData($el).entries()));
                  this.isDirty = (this.initialState !== currentState);
              }
          }"
          @input="checkChanges()" 
          @change="checkChanges()">
            
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;">
                    <strong>Oups !</strong> Vérifiez votre saisie.
                </div>
            @endif

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="side-by-side">
                <div class="input-groupe" style="flex: 1;" x-data="{ locked: true }">
                    <label for="prenom">Prénom</label>
                    <div class="input-wrapper">
                        <input type="text" id="prenom" name="prenom" 
                               value="{{ old('prenom', $user->prenom_utilisateur) }}" 
                               required 
                               :readonly="locked"
                               x-ref="field"
                               class="@error('prenom') is-invalid @enderror">
                        
                        <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                    </div>
                    @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="input-groupe" style="flex: 1;" x-data="{ locked: true }">
                    <label for="nom">Nom</label>
                    <div class="input-wrapper">
                        <input type="text" id="nom" name="nom" 
                               value="{{ old('nom', $user->nom_utilisateur) }}" 
                               required 
                               :readonly="locked"
                               x-ref="field"
                               class="@error('nom') is-invalid @enderror">
                        
                        <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                    </div>
                    @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="input-groupe" x-data="{ locked: true }">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', $user->mail) }}" 
                           required 
                           :readonly="locked"
                           x-ref="field"
                           class="@error('email') is-invalid @enderror">
                    
                    <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                </div>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="input-groupe" x-data="{ locked: true }">
                <label for="telephone">Téléphone</label>
                <div class="input-wrapper">
                    <input type="tel" id="telephone" name="telephone" 
                           value="{{ old('telephone', $user->telephone) }}" 
                           required 
                           :readonly="locked"
                           x-ref="field"
                           class="@error('telephone') is-invalid @enderror">
                    
                    <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                </div>
                @error('telephone') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="input-groupe" 
                x-data="{
                    locked: true,
                    query: '{{ old('adresse', $user->adresse_utilisateur) }}',
                    city: '{{ old('ville', $ville->nom_ville ?? '') }}',
                    zip: '{{ old('code_postal', $ville->code_postal ?? '') }}',
                    results: [],
                    showResults: false,

                    selectAddress(feature) {
                        this.query = feature.properties.label;
                        this.city = feature.properties.city;
                        this.zip = feature.properties.postcode;
                        this.showResults = false;
                        $dispatch('input'); // Force checkChanges global
                    },

                    async search() {
                        if (this.query.length < 3) { this.results = []; return; }
                        try {
                            let response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(this.query)}&limit=5&autocomplete=1`);
                            if (!response.ok) throw new Error('Network error');
                            let data = await response.json();
                            this.results = data.features; 
                            this.showResults = true;
                        } catch (e) { console.error(e); }
                    }
                }"
                @click.outside="showResults = false"
                style="position: relative;">

                <label for="adresse">Adresse</label>
                
                <div class="input-wrapper">
                    <input type="text" id="adresse" name="adresse" 
                        x-model="query"
                        :readonly="locked"
                        x-ref="field"
                        @input.debounce.100ms="search()"
                        placeholder="Rechercher une adresse..." required autocomplete="off"
                        class="@error('adresse') is-invalid @enderror">
                    
                    <input type="hidden" name="ville" x-model="city">
                    <input type="hidden" name="code_postal" x-model="zip">

                    <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                </div>

                @error('adresse') <div class="text-danger">{{ $message }}</div> @enderror

                <ul x-show="showResults && results.length > 0" class="autocomplete-dropdown" x-transition>
                    <template x-for="feature in results" :key="feature.properties.id">
                        <li @click="selectAddress(feature)" style="padding: 8px; cursor: pointer"
                            @mouseenter="$el.style.backgroundColor = '#f8f9fa'"
                            @mouseleave="$el.style.backgroundColor = 'white'">
                            <span x-text="feature.properties.label" style="font-weight: bold; display: block;"></span>
                            <span style="font-size: 0.85em; color: #666;">
                                <span x-text="feature.properties.postcode"></span> <span x-text="feature.properties.city"></span>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>

            @if($isEntreprise)
                <div id="entrepriseFields" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="width: 100%; height: 1px; background: var(--border-default); margin: 0.5rem 0;"></div>
                    
                    <div class="input-groupe" x-data="{ locked: true }">
                        <label for="siret">Numéro SIRET</label>
                        <div class="input-wrapper">
                            <input type="text" id="siret" name="siret" 
                                   value="{{ old('siret', $entreprise->numsiret ?? '') }}" 
                                   required 
                                   :readonly="locked"
                                   x-ref="field"
                                   class="@error('siret') is-invalid @enderror">
                            
                            <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </div>
                        </div>
                        @error('siret') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="input-groupe" x-data="{ locked: true }">
                        <label for="secteur">Secteur d'activité</label>
                        <div class="input-wrapper">
                            <select name="secteur" id="secteur" 
                                    class="@error('secteur') is-invalid @enderror" 
                                    required 
                                    :disabled="locked"
                                    x-ref="field">
                                <option value="" disabled>Choisir un secteur</option>
                                @foreach($secteurs as $s)
                                    <option value="{{ $s->nom_secteur }}" 
                                        {{ old('secteur', ($entreprise->idsecteur == $s->idsecteur ? $s->nom_secteur : '')) == $s->nom_secteur ? 'selected' : '' }}>
                                        {{ $s->nom_secteur }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </div>
                        </div>
                        @error('secteur') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    
                    <div style="width: 100%; height: 1px; background: var(--border-default); margin: 0.5rem 0;"></div>
                </div>
            @endif

            <div class="side-by-side">
                <div class="input-groupe" style="flex: 1;" x-data="{ locked: true }">
                    <label for="password">Nouveau mot de passe</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" 
                               placeholder="********" 
                               :readonly="locked"
                               x-ref="field"
                               class="@error('password') is-invalid @enderror">
                        
                        <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                    </div>
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="input-groupe" style="flex: 1;" x-data="{ locked: true }">
                    <label for="password_confirmation">Confirmation</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               placeholder="********" 
                               :readonly="locked"
                               x-ref="field"
                               class="@error('password_confirmation') is-invalid @enderror">
                        
                        <div class="edit-icon" @click="locked = false; $nextTick(() => $refs.field.focus())" title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="side-by-side between">
                <a href="{{ url('/') }}" class="other-btn" style="width:fit-content;" >Annuler</a>
                <input type="submit" value="Enregistrer" class="submit-btn" style="width:fit-content;" disabled>
            </div>
        </form>
@endsection