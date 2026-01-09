@extends('layout')

@section('title', 'Détails Annonce')

@section('content')

<div class="py-md">
    <a href="javascript:history.back()" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6"/>
        </svg>
        Retour
    </a>
</div>

<div class="annonce-grid">
    <div class="photo-column">
        <div class="carousel-placeholder">
            @if($annonce->photo && count($annonce->photo) > 0)
                <img src="{{ $annonce->photo[0]->nomphoto }}" alt="" class="img-responsive">
                <div id="view-photos-overlay" onclick="openModal()">
                    Voir les photos
                </div>
            @else
                <img src="/images/placeholder.jpg" alt="Sans photo" class="img-placeholder">
            @endif
        </div>

        <div class="photo-list">
            @if($annonce->photo)
                @foreach($annonce->photo as $photo)
                    <img src="{{ $photo->nomphoto }}" alt="" class="photo-item img-responsive" data-index="{{ $loop->index }}">
                @endforeach
            @endif
        </div>

        <div class="center flex-col w-full">
            <h3 class="mt-xl">Demander une réservation</h3>
            <form id="booking-form" method="GET" action="{{ url('/demander_reservation/' . $annonce->idannonce) }}">
                @csrf
                <input type="hidden" id="booking_start_date" name="start_date">
                <input type="hidden" id="booking_end_date" name="end_date">
                <div id="booking-calendar-container" class="center calendar-container">
                    <div class="cal-header cal-header-flex">
                        <button type="button" id="btn-prev" class="cal-btn-nav cal-btn-nav-style">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>
                        </button>
                        <span id="cal-title" class="cal-titre cal-title-style">Loading...</span>
                        <button type="button" id="btn-next" class="cal-btn-nav cal-btn-nav-style">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                        </button>
                    </div>

                    <div id="cal-grid" class="cal-grid">
                        <div class="cal-jour-semaine">Lun</div>
                        <div class="cal-jour-semaine">Mar</div>
                        <div class="cal-jour-semaine">Mer</div>
                        <div class="cal-jour-semaine">Jeu</div>
                        <div class="cal-jour-semaine">Ven</div>
                        <div class="cal-jour-semaine">Sam</div>
                        <div class="cal-jour-semaine">Dim</div>
                    </div>

                    <div id="cal-error" class="cal-error-msg" style="color: rgb(231, 76, 60); font-size: 0.85em; text-align: center; padding: 5px; display: none;"></div>

                    <div class="cal-footer" style="display: flex; justify-content: space-between; gap: 5px; margin-top: 15px;">
                        <h3> Prix du séjour : <span id="display-price">0</span> € <span style="font-size:0.6em; color:#666">({{ $annonce->prix_nuit }}€ / nuit)</span>
                            <div class="tooltip-container" id="myTooltip">
                                <span class="tooltip-trigger">
                                    <svg fill="#4A3B32" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M11.188 4.781c6.188 0 11.219 5.031 11.219 11.219s-5.031 11.188-11.219 11.188-11.188-5-11.188-11.188 5-11.219 11.188-11.219zM11.063 8.906c-0.313 0.375-0.469 0.813-0.469 1.281 0 0.375 0.125 0.688 0.313 0.906 0.219 0.219 0.531 0.344 0.844 0.344 0.438 0 0.844-0.188 1.156-0.563 0.281-0.344 0.438-0.844 0.438-1.375 0-0.313-0.094-0.594-0.313-0.813s-0.531-0.344-0.844-0.344c-0.406 0-0.813 0.188-1.125 0.563zM8.219 15.375l0.375 0.406c0.281-0.313 0.563-0.563 0.75-0.719 0.188-0.125 0.344-0.188 0.469-0.188 0.094 0 0.188 0.031 0.25 0.094 0.031 0.094 0.063 0.188 0.063 0.344 0 0.781-0.094 1.281-0.5 3.156s-0.625 3.25-0.625 4.156c0 0.344 0.063 0.594 0.188 0.75 0.094 0.156 0.281 0.281 0.531 0.281 0.406 0 1-0.313 1.688-0.844 0.688-0.563 1.375-1.344 2.125-2.344l-0.406-0.344c-0.25 0.313-0.5 0.531-0.688 0.688-0.188 0.125-0.344 0.25-0.469 0.25-0.094 0-0.188-0.094-0.25-0.156-0.031-0.094-0.063-0.219-0.063-0.406 0-0.125 0.031-0.531 0.156-1.25 0.094-0.719 0.063-0.719 0.25-1.781 0.031-0.313 0.125-0.75 0.219-1.281 0.25-1.594 0.406-2.563 0.406-2.875 0-0.281-0.094-0.531-0.188-0.688-0.125-0.156-0.313-0.219-0.531-0.219-0.375 0-0.875 0.281-1.563 0.781-0.688 0.531-1.375 1.25-2.188 2.188z"></path> </g></svg>
                                </span>
                        
                                <div class="tooltip-box">
                                    <p>Prix calculé en fonction du nombre de personnes, du montant de la location et des frais supplémentaires (frais de services, taxe de séjour).</p>
                                    <a href="{{ route('legal.aide') }}#aide-choose-date">En savoir plus</a>
                                    <div class="arrow"></div>
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="cal-footer" style="display: flex; justify-content: space-between; gap: 5px;">
                        <button type="button" id="btn-today" class="cal-btn" style="flex: 0 0 auto;">Auj.</button>
                        <div>
                            <button type="button" id="btn-clear" class="cal-btn cal-btn-effacer">Désélectionner</button>
                            @if (auth()->check() && auth()->user()->idutilisateur !== $annonce->idproprietaire)
                            <input type="submit" id="btn-validate" class="cal-btn cal-btn-valider" value="Réserver" disabled style="opacity: 0.5; cursor: not-allowed;">
                            @elseif (!auth()->check())
                            <button type="button" class="cal-btn cal-btn-valider" style="opacity: 1.0; cursor: pointer;" onclick="window.location.href='{{ route('login') }}'">Réserver</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <div class="map-pane-annonce" style="width: 100%; height: 400px; margin-top: 1rem; border: 1px solid var(--border-default); border-radius: 8px; overflow: hidden;">
                <div id="maCarte"></div> 
            </div>
        </div>
    </div>

    
    <div class="info-column">
        
        <header style="border:none; padding:0; text-align:left; margin:0;">
            <h1 class="titre-annonce">{{ $annonce->titre_annonce }}</h1>

                <p style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                        {{ $annonce->ville->nom_ville . ' ' . $annonce->ville->code_postal }} &bull; 
        
                    <span class="stars" style="--rating: {{ $annonce->moyenneAvisParAnnonce()['moyenne'] }}; margin-right: 5px;"></span> 
        
                    <span style="font-weight: bold;">
                        {{ number_format($annonce->avisValides->avg('note'), 1) }}
                    </span>
                    
                    <a href="{{ route('annonce.avis', $annonce->idannonce) }}" style="text-decoration: underline; color: inherit; cursor: pointer; margin-left: 5px;">
                        ({{ $annonce->avisValides->count() }} avis)
                    </a>
                </p>

            <p style="margin-top: 0.5rem;">
                Annonce postée par 
                <a class="hyperlink" href="{{ url('/proprio/' . $annonce->idproprietaire ) }}" >
                    <span style="text-transform: uppercase;">{{ $annonce->utilisateur->nom_utilisateur }} </span> {{ $annonce->utilisateur->prenom_utilisateur }}
                </a>
            </p>
        </header>
        
        <div class="prix-block">
            <div>
                {{ $annonce->prix_nuit }} € 
                <span class="prix-unit">/ nuit</span>
            </div>
            <a href="{{ url('/ajouter_fav/' . $annonce->idannonce ) }}">
                <svg id="favoris" class="{{ $isFav ? 'liked' : '' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
            </a>
        </div>

        <ul class="details-list">
            <li class="detail-item">
                <span class="detail-label">Voyageurs</span>
                {{ $annonce->nb_personnes_max }} max
            </li>
            <li class="detail-item">
                <span class="detail-label">Séjour min</span>
                @if ($annonce->nb_nuit_min === 1 )
                    {{ $annonce->nb_nuit_min }} nuit
                @else
                    {{ $annonce->nb_nuit_min }} nuits
                @endif
            </li>
            <li class="detail-item">
                <span class="detail-label">Bébés</span>
                {{ $annonce->nb_bebe_max > 0 ? 'Acceptés ('. $annonce->nb_bebe_max . ')' : 'Non' }}
            </li>
            <li class="detail-item">
                <span class="detail-label">Animaux</span>
                {{ $annonce->nb_animaux_max > 0 ? 'Admis ('. $annonce->nb_animaux_max . ')' : 'Interdits' }}
            </li>
            <li class="detail-item">
                <span class="detail-label">Heure arrivée</span>
                {{ \Carbon\Carbon::parse($annonce->heure_arrivee)->format('H:i') }}
            </li>
            <li class="detail-item">
                <span class="detail-label">Heure départ</span>
                {{ \Carbon\Carbon::parse($annonce->heure_depart)->format('H:i') }}
            </li>
            <li class="detail-item" style="grid-column: span 2;">
                <span class="detail-label">Nombre de chambres</span>
                @if ($annonce->nombre_chambre === 1)
                    {{ $annonce->nombre_chambre . ' chambre'}}
                @else 
                    {{ $annonce->nombre_chambre . ' chambres'}}
                @endif
            </li>
            <li class="detail-item" style="grid-column: span 2;">
                <span class="detail-label">Réf.</span>
                #{{ $annonce->idannonce }}
            </li>
        </ul>

        <div class="description-block">
            <h3>À propos</h3>
            <div class="description-text">
                {{ $annonce->description_annonce }}
            </div>
        </div>
        <div>
        <h3 style="margin-bottom: 1rem;">Equipements</h3>
            @foreach ($annonce->equipement as $eq)
                {{ $eq->nom_equipement }}<br>
            @endforeach
        </div>
        <div>
        <h3 style="margin-bottom: 1rem;">Services</h3>
            @foreach ($annonce->service as $se)
                {{ $se->nom_service }}<br>
            @endforeach
        </div>
            <div class="partage-container" style="margin-top: 2rem; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-default);">
    <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Partager cette annonce</h3>

    @php
        $currentUrl = url()->current();
        $titreAnnonce = urlencode($annonce->titre_annonce ?? 'Annonce Leboncoin');
    @endphp

    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        
        <button type="button" onclick="copierLien()" class="btn-share" title="Copier le lien" style="background-color: #e9ecef; color: #333;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
        </button>

        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $currentUrl }}" target="_blank" class="btn-share" title="Facebook" style="background-color: #3b5998; color: white;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
        </a>

        <a href="https://twitter.com/intent/tweet?text={{ $titreAnnonce }}&url={{ $currentUrl }}" target="_blank" class="btn-share" title="Twitter" style="background-color: #000000; color: white;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
        </a>

        <a href="https://wa.me/?text=Regarde%20cette%20annonce%20:%20{{ $titreAnnonce }}%20{{ $currentUrl }}" target="_blank" class="btn-share" title="WhatsApp" style="background-color: #25D366; color: white;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
        </a>

        <a href="mailto:?subject=Annonce : {{ $titreAnnonce }}&body=Regarde cette annonce : {{ $currentUrl }}" class="btn-share" title="Envoyer par email" style="background-color: #6c757d; color: white;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        </a>
    </div>

    <div id="copySuccess" style="display:none; color: #28a745; margin-top: 10px; font-weight: bold; font-size: 0.9rem;">
        Lien copié dans le presse-papier !
    </div>

    <style>
        .btn-share {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-share:hover {
            opacity: 0.9;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-share svg {
            pointer-events: none;
        }
    </style>

    <script>
        function copierLien() {
            const url = window.location.href;
            const msg = document.getElementById('copySuccess');
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => afficherMessageSucces())
                .catch(() => fallbackCopierLien(url)); 
            } else {

                fallbackCopierLien(url);
            }
            function fallbackCopierLien(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed"; 
                textArea.style.opacity = "0";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    const successful = document.execCommand('copy');
                    if(successful) afficherMessageSucces();
                    else alert('Impossible de copier le lien.');
                } catch (err) {
                    console.error('Erreur copie', err);
                    alert('Erreur lors de la copie.');
                }
                
                document.body.removeChild(textArea);
            }
            function afficherMessageSucces() {
                if(msg) {
                    msg.style.display = 'block';
                    setTimeout(() => { msg.style.display = 'none'; }, 3000);
                }
            }
        }
    </script>
