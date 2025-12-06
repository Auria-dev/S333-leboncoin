@extends('layout')

@section('title', 'Déposer une annonce')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div style="width: 100%; display: flex; justify-content: center;">

    <form method="POST" action="{{ url('ajouter_annonce') }}" style="display: flex; flex-direction: column; width: 700px; " enctype="multipart/form-data">
        @csrf   
        <input type="file" name="file[]" accept="image/png, image/jpeg, image/jpg" multiple>  

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
                style="position: relative;">

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
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <ul x-show="showResults && results.length > 0" 
                    class="autocomplete-dropdown"
                    x-transition>
                    
                    <template x-for="feature in results" :key="feature.properties.id">
                        <li @click="selectAddress(feature)" 
                            style="padding: 8px; cursor: pointer"
                            @mouseenter="$el.style.backgroundColor = '#f8f9fa'"
                            @mouseleave="$el.style.backgroundColor = 'white'">
                            
                            <span x-text="feature.properties.label" style="font-weight: bold; display: block;"></span>
                            
                            <span style="font-size: 0.85em; color: #666;">
                                <span x-text="feature.properties.postcode"></span> 
                                <span x-text="feature.properties.city"></span>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>

        <label>Nombre de nuits minimum</label>
        <input type="number" id="nb_nuits" name="nb_nuits" placeholder="2" min="1" required>

        <label>Nombre de personness maximum</label>
        <input type="number" id="nb_pers" name="nb_pers" placeholder="4" min="1" required>

        <label>Nombre de bébés maximum</label>
        <input type="number" id="nb_bebes" name="nb_bebes" placeholder="2" min="0" required>

        <label>Nombre d'animaux maximum</label>
        <input type="number" id="nb_animaux" name="nb_animaux" placeholder="2" min="0" required>

        <label>Nombre de chambre</label>
        <input type="number" id="nb_chambres" name="nb_chambres" placeholder="3" min="1" required>

        <div>
            <label for="DepotEquipement">Equipements</label>
            <select name="DepotEquipement[]" id="DepotEquipement" multiple>
                @if(isset($equipements))
                    @foreach($equipements as $eq)
                        <option value="{{ $eq->nom_equipement }}" @selected(request('DepotEquipement') == $eq->nom_equipement)>
                            {{ $eq->nom_equipement }}
                        </option>
                    @endforeach
                 @endif
            </select>
        </div>

        <div>
            <label for="DepotService">Services</label>
            <select name="DepotService[]" id="DepotService" multiple>
                @if(isset($services))
                    @foreach($services as $sv)
                        <option value="{{ $sv->nom_service }}" @selected(request('DepotService') == $sv->nom_service)>
                            {{ $sv->nom_service }}
                        </option>
                    @endforeach
                 @endif
            </select>
        </div>

        <label>Heure d'arrivée</label>
        <input type="time" id="heure_arr" name="heure_arr" placeholder="09:00" required>

        <label>Heure de départ</label>
        <input type="time" id="heure_dep" name="heure_dep" placeholder="17:00" required>

        <label>Description</label>
        <input type="text" id="desc" name="desc" placeholder="Maison chaleureuse..." required>
        
        <label>Prix par nuit</label>
        <input type="number" step="0.01" id="prix_nuit" name="prix_nuit" placeholder="70,50€" required>

        <div style="margin-top: 1rem;">
        <input type="submit" value="Déposer" class="submit-btn">

        </div>
    </form>

    </div>
@endsection