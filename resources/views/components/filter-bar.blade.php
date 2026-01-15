@props([
    'action', 
    'placeholder' => 'Rechercher...',
    'withLocation' => false,
    'withDates' => false,
    'withPrice' => false,
    'withGuests' => false,
    'withType' => false,
    'types' => [],
    'customSlot' => false 
])

<div class="results-header-form">
    <form method="GET" action="{{ $action }}" class="filter-form-flex">
        
        @if($withLocation)
        <div class="filter-group grow-2" 
             x-data="{
                query: '{{ request('search') }}',
                results: [],
                showResults: false,
                selectLocation(name) {
                    this.query = name;
                    this.showResults = false;
                },
                async search() {
                    if (this.query.length < 2) { this.results = []; return; }
                    try {
                        let response = await fetch(`{{ route('locations.search') }}?query=${this.query}`); 
                        if (!response.ok) throw new Error('Network response');
                        this.results = await response.json();
                        this.showResults = true;
                    } catch (e) { console.error(e); }
                }
             }"
             @click.outside="showResults = false">
            
            <label class="filter-label">Lieu</label>
            <div class="input-wrapper">
                <input 
                    type="text" name="search" 
                    x-model="query"
                    @input.debounce.300ms="search()"
                    placeholder="{{ $placeholder }}"
                    autocomplete="off"
                />
                <div class="input-icon-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>

            <ul x-show="showResults && query.length >= 2" style="display: none;" class="autocomplete-dropdown">
                <template x-for="result in results" :key="result.id">
                    <li @click="selectLocation(result.name)" class="suggestion-item">
                        <span x-text="result.name" class="suggestion-name"></span>
                        <span x-text="result.type" class="suggestion-badge"></span>
                    </li>
                </template>
                <li x-show="results.length === 0" class="suggestion-empty">
                    Aucun résultat
                </li>
            </ul>
        </div>
        @endif

        @if($withDates)
        <div class="filter-group">
            <label class="filter-label">Dates</label>
            <div class="date-group-row">
                <input type="date" name="datedebut" value="{{ request('datedebut') }}" placeholder="Début">
                <span class="date-sep">➝</span>
                <input type="date" name="datefin" value="{{ request('datefin') }}" placeholder="Fin">
            </div>
        </div>
        @endif

        @if($withType)
        <div class="filter-group">
            <label class="filter-label">Type</label>
            <select name="type">
                <option value="">Tous</option>
                @foreach($types as $t)
                    <option value="{{ $t->nom_type_hebergement }}" @selected(request('type') == $t->nom_type_hebergement)>
                        {{ $t->nom_type_hebergement }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        @if($withGuests)
        <div class="filter-group">
            <label class="filter-label">Voyageurs</label>
            <div class="side-inputs">
                <input type="number" name="nbVoyageurs" placeholder="Adultes" min="1" value="{{ request('nbVoyageurs') }}" style="width: 70px;">
                <input type="number" name="nbBebes" placeholder="Bébés" min="0" value="{{ request('nbBebes') }}" style="width: 70px;">
            </div>
        </div>
        @endif

        @if($withPrice)
        <div class="filter-group">
            <label class="filter-label">Prix (€)</label>
            <div class="side-inputs">
                <input type="number" name="prixMin" placeholder="Min" min="0" value="{{ request('prixMin') }}" style="width: 70px;">
                <input type="number" name="prixMax" placeholder="Max" min="0" value="{{ request('prixMax') }}" style="width: 70px;">
            </div>
        </div>
        @endif

        @if($customSlot)
            <div class="filter-group">
                {{ $slot }}
            </div>
        @endif

        <div class="filter-actions">
            <button type="submit" class="submit-btn filter-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/></svg>
                Filtrer
            </button>
        </div>
        
    </form>
    
    @if(request()->anyFilled(['search', 'datedebut', 'datefin', 'type', 'nbVoyageurs', 'prixMin', 'statut']))
        <a href="{{ $action }}" class="reset-link">Réinitialiser</a>
    @endif
</div>