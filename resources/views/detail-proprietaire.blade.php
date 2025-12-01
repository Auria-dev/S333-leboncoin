@extends('layout')
@section('title', 'Profil')
@section('content')
    <div>

    <!-- Ici $proprietaire c'est jeankevin  -->
    <div id="info-proprio">
        <img id="img-proprio" src="/images/maison.jpg"/>
        <div>
            <p><strong>
                <span style="text-transform: uppercase;"> {{ $proprietaire->nom_utilisateur }} </span> 
                    {{ $proprietaire->prenom_utilisateur }}
            </strong></p>
            <p> {{ $proprietaire->adresse_utilisateur . ', ' . $proprietaire->ville->nomville . ' ' . $proprietaire->ville->code_postal}} </p>
            <p> {{ $proprietaire->annonce->count() }} annonces publiées</p>
        </div>
    </div>

    <div style="margin-bottom: 1rem; margin-left: 1rem; color: #4A3B32;"><strong>Autre(s) publication</strong></div>

    <div class="scroll">
        @foreach($proprietaire->annonce as $annonce)
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
    </div>
    

    </div>
@endsection
@push('scripts')
@endpush