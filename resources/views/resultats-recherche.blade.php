@extends('layout')

@section('title', 'Résultat(s)')

@section('content')

<div style="width: 100%;">
    <div class="results-header-form">
        <form method="POST" action="{{ url('resultats') }}">
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

                    <label for="search">Selectionner un lieu</label>
                    
                    <div class="input-groupe">
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            x-model="query"
                            value="{{ old('search', request('search')) }}"
                            @input.debounce.300ms="search()"
                            placeholder="Paris, Haute-Savoie, Rhône-Alpes..." 
                            required
                            autocomplete="off"
                        />
                        <div class="input-icon input-icon-search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                        </div>
                    </div>

                    <ul x-show="showResults && query.length >= 1" 
                        style="display: none;" 
                        class="autocomplete-dropdown">
                        
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

                <div>
                    <label for="filtreTypeHebergement">Filtrer par type</label>
                    <select name="filtreTypeHebergement" id="filtreTypeHebergement">
                        <option value="">Tous les types</option>
                        @if(isset($types))
                            @foreach($types as $th)
                                <option 
                                    value="{{ $th->nom_type_hebergement }}"
                                    @selected(request('filtreTypeHebergement') == $th->nom_type_hebergement)
                                >
                                    {{ $th->nom_type_hebergement }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
        
                <div class="input-groupe">
                    <label for="filtreDates">Dates du séjour</label>
                    <input 
                        type="date" 
                        id="filtreDates"
                        data-picker-dual="true"
                        data-target-start="datedebut" 
                        data-target-end="datefin"
                    />

                    <input 
                        type="hidden" 
                        name="datedebut" 
                        id="datedebut" 
                        value="{{ request('datedebut') }}"
                    >
                    <input 
                        type="hidden" 
                        name="datefin" 
                        id="datefin" 
                        value="{{ request('datefin') }}"
                    >
                </div>
                
                <input type="submit" class="submit-btn" value="Filtrer"/>
            </div>
        </form>
        
        <p style="margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem;">
            <!-- {{ isset($annonces) ? count($annonces) : 0 }} -->
            @if (isset($annonces) && count($annonces) > 0)
            {{ count($annonces) }} résultats trouvés
            @else
            Aucun résultat
            @endif
        </p>
    </div>

    <div class="scroll">
    @if(isset($annonces))
        @foreach($annonces as $annonce)
            <a class="annonce-card" href="{{ url('annonce/'.strval($annonce->idannonce)) }}">
                
                <div class="annonce-photo">
                    @if(isset($annonce->photo) && count($annonce->photo) > 0)
                        <img loading="lazy" src="{{ $annonce->photo[0]->nomphoto }}" alt="Photo annonce"/>
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color: var(--text-muted);">
                            Sans photo
                        </div>
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
                        
                        <span class="date-badge">
                            {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    @endif
    </div>
</div>

@endsection