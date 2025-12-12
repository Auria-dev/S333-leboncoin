@extends('layout')

@section('title', 'Tableau de bord')

@section('content')
<div class="center-container">
<div class="dashboard-container">

<div class="side-by-side dashboard-header">
    @if($utilisateur->photo_profil === null)
        <img src="/images/photo-profil.jpg" class="profile-img">
    @else
        <img src="{{ $utilisateur->photo_profil }}" class="profile-img">
    @endif   
    <div class="flex-col-gap-sm">
        <h1>Bonjour {!! $utilisateur->displayName() !!} </h1>
        <p>Bienvenue dans le tableau de bord de votre compte {{ strtolower($utilisateur->getTypeParticulier()) }}.</p>
        <a href="{{ url('/modifier_compte') }}" class="other-btn w-fit" wire:navigate>Modifier mon compte</a>
    </div>
</div>
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
        <p class="section-title">Mes réservation(s)</p>
        <div class="res-scroller">
            @forelse($utilisateur->reservation as $res)
                <a class="res-card" href="{{ url('reservation/'.strval($res->idreservation)) }}" >
                    <div class="res-header">
                        <div>
                            <h3 class="res-id">Réservation #{{ $res->idannonce }}</h3>
                            <span class="res-dates">
                                <p class="side-by-side center" style="gap: 0.25rem;">{!! $res->particulier->utilisateur->displayName() !!}</p>
                                <p>{{ \Carbon\Carbon::parse($res->date_debut_resa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($res->date_fin_resa)->format('d/m/Y') }}</p>
                            </span>
                        </div>
                        @php
                            $s = strtolower($res->statut_reservation);
                            $st_class = 'st-default';
                            
                            if(Str::contains($s, ['valid', 'accept'])) {
                                $st_class = 'st-accepted';
                            } elseif(Str::contains($s, ['refus', 'annul'])) {
                                $st_class = 'st-rejected';
                            } elseif(Str::contains($s, ['attent'])) {
                                $st_class = 'st-pending';
                            }
                        @endphp
                        <span class="status-dot {{ $st_class }}">
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
        <h2 class="section-title">Mes annonces en demande</h2>
        <div class="res-grid">
            @forelse($utilisateur->demandesReservations as $demande)
                @php
                    $start = \Carbon\Carbon::parse($demande->date_debut_resa);
                    $end = \Carbon\Carbon::parse($demande->date_fin_resa);
                    $nights = $start->diffInDays($end);
                    $total_price = $nights * $demande->annonce->prix_nuit;

                    $s = strtolower($demande->statut_reservation);
                    $st_class = 'st-default';
                    
                    if(Str::contains($s, ['valid', 'accept'])) {
                        $st_class = 'st-accepted';
                    } elseif(Str::contains($s, ['refus', 'annul'])) {
                        $st_class = 'st-rejected';
                    } elseif(Str::contains($s, ['attent'])) {
                        $st_class = 'st-pending';
                    }
                @endphp

                <a href="{{ url('reservation/'.strval($demande->idreservation)) }}" class="compact-card">
                    
                    <div class="card-top">
                        <h3 class="card-title">{{ $demande->annonce->titre_annonce }}</h3>
                        <span class="status-dot {{ $st_class }}">
                            {{ $demande->statut_reservation }}
                        </span>
                    </div>

                    <div class="card-dates">
                        <svg class="card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ $start->format('d M') }} - {{ $end->format('d M') }} ({{ $nights }} nuits)</span>
                    </div>

                    <div class="card-footer">
                        <div class="user-info">
                            @if($demande->particulier->utilisateur->photo_profil)
                                <img src="{{ $demande->particulier->utilisateur->photo_profil }}" class="user-pfp" alt="User">
                            @else
                                <img src="/images/photo-profil.jpg" class="user-pfp" alt="User">
                            @endif
                            <div class="user-text-col">
                                <span class="user-label">Voyageur</span>
                                <span class="user-name">{{ $demande->particulier->utilisateur->prenom_utilisateur }}</span>
                            </div>
                        </div>
                        
                        <span class="card-price">{{ $total_price }}€</span>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1 / -1; padding: 60px; text-align: center; background: var(--bg-card); border-radius: var(--radius-card); border: 1px dashed var(--border-default);">
                    <p style="color: var(--text-muted); margin: 0;">Aucune demande de réservation pour le moment.</p>
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



        <div class="res-section">
        <p class="section-title">Mes recherches sauvegardées</p>

        <div class="res-scroller">
            @forelse($utilisateur->recherche as $savedSearch)
                <div class="search-card">
                    
                    <form action="{{ route('recherche.destroy', $savedSearch->idcritere) }}" method="POST" class="delete-search-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-search" title="Supprimer la recherche">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>

                    <a href="{{ route('resultats', [
                        'search'      => $savedSearch->mot_clef,
                        'nbVoyageurs' => $savedSearch->nb_voyageurs,
                        'datedebut'   => $savedSearch->date_debut_recherche,
                        'datefin'     => $savedSearch->date_fin_recherche,
                        'prixMin'     => $savedSearch->prix_min,
                        'prixMax'     => $savedSearch->prix_max,
                        'filtreTypeHebergement' => $savedSearch->type_hebergement
                    ]) }}" class="search-card-link">
                        
                        <div>
                            <div class="search-title">{{ $savedSearch->pivot->titre_recherche }}</div>
                            <span class="search-tag">
                                {{ $savedSearch->type_hebergement ?? 'Tous types' }}
                            </span>
                        </div>

                        <div class="search-criteria">
                            <div class="criteria-row">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <span>{{ $savedSearch->mot_clef ?: 'Partout' }}</span>
                            </div>

                            <div class="criteria-row">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                <span>
                                    @if($savedSearch->date_debut_recherche)
                                        {{ \Carbon\Carbon::parse($savedSearch->date_debut_recherche)->format('d/m') }} - {{ \Carbon\Carbon::parse($savedSearch->date_fin_recherche)->format('d/m') }}
                                    @else
                                        Dates flexibles
                                    @endif
                                </span>
                            </div>

                            <div class="criteria-row">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span>{{ $savedSearch->nb_voyageurs ?? 1 }} voyageur(s)</span>
                            </div>
                        </div>

                    </a>
                </div>
            @empty
                <div class="res-empty">
                    <p>Aucune recherche sauvegardée.</p>
                </div>
            @endforelse
        </div>
    </div>

    @endif

    <form method="POST" action="{{ url('logout') }}" class="w-fit">
        @csrf
        <button type="submit" class="other-btn">Se déconnecter</button>
    </form>
</div>
</div>
@endsection