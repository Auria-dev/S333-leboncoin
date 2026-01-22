@extends('layout')
@section('title', 'Profil')
@section('content')
    <div>

    <!-- Ici $proprietaire c'est jeankevin  -->
    <div id="info-proprio">
        @if($proprietaire->photo_profil === null)
            <img src="/profil/photo-profil.jpg" id="img-proprio" class="profile-img">
        @else
            <img src="{{ $proprietaire->photo_profil }}" id="img-proprio" class="profile-img">
        @endif
        <div>
            <p style="font-size: 25px;"><strong> {!! $proprietaire->displayName() !!} </strong></p>
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
            <div style="display: flex; margin-top: 1rem; gap: 2rem;">
                @if ($proprietaire->getTypeParticulier() == 'Propriétaire' || $proprietaire->getTypeParticulier() == 'Locataire & Propriétaire')
                    @if($proprietaire->telephone_verifie)
                    <div class="verif-proprio"> 
                       <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z" fill="#4A3B32"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z" fill="#4A3B32"></path> </g></svg> 
                        <p> Téléphone vérifié </p>
                    </div>
                    @endif

                    @if($proprietaire->particulier->piece_identite !== null)
                    <div class="verif-proprio"> 
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z" fill="#4A3B32"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z" fill="#4A3B32"></path> </g></svg> 
                        <p> Identité vérifiée </p>
                    </div>
                    @endif
                @else 
                    <div class="verif-proprio"> 
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z" fill="#4A3B32"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z" fill="#4A3B32"></path> </g></svg> 
                        <p> Entreprise </p>
                    </div>
                    <div class="verif-proprio"> 
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z" fill="#4A3B32"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z" fill="#4A3B32"></path> </g></svg> 
                        <p> {{ $proprietaire->entreprise->secteur->nom_secteur }} </p>
                    </div>
                @endif 
            </div>        
        </div>
    </div>

    <div class="proprio-annonces-title"><strong>Annonce(s) publiées par {{$proprietaire->nom_utilisateur . ' ' . $proprietaire->prenom_utilisateur}}</strong></div>

        <div class="res-scroller">
            @forelse($proprietaire->annonce as $similaire)
            <a class="similaire-card" href="{{ url('annonce/'.strval($similaire->idannonce)) }}">
                <div class="similaire-card-img">
                    @if(isset($similaire->photo) && count($similaire->photo) > 0)
                        <img class="similaire-image" loading="lazy" src="{{ $similaire->photo[0]->nomphoto }}" alt="Photo annonce"/>
                    @else
                        <div class="no-photo-placeholder">
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