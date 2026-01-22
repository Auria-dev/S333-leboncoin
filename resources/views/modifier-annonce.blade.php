@extends('layout')

@section('title', 'Ajouter un type d\'hébergement')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="center-container">
        <form class="form-ajouter add-annonce-form" method="POST" action="{{ url('modifier_annonce/update/'.$annonce->idannonce) }}" 
            x-data="formManager()"
            @submit.prevent="submitForm" enctype="multipart/form-data">
            @csrf  
            @method('PUT')
            <div class="previews-container">
                @if(isset($annonce->photo))
                    @foreach($annonce->photo as $photo)  
                        <img src="{{$photo->nomphoto}}" alt="" class="img-depot-annonce">
                    @endforeach
                @endif
            </div>
            <div id="previewsContainer" class="previews-container"></div>
            <div class="upload-container">
                <div class="button-group">
                    <button type="button" id="customSelectBtn" class="btn-select">
                        Choisir des images
                    </button>
                </div>
        
                <span id="fileChosen">Aucune image sélectionnée</span>
        
                <input type="file" name="file[]" id="fileInput" accept=".png, .jpeg, .jpg" multiple hidden> 
            </div> 
            
            <div class="field-group" x-data="inputField()">
                <label class="font-bold">Titre</label>
                <div class="input-wrapper">
                    <input type="text" id="titre" name="titre" value="{{old('titre', $annonce->titre_annonce)}}" required
                           :disabled="!editing" x-ref="input" @input="touch()">
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                @error('titre') <div class="text-error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="DepotTypeHebergement">Type d'hébergement</label>
                <select name="DepotTypeHebergement" id="DepotTypeHebergement">
                    @if(isset($types))
                        @foreach($types as $th)
                            <option value="{{ $th->nom_type_hebergement }}" @selected(old('DepotTypeHebergement', $annonce->type_hebergement->nom_type_hebergement) == $th->nom_type_hebergement)>
                                {{ $th->nom_type_hebergement }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="field-group" 
                x-data="addressField(
                    '{{ addslashes(old('adresse', $annonce->adresse_annonce)) }}', 
                    '{{ addslashes(old('ville', $ville->nom_ville ?? '')) }}', 
                    '{{ addslashes(old('code_postal', $ville->code_postal ?? '')) }}'
                )"
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

                    <button type="button" class="action-btn edit-trigger" @click="enable()">
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

            <div class="annonce-group">
                <div class="field-group" x-data="inputField()">
                    <label>Nombre de personnes maximum</label>
                    <div class="input-wrapper">
                        <input type="number" id="nb_pers" name="nb_pers" value="{{old('nb_pers', $annonce->nb_personnes_max)}}" min="1" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('nb_pers') <div class="text-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label>Nombre de chambres</label>
                    <div class="input-wrapper">
                        <input type="number" id="nb_chambres" name="nb_chambres" value="{{old('nb_chambres', $annonce->nombre_chambre)}}" min="1" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('nb_pers') <div class="text-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="annonce-group">
                <div class="field-group" x-data="inputField()">
                    <label>Nombre de bébés maximum</label>
                    <div class="input-wrapper">
                        <input type="number" id="nb_bebes" name="nb_bebes" value="{{old('nb_bebes', $annonce->nb_bebe_max)}}" min="0" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('nb_bebes') <div class="text-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label>Nombre d'animaux maximum</label>
                    <div class="input-wrapper">
                        <input type="number" id="nb_animaux" name="nb_animaux" value="{{old('nb_animaux', $annonce->nb_animaux_max)}}" min="0" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('nb_animaux') <div class="text-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="annonce-group">
                <div class="field-group" x-data="inputField()">
                    <label>Minimum de nuits</label>
                    <div class="input-wrapper">
                       <input type="number" id="nb_nuits" name="nb_nuits" value="{{old('nb_nuits', $annonce->nb_nuit_min)}}" min="1" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('nb_nuits') <div class="text-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label>Prix par nuit</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" id="prix_nuit" name="prix_nuit" value="{{old('prix_nuit', $annonce->prix_nuit)}}" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('prix_nuit') <div class="text-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label>Arrivée</label>
                    <div class="input-wrapper">
                        <input type="time" id="heure_arr" name="heure_arr" value="{{old('heure_arr', \Carbon\Carbon::parse($annonce->heure_arrivee)->format('H:i'))}}" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('heure_arr') <div class="text-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label>Départ</label>
                    <div class="input-wrapper">
                        <input type="time" id="heure_dep" name="heure_dep" value="{{old('heure_dep', \Carbon\Carbon::parse($annonce->heure_depart)->format('H:i'))}}" required
                            :disabled="!editing" x-ref="input" @input="touch()">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                    </div>
                    @error('heure_dep') <div class="text-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <div>
                    @if(isset($equipements))
                        @foreach ($equipements as $equipements)
                            <div>
                                <label style="font-size: 18px">{{ $equipements->first()->categorieEquipement->nom_categorie }}</label>
                                <div>
                                    @foreach($equipements as $eq)
                                        <label class="pill-label">
                                            <input type="checkbox" 
                                            name="DepotEquipement[]" 
                                            value="{{ $eq->idequipement }}" 
                                            @checked(in_array($eq->idequipement, old('DepotEquipement', $annonce->equipement->pluck('idequipement')->toArray())))>
                                            
                                            <span class="pill-content">
                                                <span class="icon-wrapper">
                                                    <span class="checkmark-draw"></span>
                                                </span>
                                                <span>{{ $eq->nom_equipement }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="form-group mt-4">
                <label style="font-size: 18px">Services</label>
                <div>
                    @if(isset($services))
                        @foreach($services as $sv)
                            <label class="pill-label">
                                <input type="checkbox" 
                                name="DepotService[]" 
                                value="{{ $sv->idservice }}" 
                                @checked(in_array($sv->idservice, old('DepotService', $annonce->service->pluck('idservice')->toArray())))>
                                
                                <span class="pill-content">
                                    <span class="icon-wrapper">
                                        <span class="checkmark-draw"></span>
                                    </span>
                                    <span>{{ $sv->nom_service }}</span>
                                </span>
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="field-group" x-data="inputField()">
                <label>Description</label>
                <div class="input-wrapper">
                    <textarea id="desc" name="desc" rows="5" required :disabled="!editing" x-ref="input" @input="touch()">{{old('desc', $annonce->description_annonce)}}</textarea>
                    <button type="button" class="action-btn edit-trigger" @click="enable()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                </div>
                @error('desc') <div class="text-error">{{ $message }}</div> @enderror
            </div>
            
            <div class="mt-md">
                <input type="submit" value="Modifier l'annonce"  class="submit-btn-custom">
            </div>
        </form>

    </div>

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
                    setTimeout(() => {
                        this.$refs.input.focus();
                        this.$refs.input.select();
                    }, 50);
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


    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const container = document.getElementById('previewsContainer');

        fileInput.addEventListener('change', function(event) {
            container.innerHTML = ''; 

            const files = event.target.files;
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result; 
                            img.alt = 'Aperçu photo ' + (i + 1);
                            img.classList.add("img-depot-annonce");

                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    });

    const realFileBtn = document.getElementById("fileInput");
    const customBtn = document.getElementById("customSelectBtn");
    const customTxt = document.getElementById("fileChosen");

    customBtn.addEventListener("click", function(e) {
        e.preventDefault();
        realFileBtn.click();
    });

    realFileBtn.addEventListener("change", function() {
        const count = realFileBtn.files.length;

        if (count === 0) {
            customTxt.textContent = "Aucune image sélectionnée";
        } else if (count === 1) {
            customTxt.textContent = realFileBtn.files[0].name;
        } else {
            customTxt.textContent = count + " images sélectionnées";
        }
    });
</script>
@endsection