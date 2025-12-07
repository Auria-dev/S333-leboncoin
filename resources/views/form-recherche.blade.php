@extends('layout')

@section('title', 'Rechercher')

@section('content')

    <form method="get" action="{{ url('resultats') }}" style="width: 100%; max-width: 400px; margin: 0 auto;">
        @csrf

        <div class="full-width search-container" 
            x-data="{
                query: '',
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
                    type="text" 
                    id="search" 
                    name="search" 
                    x-model="query"
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

        <div class="full-width input-groupe">
            <label for="vyg">Dates du séjour</label>
            
            <input 
                type="date" 
                id="vyg"
                data-picker-dual="true"
                data-target-start="datedebut" 
                data-target-end="datefin"
            />

            <input type="hidden" name="datedebut" id="datedebut">
            <input type="hidden" name="datefin" id="datefin">
        </div>
        
        <div class="full-width">
            <input type="submit" class="submit-btn" value="Rechercher" />
        </div>

    </form>
@endsection

