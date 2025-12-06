@extends('layout')

@section('title', 'Détail Annonce')

@section('content')

<div style="padding: 1rem 0;">
    <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--text-color, #333); font-weight: 500;">
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
                <img src="{{ $annonce->photo[0]->nomphoto }}" alt="" style="width:100%; height:auto;">
                <div id="view-photos-overlay" onclick="openModal()">
                    Voir les photos
                </div>
            @else
                <img src="/path/to/default-image.jpg" alt="Sans photo" style="width:100%; height:auto; display:flex; align-items:center; justify-content:center; color: var(--text-muted);">
            @endif
        </div>

        <div class="photo-list">
            @if($annonce->photo)
                @foreach($annonce->photo as $photo)
                    <img src="{{ $photo->nomphoto }}" alt="" style="width:100%; height:auto;" class="photo-item" data-index="{{ $loop->index }}">
                @endforeach
            @endif
        </div>
        <div style="width: 100%; display:flex; flex-direction: column;" class="center">

            <h3 style="margin-top: 3rem;">Disponibilité de cette annonce</h3>
            <input type="hidden" id="booking_start_date" name="start_date">
            <input type="hidden" id="booking_end_date" name="end_date">
            <div id="booking-calendar-container" class="center" style="background-color: white; padding: 1rem 1rem; margin-top: 1rem; border-radius: 1rem; width: 430px;">
                <div class="cal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <button id="btn-prev" class="cal-btn-nav" style="cursor: pointer; padding: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>
                    </button>
                    <span id="cal-title" class="cal-titre" style="font-weight: bold; font-size: 1.1em;">Loading...</span>
                    <button id="btn-next" class="cal-btn-nav" style="cursor: pointer; padding: 5px;">
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
                    <h3>Prix du séjour: <span id="display-price">0</span> € <span style="font-size:0.6em; color:#666">({{ $annonce->prix_nuit }}€ / nuit)</span></h3>
                </div>
                <div class="cal-footer" style="display: flex; justify-content: space-between; gap: 5px;">
                    <button type="button" id="btn-today" class="cal-btn" style="flex: 0 0 auto;">Auj.</button>
                    <div>
                        <button type="button" id="btn-clear" class="cal-btn cal-btn-effacer">Désélectionner</button>
                        <button type="button" id="btn-validate" class="cal-btn cal-btn-valider">Réserver</button>
                    </div>
                </div>
            </div>

            <style>
                .cal-cellule.selected {
                    background-color: var(--primary);
                    color: white;
                }
                
                .cal-cellule.in-range {
                    background-color: var(--primary);
                    background-color: color-mix(in srgb, var(--primary), white 80%);
                    color: black;
                    border-radius: 0;
                }

                .cal-cellule.selected:hover {
                    background-color: var(--primary-hover);
                    color: white;
                }
                

                .cal-cellule.disabled {
                    opacity: 0.3;
                    pointer-events: none;
                    text-decoration: line-through;
                }
            </style>
