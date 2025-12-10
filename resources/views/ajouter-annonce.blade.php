@extends('layout')

@section('title', 'Déposer une annonce')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="center-container">

    <form class="form-ajouter add-annonce-form" method="POST" action="{{ url('ajouter_annonce') }}" enctype="multipart/form-data">
        @csrf   
        <input type="file" name="file[]" id="fileInput" accept=".png, .jpeg, .jpg" multiple required>  

        <div id="previewsContainer" class="previews-container"></div>

        <label>Titre</label>
        <input type="text" id="titre" name="titre" placeholder="Maison 3 chambres centre d'Annecy" required>

        <div>
            <label for="DepotTypeHebergement">Type d'hébergement</label>
            <select name="DepotTypeHebergement" id="DepotTypeHebergement">
                @if(isset($types))
                    @foreach($types as $th)
                        <option value="{{ $th->nom_type_hebergement }}" @selected(request('DepotTypeHebergement') == $th->nom_type_hebergement)>
                            {{ $th->nom_type_hebergement }}
                        </option>
                    @endforeach
                 @endif
            </select>
        </div>

        <div class="input-groupe" 
                x-data="{
                    query: '{{ old('adresse') }}',
                    city: '{{ old('ville') }}',
                    zip: '{{ old('code_postal') }}',
                    results: [],
                    showResults: false,

                    selectAddress(feature) {
                        this.query = feature.properties.label;
                        
                        this.city = feature.properties.city;
                        this.zip = feature.properties.postcode;
                        
                        this.showResults = false;
                    },

                    async search() {
                        if (this.query.length < 3) {
                            this.results = [];
                            return; 
                        }

                        try {
                            let response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(this.query)}&limit=5&autocomplete=1`);
                            
                            if (!response.ok) throw new Error('Network error');
                            
                            let data = await response.json();
                            
                            this.results = data.features; 
                            this.showResults = true;

                        } catch (e) {
                            console.error(e);
                        }
                    }
                }"
                @click.outside="showResults = false"
                class="input-groupe relative">

                <label for="adresse">Adresse</label>

                <input 
                    type="text" 
                    id="depot_adresse" 
                    name="depot_adresse" 
                    x-model="query"
                    @input.debounce.100ms="search()"
                    placeholder="9 Rue de l'Arc en Ciel, 74940 Annecy" 
                    required
                    autocomplete="off"
                    class="@error('depot_adresse') is-invalid @enderror"
                >

                <input type="hidden" name="ville" x-model="city">
                <input type="hidden" name="code_postal" x-model="zip">

                @error('depot-adresse')
                    <div class="text-error">{{ $message }}</div>
                @enderror

                <ul x-show="showResults && results.length > 0" 
                    class="autocomplete-dropdown"
                    x-transition>
                    
                    <template x-for="feature in results" :key="feature.properties.id">
                        <li @click="selectAddress(feature)" 
                            class="suggestion-item"
                            @mouseenter="$el.style.backgroundColor = '#f8f9fa'"
                            @mouseleave="$el.style.backgroundColor = 'white'">
                            
                            <span x-text="feature.properties.label" class="font-bold block"></span>
                            
                            <span class="text-sm text-muted-color">
                                <span x-text="feature.properties.postcode"></span> 
                                <span x-text="feature.properties.city"></span>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>

        <div class="annonce-group">
            <div>
                <label>Nombre de personnes maximum</label>
                <input type="number" id="nb_pers" name="nb_pers" placeholder="4" min="1" required>
            </div>

            <div>
                <label>Nombre de chambres</label>
                <input type="number" id="nb_chambres" name="nb_chambres" placeholder="3" min="1" required>
            </div>
        </div>

        <div class="annonce-group">
            <div>
                <label>Nombre de bébés maximum</label>
                <input type="number" id="nb_bebes" name="nb_bebes" placeholder="2" min="0" required>
            </div>

            <div>
                <label>Nombre d'animaux maximum</label>
                <input type="number" id="nb_animaux" name="nb_animaux" placeholder="2" min="0" required>
            </div>
        </div>

        <div class="annonce-group">
            <div>
                <label>Nombre de nuits minimum</label>
                <input type="number" id="nb_nuits" name="nb_nuits" placeholder="2" min="1" required>
            </div>

            <div>
                <label>Prix par nuit</label>
                <input type="number" step="0.01" id="prix_nuit" name="prix_nuit" placeholder="70,50€" required>
            </div>

            <div>
                <label>Arrivée</label>
                <input type="time" id="heure_arr" name="heure_arr" placeholder="09:00"  required>
            </div>

            <div>
                <label>Départ</label>
                <input type="time" id="heure_dep" name="heure_dep" placeholder="17:00"  required>
            </div>
        </div>

        <div class="form-group">
            <label>Equipements</label>
            <div>
                @if(isset($equipements))
                    @foreach($equipements as $eq)
                        <label class="pill-label">
                            <input type="checkbox" 
                                name="DepotEquipement[]" 
                                value="{{ $eq->nom_equipement }}"
                                @checked(in_array($eq->nom_equipement, request('DepotEquipement', [])))>
                            
                            <span class="pill-content">
                                <span class="icon-wrapper">
                                    <span class="checkmark-draw"></span>
                                </span>
                                <span>{{ $eq->nom_equipement }}</span>
                            </span>
                        </label>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="form-group mt-4">
            <label>Services</label>
            <div>
                @if(isset($services))
                    @foreach($services as $sv)
                        <label class="pill-label">
                            <input type="checkbox" 
                                name="DepotService[]" 
                                value="{{ $sv->nom_service }}"
                                @checked(in_array($sv->nom_service, request('DepotService', [])))>
                            
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

        <label>Description</label>
        <textarea id="desc" name="desc" placeholder="Maison chaleureuse..." rows="5" required></textarea>
        
        <div class="mt-md">
        <input type="submit" value="Déposer" class="submit-btn">

        </div>
    </form>

    </div>
    <script>
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
                            img.style.maxWidth = '200px';
                            img.style.maxHeight = '200px';

                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    });
</script>
@endsection