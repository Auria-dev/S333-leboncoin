@extends('layout')

@section('title', 'Dashboard')

@section('content')
    
    <h1>Bonjour {{ $utilisateur->prenom_utilisateur . ' ' . $utilisateur->nom_utilisateur }} </h1>

    <p>Bienvenue dans le tableau de bord de votre compte {{ strtolower($utilisateur->getTypeParticulier()) }}.</p>

    @if ($utilisateur->getTypeParticulier() == 'Propriétaire' || $utilisateur->getTypeParticulier() == 'Locataire & Propriétaire')
    <div class="res-section">
        <p class="section-title">Mes annonces</p>
    
        <div class="res-scroller">
            @forelse($utilisateur->annonce as $similaire)
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
                            <span class="similaire-location-badge">
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
                    <p>Aucune annonce postée.</p>
                </div>
            @endforelse
        </div>
    </div>
    @endif


    @if ($utilisateur->getTypeParticulier() == 'Locataire' || $utilisateur->getTypeParticulier() == 'Locataire & Propriétaire')

    <div class="res-section">
        <p class="section-title">Mes réservations</p>

        <div class="res-scroller">
            @forelse($utilisateur->reservation as $res)
                <a class="res-card" href="{{ url('annonce/'.strval($res->idannonce)) }}" >
                    <div class="res-header">
                        <div>
                            <h3 class="res-id">Annonce #{{ $res->idannonce }}</h3>
                            <span class="res-dates">
                                {{ \Carbon\Carbon::parse($res->date_debut_resa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($res->date_fin_resa)->format('d/m/Y') }}
                            </span>
                        </div>
                        <span class="res-badge">
                            {{ $res->statut_reservation }}
                        </span>
                    </div>

                    <hr class="res-divider">

                    <div class="res-grid">
                        <div>
                            <div class="res-label">Voyageurs</div>
                            
                            <div class="res-info-row">
                                <svg class="res-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>{{ $res->nb_adultes }} Adulte(s)</span>
                            </div>

                            @if($res->nb_enfants > 0)
                                <div class="res-info-row">
                                    <svg class="res-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-person-standing-icon lucide-person-standing"><circle cx="12" cy="5" r="1"/><path d="m9 20 3-6 3 6"/><path d="m6 8 6 2 6-2"/><path d="M12 10v4"/></svg>
                                    <span>{{ $res->nb_enfants }} Enfant(s)</span>
                                </div>
                            @endif

                            @if($res->nb_bebes > 0)
                                <div class="res-info-row">
                                    <svg class="res-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baby-icon lucide-baby"><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M15 12h.01"/><path d="M19.38 6.813A9 9 0 0 1 20.8 10.2a2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/><path d="M9 12h.01"/></svg>
                                    <span>{{ $res->nb_bebes }} Bébé(s)</span>
                                </div>
                            @endif

                            @if($res->nb_animaux > 0)
                                <div class="res-info-row">
                                    <svg class="res-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paw-print-icon lucide-paw-print"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
                                    <span>{{ $res->nb_animaux }} Animaux</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="res-label">Séjour</div>
                            <div class="res-info-row">
                                <svg class="res-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon-icon lucide-moon"><path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/></svg>
                                <span>{{ $res->nb_nuits }} Nuit(s)</span>
                            </div>
                        </div>
                    </div>

                    <div class="res-price-box">
                        <div class="res-price-row">
                            <span>Frais de service</span>
                            <span>{{ number_format($res->frais_services, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="res-price-row">
                            <span>Taxe de séjour</span>
                            <span>{{ number_format($res->taxe_sejour, 2, ',', ' ') }} €</span>
                        </div>
                        
                        <div class="res-price-divider"></div>

                        <div class="res-total">
                            <span>Total</span>
                            <span>{{ number_format($res->montant_total, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="res-empty">
                    <p>Aucune réservation trouvée.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="res-section">
        <p class="section-title">Mes favoris</p>
    
        <div class="res-scroller">
            @forelse($utilisateur->favoris as $similaire)
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

    @endif

    <a href="{{ url('/modifier_compte') }}" class="other-btn"  style='margin-top: 1rem;' wire:navigate>Modifier mon compte</a>

    <form method="POST" action="{{ url('logout') }}" style='margin-top: 1rem;'>
        @csrf
        <button type="submit" class="other-btn">Se déconnecter</button>
    </form>
@endsection