<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const dispoData = JSON.parse({!! isset($dispoJson) ? json_encode($dispoJson) : '{}' !!});
                    const prixParNuit = parseFloat("{{ $annonce->prix_nuit }}");
                    // 1. Get the minimum nights from Blade, default to 1 if missing
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
                    const errorEl = document.getElementById('cal-error'); // Select the error div
                    
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
                        setTimeout(() => {
                            errorEl.style.display = 'none';
                        }, 3000);
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
                        } else {
                            priceEl.textContent = '0';
                        }
                    }

                    // Helper function to check if a range is free
                    function isRangeAvailable(start, end) {
                        let current = new Date(start);
                        // We check up to (but not including) the end date for availability
                        // because the end date is the checkout date (morning).
                        // However, usually availability is checked for the nights stayed.
                        // Based on your logic: Loop checks days inclusive.
                        
                        // We need to check every night user is sleeping.
                        // If Start is 1st, End is 4th (3 nights). We check availability for 1st, 2nd, 3rd.
                        const checkoutTime = end.getTime();
                        
                        while (current.getTime() < checkoutTime) {
                            const dateString = formatDateKey(current);
                            if (dispoData[dateString] && dispoData[dateString].dispo === false) {
                                return false;
                            }
                            current.setDate(current.getDate() + 1);
                        }
                        return true;
                    }

                    function renderCalendar() {
                        while (gridEl.children.length > 7) {
                            gridEl.removeChild(gridEl.lastChild);
                        }

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

                                if (sTime && dateTime === sTime) {
                                    cell.classList.add('selected', 'start-date');
                                }
                                else if (eTime && dateTime === eTime) {
                                    cell.classList.add('selected', 'end-date');
                                }
                                else if (sTime && eTime && dateTime > sTime && dateTime < eTime) {
                                    cell.classList.add('in-range');
                                }
                            }
                            gridEl.appendChild(cell);
                        }
                        
                        // Navigation buttons state logic (unchanged)
                        const prevMonthDate = new Date(year, month - 1);
                        if (year < today.getFullYear() || (year === today.getFullYear() && month <= today.getMonth())) {
                            prevBtn.disabled = true;
                            prevBtn.style.opacity = "0.3";
                            prevBtn.style.cursor = "not-allowed";
                        } else {
                            prevBtn.disabled = false;
                            prevBtn.style.opacity = "1";
                            prevBtn.style.cursor = "pointer";
                        }

                        const nextMonthDate = new Date(year,month+1);
                        if (nextMonthDate > new Date(today.getFullYear()+2, today.getMonth()) ) {
                            nextBtn.disabled = true;
                            nextBtn.style.opacity = "0.3";
                            nextBtn.style.cursor = "not-allowed";
                        } else {
                            nextBtn.disabled = false;
                            nextBtn.style.opacity = "1";
                            nextBtn.style.cursor = "pointer";
                        }
                    }

                    function selectDate(date) {
                        errorEl.style.display = 'none'; // Clear errors on click

                        // SCENARIO 1: New Selection (No start date, or clicking before start, or resetting)
                        if (!startDate || (startDate && date < startDate) || (startDate && endDate && date < startDate)) {
                            
                            // 2. Logic: Attempt to auto-select the minimum range
                            const proposedEnd = new Date(date);
                            proposedEnd.setDate(date.getDate() + minNights);

                            // Check if the auto-selected range is valid/available
                            if (isRangeAvailable(date, proposedEnd)) {
                                startDate = date;
                                endDate = proposedEnd;
                            } else {
                                // If the minimum stay isn't available, we can't start here
                                showError("La durée minimum n'est pas disponible pour cette date.");
                                return;
                            }
                        } 
                        // SCENARIO 2: Extending Selection (Clicking after start date)
                        else if (startDate && date > startDate) {
                            
                            // Calculate proposed duration
                            const diffTime = Math.abs(date - startDate);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                            // 3. Logic: Prevent selecting less than minimum
                            if (diffDays < minNights) {
                                showError(`Le séjour doit être de ${minNights} nuits minimum.`);
                                return;
                            }

                            // Check availability for the manually extended range
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

                    prevBtn.addEventListener('click', () => {
                        currentViewDate.setMonth(currentViewDate.getMonth() - 1);
                        renderCalendar();
                        updateTotalPrice();
                    });

                    nextBtn.addEventListener('click', () => {
                        currentViewDate.setMonth(currentViewDate.getMonth() + 1);
                        renderCalendar();
                        updateTotalPrice();
                    });

                    todayBtn.addEventListener('click', () => {
                        currentViewDate = new Date(today);
                        renderCalendar();
                        updateTotalPrice();
                    });

                    clearBtn.addEventListener('click', () => {
                        startDate = null;
                        endDate = null;
                        updateHiddenInputs();
                        renderCalendar();
                        updateTotalPrice();
                        errorEl.style.display = 'none';
                    });

                    renderCalendar();
                });
            </script>
        </div>
    </div>
    <div class="info-column">
        
        <header style="border:none; padding:0; text-align:left; margin:0;">
            <h1 class="titre-annonce">{{ $annonce->titre_annonce }}</h1>
            <p style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                {{ $annonce->adresse_annonce . ', ' . $annonce->ville->nomville . ' ' . $annonce->ville->code_postal }} &bull; <span class="stars" style="--rating: {{ $annonce->moyenneAvisParAnnonce()['moyenne'] }}; margin-right: 5px;"></span> {{$annonce->moyenneAvisParAnnonce()['moyenne'] . ' (' . $annonce->moyenneAvisParAnnonce()['nbAvis'] . ' avis) '}}
            </p>
            
            <p style="margin-top: 0.5rem;">
                Annonce posté par 
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
                {{ $annonce->nb_nuit_min }} nuits
            </li>
            <li class="detail-item">
                <span class="detail-label">Bébés</span>
                {{ $annonce->nb_bebe_max > 0 ? 'Acceptés' : 'Non' }}
            </li>
            <li class="detail-item">
                <span class="detail-label">Animaux</span>
                {{ $annonce->nb_animaux_max > 0 ? 'Admis' : 'Interdits' }}
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
                {{ $annonce->nombre_chambre . ' chambres'}}
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

