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
    {{-- COLONNE GAUCHE : PHOTOS + CALENDRIER --}}
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
                        <h3>Prix du séjour : <span id="display-price">0</span> € <span style="font-size:0.6em; color:#666">({{ $annonce->prix_nuit }}€ / nuit)</span></h3>
                    </div>
                    <div class="cal-footer" style="display: flex; justify-content: space-between; gap: 5px;">
                        <button type="button" id="btn-today" class="cal-btn" style="flex: 0 0 auto;">Auj.</button>
                        <div>
                            <button type="button" id="btn-clear" class="cal-btn cal-btn-effacer">Désélectionner</button>
                            @if (auth()->check() && auth()->user()->idutilisateur !== $annonce->idproprietaire)
                            <input type="submit" id="btn-validate" class="cal-btn cal-btn-valider" value="Réserver" disabled style="opacity: 0.5; cursor: not-allowed;">
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

            @if($annonce->est_garantie)
                <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #badbcc; display: inline-block;">
                    <strong>🌟 PAIEMENT GARANTI</strong>
                    <br>
                    <small>Propriétaire vérifié par téléphone</small>
                </div>
            @endif

                <p style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                        {{ $annonce->adresse_annonce . ', ' . $annonce->ville->nomville . ' ' . $annonce->ville->code_postal }} &bull; 
        
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
        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-default);">
            <small style="color: var(--text-muted);">
                Publié le {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d/m/Y') }}
            </small>
        </div>
    </div>
</div>

{{-- MODAL PHOTOS --}}
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

{{-- ANNONCES SIMILAIRES --}}
<div class="res-section">
    <p class="section-title">Annonce(s) similaire(s)</p>
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

{{-- SECTION RESERVATIONS (POUR LE PROPRIO) --}}
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


<div id="section-avis" class="container" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; max-width: 800px;">
    
    <h3>Commentaires des voyageurs</h3>

    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
        <span style="font-size: 2rem; font-weight: bold;">
            ★ {{ number_format($annonce->avisValides->avg('note'), 1) }}
        </span>
        <span style="color: #666;">
            ({{ $annonce->avisValides->count() }} avis)
        </span>
    </div>

    <div class="avis-list">
        @forelse($annonce->avisValides as $avis)
            <div class="avis-card" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <div style="font-weight: bold;">
                        {{ $avis->utilisateur->prenom_utilisateur ?? 'Voyageur' }}
                        <span style="font-weight: normal; color: #888; font-size: 0.9em;">
                            - le {{ \Carbon\Carbon::parse($avis->date_depot)->format('d/m/Y') }}
                        </span>
                    </div>
                    <div style="color: #ffb400;">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $avis->note) ★ @else ☆ @endif
                        @endfor
                    </div>
                </div>
                
                <p style="margin: 0; line-height: 1.5; color: #333;">
                    {{ $avis->commentaire }}
                </p>

                @if($avis->reponse_avis)
                    <div style="margin-top: 10px; padding: 10px; background-color: #f9f9f9; border-left: 3px solid #ccc; font-size: 0.9em;">
                        <strong>Réponse du propriétaire :</strong><br>
                        {{ $avis->reponse_avis }}
                    </div>
                @endif
            </div>
        @empty
            <p style="color: #666; font-style: italic;">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script defer>
    // --- SCRIPT CALENDRIER ---
    document.addEventListener('DOMContentLoaded', function() {
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

    // --- SCRIPT CAROUSEL & MODAL ---
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

@endpush