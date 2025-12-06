@extends('layout')
@section('title', 'Profil')
@section('content')
    <div>

    <!-- Ici $proprietaire c'est jeankevin  -->
    <div id="info-proprio">
         @if($proprietaire->photo_profil === null)
            <img src="/images/photo-profil.jpg" id="img-proprio" style="width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:1rem;">
        @else
            <img src="{{ $proprietaire->photo_profil }}" id="img-proprio" style="width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:1rem;">
        @endif
        <div>
            <p><strong>
                <span style="text-transform: uppercase;"> {{ $proprietaire->nom_utilisateur }} </span> 
                    {{ $proprietaire->prenom_utilisateur }} 
            </strong></p>
            <div class="notation">
                <p> <span class="stars" style="--rating: {{ $moyenneAvis }};"></span> {{ $moyenneAvis . ' (' . $nbAvis . ' avis) '}} </p>
            </div>
            <p> {{ $proprietaire->ville->departement->nom_departement }} </p>
            <p> Membre depuis {{ strtolower(\Carbon\Carbon::parse($proprietaire->date_creation)->translatedFormat('F Y')) }} </p>
            <p> 
                @if(isset($proprietaire->annonce) && count($proprietaire->annonce) > 1)
                    {{ $proprietaire->annonce->count() }} annonces 
                @else 
                    {{ $proprietaire->annonce->count() }} annonce
                @endif
            </p>           
        </div>
    </div>

    <div style="margin-bottom: 1rem; margin-left: 1rem; color: #4A3B32;"><strong>Annonce(s) publiées par {{$proprietaire->nom_utilisateur . ' ' . $proprietaire->prenom_utilisateur}}</strong></div>

        <div class="res-scroller">
            @forelse($proprietaire->annonce as $similaire)
            <a class="similaire-card" href="{{ url('annonce/'.strval($similaire->idannonce)) }}">
                <div class="similaire-card-img">
                    @if(isset($similaire->photo) && count($similaire->photo) > 0)
                        <img class="similaire-image" loading="lazy" src="{{ $similaire->photo[0]->nomphoto }}" alt="Photo annonce"/>
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color: var(--text-muted);">
                            Sans photo
                        </div>
                    @endif
                </div>
                <div class="similaire-info">
                        <h2 class="similaire-card-title">{{ $similaire->titre_annonce }}</h2>
                        <span class="similaire-card-price">{{ ceil($similaire->prix_nuit) }}€ / nuit</span>
                        
                        <div class="similaire-card-meta">
                            {{ $similaire->type_hebergement->nom_type_hebergement ?? 'Type inconnu' }} &bull; 
                            {{ $similaire->nb_personnes_max }} pers &bull; 
                            {{ $similaire->nb_bebe_max }} bébé
                        </div>

                        <div class="card-footer">
                            <span class="location-badge">
                                <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                {{ $similaire->adresse_annonce }} &bull; {{ $similaire->ville->nom_ville ?? 'Ville' }}
                            </span>
                        </div>
                </div>
            </a>
            @empty
                <div class="res-empty">
                    <p>Aucun favoris.</p>
                </div>
            @endforelse
            </div>
        </div>
    </div>
    

    </div>
@endsection
@push('scripts')
@endpush