<div class="res-section">
        <p class="section-title">Annonce(s) similaire(s)</p>

        <div class="res-scroller">
        @foreach($annonce->similaires as $similaire)
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
        @endforeach
    </div>
</div>

<div class="res-section">
    <p class="section-title">Réservation(s)</p>
    @forelse($annonce->reservation as $r)
    <div class="reviews">
        <p>{{ $r->particulier->utilisateur->prenom_utilisateur }} {{ $r->particulier->utilisateur->nom_utilisateur }} a passe <b>{{ $r->nb_nuits }} nuits</b> ici</p>
        <p style="margin-bottom: 1rem;"class='subtitle'>Du {{ $r->date_debut_resa }} au {{ $r->date_fin_resa }}</p>
        
        @if($r->avis)
            <span class="stars" style="--rating: {{ $r->avis->note }}; margin-right: 5px;"></span> {{ $r->avis->note }}
                
            @if($r->avis->commentaire)
                <p style="margin-top: 0.5rem;">"{{ $r->avis->commentaire }}"</p>
            @endif
        @else
            <p style="font-style: italic; color: var(--text-muted);">Pas d'avis laissé</p>
        @endif

    </div>
    @empty
        <p>Pas de réservations</p>
    @endforelse
</div>
    
@endsection
@push('scripts')
<script>
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

    const itemPhoto = document.querySelectorAll('.photo-item');
    
    let currentIndex = 0;

    itemPhoto.forEach(item => {
        item.addEventListener('click', () => {
            const index = item.getAttribute('data-index');
            currentIndex = parseInt(index);
            track.style.scrollBehavior = 'auto';
            document.getElementById('modal-overlay').style.display = 'flex';
            updateCarousel();

            setTimeout(() => {
                track.style.scrollBehavior = 'smooth';
            }, 50);
        });
    });

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

    const dots = document.querySelectorAll('.dot');

    function updateCarousel() {

        const slideWidth = slides[0].clientWidth;
        track.scrollTo({
            left: currentIndex * slideWidth,
            behavior: 'auto'
        });
        dots.forEach(dot => dot.classList.remove('active'));
        dots[currentIndex].classList.add('active');
    }

    nextBtn.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex >= slides.length) {
            currentIndex = 0;
        }
        updateCarousel();
    });

    prevBtn.addEventListener('click', () => {
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = slides.length - 1;
        }
        updateCarousel();
    });

    window.addEventListener('resize', updateCarousel);

    const btnfavoris = document.getElementById('favoris')

    btnfavoris.addEventListener('click', () => {
        if (!btnfavoris.classList.contains('liked')) {
            btnfavoris.classList.add('liked')
        } else {
            btnfavoris.classList.remove('liked')
        }
    })
</script>
@endpush