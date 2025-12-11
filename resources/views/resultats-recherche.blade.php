@extends('layout')

@section('title', 'Résultat(s)')

@section('content')

<div class="page-wrapper">
    <div class="results-header-form">
        <form method="get" action="{{ url('resultats') }}">
            @csrf
            <div class="filter-row">
                <div class="full-width search-container" 
                    x-data="{
                        query: '{{ old('search', request('search')) }}',
                        results: [],
                        showResults: false,
                        selectLocation(name) {
                            this.query = name;
                            this.showResults = false;
                        },
                        async search() {
                            try {
                                let response = await fetch(`{{ route('locations.search') }}?query=${this.query}`);
                                if (!response.ok) throw new Error('Network response was not ok');
                                this.results = await response.json();
                                this.showResults = true;
                            } catch (e) {
                                console.error(e);
                            }
                        }
                    }"
                    @click.outside="showResults = false">

                    <label for="search">Sélectionnez un lieu</label>
                    <div class="input-groupe">
                        <input 
                            type="text" id="search" name="search" 
                            x-model="query"
                            value="{{ old('search', request('search')) }}"
                            @input.debounce.300ms="search()"
                            placeholder="Paris, Haute-Savoie, Rhône-Alpes..." 
                            required autocomplete="off"
                        />
                        <div class="input-icon input-icon-search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                        </div>
                    </div>

                    <ul x-show="showResults && query.length >= 1" style="display: none;" class="autocomplete-dropdown">
                        <template x-for="result in results" :key="result.name + result.type">
                            <li @click="selectLocation(result.name)" class="suggestion-item">
                                <span x-text="result.name" class="suggestion-name"></span>
                                <span x-text="result.type" class="suggestion-badge"></span>
                            </li>
                        </template>
                        <li x-show="results.length === 0" class="suggestion-empty">
                            Aucun lieu trouvé pour "<span x-text="query"></span>"
                        </li>
                    </ul>
                </div>

                <div class="filters-supp">

                    <div>
                        <label for="filtreTypeHebergement">Filtrer par type</label>
                        <select name="filtreTypeHebergement" id="filtreTypeHebergement">
                            <option value="">Tous les types</option>
                            @if(isset($types))
                                @foreach($types as $th)
                                    <option value="{{ $th->nom_type_hebergement }}" @selected(request('filtreTypeHebergement') == $th->nom_type_hebergement)>
                                        {{ $th->nom_type_hebergement }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
            
                    <div class="input-groupe">
                        <label for="filtreDates">Dates du séjour</label>
                        <input type="date" id="filtreDates" data-picker-dual="true" data-target-start="datedebut" data-target-end="datefin"/>
                        <input type="hidden" name="datedebut" id="datedebut" value="{{ request('datedebut') }}">
                        <input type="hidden" name="datefin" id="datefin" value="{{ request('datefin') }}">
                    </div>

                    <div>
                        <label for="nbVoyageurs">Nombre de voyageurs</label>
                        <input type="number" id="nbVoyageurs" name="nbVoyageurs" min="1" value="{{ old('nbVoyageurs', request('nbVoyageurs', 1)) }}" required/>
                    </div>
                    <div>
                        <label for="nbBebes">Nombre de bébés</label>
                        <input type="number" id="nbBebes" name="nbBebes" min="0" value="{{ old('nbBebes', request('nbBebes', 0)) }}" />
                    </div>
                    <div>
                        <label for="nbChambres">Nombre de chambres</label>
                        <input type="number" id="nbChambres" name="nbChambres" min="1" value="{{ old('nbChambres', request('nbChambres', 1)) }}" />
                    </div>
                    <div>
                        <label for="prixMin">Prix min (€)</label>
                        <input type="number" id="prixMin" name="prixMin" min="0" value="{{ old('prixMin', request('prixMin', 0)) }}" />
                    </div>
                    <div>
                        <label for="prixMax">Prix max (€)</label>
                        <input type="number" id="prixMax" name="prixMax" min="0" value="{{ old('prixMax', request('prixMax', 1000)) }}" />
                    </div>
                    
                    <input id="filter-btn" type="submit" class="submit-btn" value="Filtrer"/>
                </div>
            </div>
        </form>

        <div class ="results-summary">
            
            <p class="results-count">
                @if (isset($annonces) && count($annonces) > 1)
                {{ count($annonces) }} résultats trouvés
                @elseif (isset($annonces) && count($annonces) == 1)
                1 résultat trouvé
                @else
                Aucun résultat
                @endif
            </p>
            <div id="save-search-btn" class="submit-btn">Sauvegarder recherche</div>
        </div>

        
    </div>

    <div class="search-results-container">
        
        <div class="listings-pane">
            @if(isset($annonces) && count($annonces) > 0)
                @foreach($annonces as $annonce)
                    <a class="annonce-card" href="{{ url('annonce/'.strval($annonce->idannonce)) }}">
                        <div class="annonce-photo">
                            @if(isset($annonce->photo) && count($annonce->photo) > 0)
                                <img loading="lazy" src="{{ $annonce->photo[0]->nomphoto }}" alt="Photo annonce"/>
                            @else
                                <div class="no-photo">Sans photo</div>
                            @endif
                        </div>

                        <div class="annonce-info">
                            <div class="annonce-header">
                                <h2 class="card-title">{{ $annonce->titre_annonce }}</h2>
                                <span class="card-price">{{ ceil($annonce->prix_nuit) }}€ / nuit</span>
                            </div>
                            
                            <div class="card-meta">
                                {{ $annonce->type_hebergement->nom_type_hebergement ?? 'Type inconnu' }} &bull; 
                                {{ $annonce->nb_personnes_max }} pers &bull; 
                                {{ $annonce->nb_bebe_max }} bébé
                            </div>

                            <div class="card-footer">
                                <span class="location-badge">
                                    <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $annonce->adresse_annonce }} &bull; {{ $annonce->ville->nom_ville ?? 'Ville' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="no-results-box">
                    <h3>Aucun résultat trouvé</h3>
                    <p>Essayez d'élargir votre zone de recherche.</p>
                </div>
            @endif
        </div>

        <div class="map-pane">
            <div id="maCarte"></div> 
        </div>

    </div>
</div>

<div class="light-box-save-search hidden">
    <form action="{{ url('sauvegarder_recherche') }}" method="POST" class="inline-form">
        @csrf 
        
        <input type="hidden" name="search" value="{{ $search ?? '' }}">
        <input type="hidden" name="nbVoyageurs" value="{{ $nbVoyageurs ?? '' }}">
        <input type="hidden" name="prixMin" value="{{ $prixMin ?? '' }}">
        <input type="hidden" name="prixMax" value="{{ $prixMax ?? '' }}">
        <input type="hidden" name="datedebut" value="{{ $datedebut ?? '' }}">
        <input type="hidden" name="datefin" value="{{ $datefin ?? '' }}">
        <input type="hidden" name="filtreTypeHebergement" value="{{ $filtreTypeHebergement ?? '' }}">

        <div class="light-box-content">
            <h2 class="text-center">Sauvegarder cette recherche</h2>
            <div>
                <h3>Nom de la recherche</h3>
                <input type="text" name="nom_sauvegarde" placeholder="'Vacances de noël'" autofocus required />
            </div>
            <div>
                <h3>Lieu</h3>
                <input type="text" value="{{$search ?? 'Aucun' }}" disabled/>
            </div>
            <div>
                <h3>Type d'hebergement</h3>
                <input type="text" value="{{$filtreTypeHebergement ?? 'Tous'}}" disabled/>
            </div>
            <div class="date-inputs">
                <div>
                    <h3>Date de debut</h3>
                    <input type="text" value="{{$datedebut ?? 'NULL' }}" class="date" disabled/>
                </div>
                <div>
                    <h3>Date de fin</h3>
                    <input type="text" value="{{$datefin ?? 'NULL' }}" class="date" disabled/>
                </div>
            </div>

            <div class="lightbox-actions">
                <button class="other-btn btn-close-light-box">Annuler</button>
                <button class="submit-btn btn-confirm-save-search">Confirmer</button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        
        const lightBox = document.querySelector('.light-box-save-search');
        const btnSaveSearch = document.getElementById('save-search-btn');
        const btnCloseLightBox = document.querySelector('.btn-close-light-box');
        const btnConfirmSaveSearch = document.querySelector('.btn-confirm-save-search');


        btnSaveSearch.addEventListener('click', () => {
            console.log('Ouverture de la light box de sauvegarde de recherche');
            lightBox.style.display = 'flex';
        });

        btnCloseLightBox.addEventListener('click', (e) => {
            e.preventDefault();
            lightBox.style.display = 'none';
        });






        var map = L.map('maCarte', {
            zoomControl: false
        }).setView([48.8566, 2.3522], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.control.zoom({
            position: 'topright'
        }).addTo(map);



        const annonces = @json($annonces);
        const annoncesListe = Object.values(annonces);
        console.log(annonces);

        var markersGroup = new L.featureGroup();
        
        annoncesListe.forEach(annonce => {
            if (annonce.latitude && annonce.longitude) {
                console.log('Ajout du marqueur pour l\'annonce ID:', annonce.idannonce);
                const marker = L.marker([annonce.latitude, annonce.longitude]).addTo(map);
                const popupContent = `
                    <strong>${annonce.titre_annonce}</strong><br>
                    Prix: ${annonce.prix_nuit}€ / nuit<br>
                    <a href="/annonce/${annonce.idannonce}">Voir l'annonce</a>
                `;
                marker.bindPopup(popupContent);
                marker.addTo(markersGroup);
            }
        });

        if (annoncesListe.length > 0 && markersGroup.getLayers().length > 0) {
            map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] 
        });

    }
            
    </script>
@endpush