</div>

        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-default);">
            <small style="color: var(--text-muted);">
                Publié le {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d/m/Y') }}
            </small>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modal-overlay" style="display: none;">
    <div class="carrousel-conatainer">
        <div class="carrousel-body">
            <div class="carrousel-slide">
                <svg xmlns="http://www.w3.org/2000/svg" id="croix" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                @foreach($annonce->photo as $photo)
                    <div class="slide">
                        <img src="{{ $photo->nomphoto }}" alt="Photo annonce">
                    </div>
                @endforeach
            </div>
            <button id="prevBtn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg></button>
            <button id="nextBtn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg></button>

            <div class="dots"></div>
            
        </div>
    </div>
</div>

<div class="res-section" style="margin-top: 2rem;">
    <p class="section-title">Annonce(s) similaire(s)  
        <div class="tooltip-container" id="myTooltip">
            <span class="tooltip-trigger">
                <svg fill="#4A3B32" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M11.188 4.781c6.188 0 11.219 5.031 11.219 11.219s-5.031 11.188-11.219 11.188-11.188-5-11.188-11.188 5-11.219 11.188-11.219zM11.063 8.906c-0.313 0.375-0.469 0.813-0.469 1.281 0 0.375 0.125 0.688 0.313 0.906 0.219 0.219 0.531 0.344 0.844 0.344 0.438 0 0.844-0.188 1.156-0.563 0.281-0.344 0.438-0.844 0.438-1.375 0-0.313-0.094-0.594-0.313-0.813s-0.531-0.344-0.844-0.344c-0.406 0-0.813 0.188-1.125 0.563zM8.219 15.375l0.375 0.406c0.281-0.313 0.563-0.563 0.75-0.719 0.188-0.125 0.344-0.188 0.469-0.188 0.094 0 0.188 0.031 0.25 0.094 0.031 0.094 0.063 0.188 0.063 0.344 0 0.781-0.094 1.281-0.5 3.156s-0.625 3.25-0.625 4.156c0 0.344 0.063 0.594 0.188 0.75 0.094 0.156 0.281 0.281 0.531 0.281 0.406 0 1-0.313 1.688-0.844 0.688-0.563 1.375-1.344 2.125-2.344l-0.406-0.344c-0.25 0.313-0.5 0.531-0.688 0.688-0.188 0.125-0.344 0.25-0.469 0.25-0.094 0-0.188-0.094-0.25-0.156-0.031-0.094-0.063-0.219-0.063-0.406 0-0.125 0.031-0.531 0.156-1.25 0.094-0.719 0.063-0.719 0.25-1.781 0.031-0.313 0.125-0.75 0.219-1.281 0.25-1.594 0.406-2.563 0.406-2.875 0-0.281-0.094-0.531-0.188-0.688-0.125-0.156-0.313-0.219-0.531-0.219-0.375 0-0.875 0.281-1.563 0.781-0.688 0.531-1.375 1.25-2.188 2.188z"></path> </g></svg>
            </span>
    
            <div class="tooltip-box">
                <p>Annonces basées sur les critères de l'annonce principale.</p>
                <a href="{{ route('legal.aide') }}#aide-similaire">En savoir plus</a>
                <div class="arrow"></div>
            </div>
        </div>
    </p>
    <div class="res-scroller">
        @forelse($annonce->similaires as $similaire)
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
                <p>Aucune annonce similaire trouvée.</p>
            </div>
        @endforelse
    </div>
</div>

@if (auth()->check() && auth()->user()->idutilisateur === $annonce->idproprietaire)
<div class="res-section">
    <p class="section-title">Réservation(s) de cette annonce</p>
    <div class="res-scroller">
        @forelse($annonce->reservation as $res)
            <a class="res-card" href="{{ url('reservation/'.strval($res->idreservation)) }}" >
                <div class="res-header">
                    <div>
                        <h3 class="res-id">Réservation #{{ $res->idreservation }}</h3>
                        <span class="res-dates">
                            <p class="side-by-side" style="align-items: center; gap: 0.25rem;">{!! $res->particulier->utilisateur->displayName() !!}</p>
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
@endif


<div>
    
    <h2 style="margin-bottom: 1rem;">Commentaires des voyageurs</h2>

    <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 20px;">
        <span style="font-size: 30px; font-weight: bold;">
            ★ {{ number_format($annonce->avisValides->avg('note'), 1) }}
        </span>
        <span style="color: var(--text-muted);">
            ({{ $annonce->avisValides->count() }} avis)
        </span>
    </div>

    <div>
        @forelse($annonce->avisValides as $avis)
        <hr class="res-divider">
            <div style="padding-top: 10px; padding-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px; font-weight: bold;">
                        
                        @if($avis->utilisateur->photo_profil === null)
                            <img src="/images/photo-profil.jpg" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%; ">
                        @else
                            <img src="{{ $avis->utilisateur->photo_profil }}" class="profile-img" style="width: 50px; height: 50px; border-radius: 50%;">
                        @endif

                        <div>
                            {{ $avis->utilisateur->prenom_utilisateur }} {{ $avis->utilisateur->nom_utilisateur }}
                            <span style="color: var(--text-muted); font-size: 0.8em; font-weight: normal; display: block;">
                                {{ \Carbon\Carbon::parse($avis->date_depot)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <div style="color: var(--primary-hover); font-size: 1.2em;">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $avis->note) ★ @else ☆ @endif
                        @endfor
                    </div>
                </div>
                
                <p style="line-height: 2">
                    {{ $avis->commentaire }}
                </p>

                @if($avis->reponse_avis)
                    <div style="margin-top: 10px; padding: 8px 0 5px 10px; background-color: var(--bg-response); border-left: 3px solid var(--text-muted); font-size: 0.9sem; border-top-right-radius: 30px; border-bottom-right-radius: 30px;">
                        <p>{{$annonce->utilisateur->prenom_utilisateur}} {{$annonce->utilisateur->nom_utilisateur}} : </p>
                        <p style="line-height: 2">{{ $avis->reponse_avis }} </p>
                    </div>
                @endif
            </div>
        @empty
            <p style="color: var(--text-muted);; font-style: italic;">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script defer>
    document.addEventListener("DOMContentLoaded", (event) => {
        const dispoData = JSON.parse({!! isset($dispoJson) ? json_encode($dispoJson) : '{}' !!});
        const prixParNuit = parseFloat("{{ $annonce->prix_nuit }}");
        const minNights = parseInt("{{ $annonce->nb_nuit_min ?? 1 }}"); 

        const today = new Date();
        today.setHours(0,0,0,0);
        const maxDate = new Date(today);
        maxDate.setFullYear(today.getFullYear() + 2);

        let currentViewDate = new Date(today);
        let startDate = null;
        let endDate = null;

        const gridEl = document.getElementById('cal-grid');
        const titleEl = document.getElementById('cal-title');
        const prevBtn = document.getElementById('btn-prev');
        const nextBtn = document.getElementById('btn-next');
        const todayBtn = document.getElementById('btn-today');
        const clearBtn = document.getElementById('btn-clear');
        const validateBtn = document.getElementById('btn-validate');
        const errorEl = document.getElementById('cal-error');
        
        const startInput = document.getElementById('booking_start_date');
        const endInput = document.getElementById('booking_end_date');

        function formatDateKey(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function showError(msg) {
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
            setTimeout(() => { errorEl.style.display = 'none'; }, 3000);
        }

        function updateHiddenInputs() {
            startInput.value = startDate ? formatDateKey(startDate) : '';
            endInput.value = endDate ? formatDateKey(endDate) : '';
        }

        function updateTotalPrice() {
            const priceEl = document.getElementById('display-price');
            if (startDate && endDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                const total = diffDays * prixParNuit;
                priceEl.textContent = total.toFixed(2);
                if(validateBtn) {
                    validateBtn.disabled = false;
                    validateBtn.style.opacity = '1';
                    validateBtn.style.cursor = 'pointer';
                }
            } else {
                priceEl.textContent = '0';
                if(validateBtn) {
                    validateBtn.disabled = true;
                    validateBtn.style.opacity = '0.5';
                    validateBtn.style.cursor = 'not-allowed';
                }
            }
        }

        function isRangeAvailable(start, end) {
            let current = new Date(start);
            const checkoutTime = end.getTime();
            while (current.getTime() < checkoutTime) {
                const dateString = formatDateKey(current);
                if (dispoData[dateString] && dispoData[dateString].dispo === false) return false;
                current.setDate(current.getDate() + 1);
            }
            return true;
        }

        function renderCalendar() {
            while (gridEl.children.length > 7) { gridEl.removeChild(gridEl.lastChild); }

            const year = currentViewDate.getFullYear();
            const month = currentViewDate.getMonth();
            const monthName = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(currentViewDate);
            titleEl.textContent = monthName.charAt(0).toUpperCase() + monthName.slice(1);

            const firstDayOfMonth = new Date(year, month, 1);
            let startDayIndex = firstDayOfMonth.getDay();
            startDayIndex = (startDayIndex === 0) ? 6 : startDayIndex - 1;
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < startDayIndex; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'cal-cellule';
                emptyCell.style.border = 'none';
                emptyCell.style.backgroundColor = 'transparent';
                gridEl.appendChild(emptyCell);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dateObj = new Date(year, month, d);
                const dateString = formatDateKey(dateObj);
                const cell = document.createElement('div');
                cell.className = 'cal-cellule';
                cell.textContent = d;

                let isDisabled = false;
                if (dateObj < today) isDisabled = true;
                else if (dateObj > maxDate) isDisabled = true;
                else if (dispoData[dateString] && dispoData[dateString].dispo === false) isDisabled = true;

                if (isDisabled) {
                    cell.classList.add('disabled');
                } else {
                    cell.addEventListener('click', () => selectDate(dateObj));
                    const dateTime = dateObj.getTime();
                    const sTime = startDate ? startDate.getTime() : null;
                    const eTime = endDate ? endDate.getTime() : null;

                    if (sTime && dateTime === sTime) cell.classList.add('selectionne', 'debut-plage');
                    else if (eTime && dateTime === eTime) cell.classList.add('selectionne', 'fin-plage');
                    else if (sTime && eTime && dateTime > sTime && dateTime < eTime) cell.classList.add('dans-plage');
                }
                gridEl.appendChild(cell);
            }
        }

        function selectDate(date) {
            errorEl.style.display = 'none';
            if (!startDate || (startDate && date < startDate) || (startDate && endDate && date < startDate)) {
                const proposedEnd = new Date(date);
                proposedEnd.setDate(date.getDate() + minNights);
                if (isRangeAvailable(date, proposedEnd)) {
                    startDate = date;
                    endDate = proposedEnd;
                } else {
                    showError("La durée minimum n'est pas disponible pour cette date.");
                    return;
                }
            } else if (startDate && date > startDate) {
                const diffTime = Math.abs(date - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays < minNights) {
                    showError(`Le séjour doit être de ${minNights} nuits minimum.`);
                    return;
                }
                if (isRangeAvailable(startDate, date)) {
                    endDate = date;
                } else {
                    showError("Certaines dates sélectionnées ne sont pas disponibles.");
                    return; 
                }
            }
            updateHiddenInputs();
            renderCalendar();
            updateTotalPrice();
        }

        prevBtn.addEventListener('click', () => { currentViewDate.setMonth(currentViewDate.getMonth() - 1); renderCalendar(); });
        nextBtn.addEventListener('click', () => { currentViewDate.setMonth(currentViewDate.getMonth() + 1); renderCalendar(); });
        todayBtn.addEventListener('click', () => { currentViewDate = new Date(today); renderCalendar(); updateTotalPrice(); });
        clearBtn.addEventListener('click', () => { startDate = null; endDate = null; updateHiddenInputs(); renderCalendar(); updateTotalPrice(); errorEl.style.display = 'none'; });

        renderCalendar();
    });

    function openModal() {
        document.getElementById('modal-overlay').style.display = 'flex';
    }
    
    const croix = document.getElementById("croix");
    window.onclick = function(event) {
        var modal = document.getElementById('modal-overlay');
        if (event.target == modal || event.target == croix) {
            modal.style.display = 'none';
        }
    }

    const track = document.querySelector('.carrousel-slide');
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dotsContainer = document.querySelector('.dots');
    const photoItems = document.querySelectorAll('.photo-item');
    let currentIndex = 0;

    photoItems.forEach(item => {
        item.addEventListener('click', () => {
            const index = item.getAttribute('data-index');
            currentIndex = parseInt(index);
            track.style.scrollBehavior = 'auto';
            document.getElementById('modal-overlay').style.display = 'flex';
            updateCarousel();
            setTimeout(() => { track.style.scrollBehavior = 'smooth'; }, 50);
        });
    });

    if(slides.length > 0) {
        slides.forEach((slide, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
            dotsContainer.appendChild(dot);
        });
    }

    const dots = document.querySelectorAll('.dot');

    function updateCarousel() {
        if(slides.length === 0) return;
        const slideWidth = slides[0].clientWidth;
        track.scrollTo({ left: currentIndex * slideWidth, behavior: 'auto' });
        dots.forEach(dot => dot.classList.remove('active'));
        if(dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    if(nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentIndex++;
            if (currentIndex >= slides.length) currentIndex = 0;
            updateCarousel();
        });
    }

    if(prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentIndex--;
            if (currentIndex < 0) currentIndex = slides.length - 1;
            updateCarousel();
        });
    }

    window.addEventListener('resize', updateCarousel);

    const btnfavoris = document.getElementById('favoris');
    if(btnfavoris){
        btnfavoris.addEventListener('click', () => {
            if (!btnfavoris.classList.contains('liked')) btnfavoris.classList.add('liked');
            else btnfavoris.classList.remove('liked');
        });
    }
</script>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-providers@latest/leaflet-providers.js"></script>
<script src="{{ asset('js/map.js') }}"></script>

<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const annoncesData = @json($annonceAsArray);
        console.log(@json($annonceAsArray));
        
        initMapAnnonce('maCarte', annoncesData);
    });
</script>

<script defer>
    const tooltip = document.getElementById('myTooltip');

    tooltip.addEventListener('click', (e) => {
    e.stopPropagation();
    tooltip.classList.toggle('active');
    });

    document.addEventListener('click', () => {
    tooltip.classList.remove('active');
    });

    tooltip.querySelector('.tooltip-box').addEventListener('click', (e) => {
    e.stopPropagation();
    });
</script>
@endpush