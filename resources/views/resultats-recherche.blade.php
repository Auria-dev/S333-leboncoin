@extends('layout')

@section('title', 'Résultat(s)')

@section('content')

<div style="width: 100%;">
    
    <div class="results-header-form">
        <form method="POST" action="{{ url('resultats') }}">
            @csrf
            <input 
                value="{{ $ville ?? '' }}"
                type="hidden" 
                id="search" 
                name="search" 
            />

            <label for="filtreTypeHebergement">Filtrer par type</label>
            <div class="filter-row">
                <select name="filtreTypeHebergement" id="filtreTypeHebergement">
                    <option value="">Tous les types</option>
                    @if(isset($types))
                        @foreach($types as $th)
                            <option value="{{ $th->nom_type_hebergement }}" {{ (isset($typeSelectionner) && $typeSelectionner === $th->nom_type_hebergement) ? 'selected':'' }}>
                                {{ $th->nom_type_hebergement }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <input type="submit" class="submit-btn" value="Filtrer"/>
            </div>
        </form>
        
        <p style="margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem;">
            {{ isset($annonces) ? count($annonces) : 0 }} résultats trouvés
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
                            {{ $annonce->adresse_annonce }} &bull; {{ $annonce->ville->nomville ?? 'Ville' }}
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