@extends('layout')

@section('title', 'Détail Annonce')

@section('content')

<div class="annonce-grid">

    <div class="photo-column">
        <div class="carousel-placeholder">
            <img src="{{ $annonce->photo[0]->nomphoto }}" alt="" style="width:100%; height:auto;">
            <div id="view-photos-overlay" onclick="openModal()">
                Voir les photos
            </div>
        </div>
        <div class="photo-list">
            @forEach($annonce->photo as $photo)
                <img src="{{ $photo->nomphoto }}" alt="" style="width:100%; height:auto;" class="photo-item" data-index="{{ $loop->index }}">
            @endforEach
        </div>       
    </div>

    <div class="info-column">
        
        <header style="border:none; padding:0; text-align:left; margin:0;">
            <h1 class="titre-annonce">{{ $annonce->titre_annonce }}</h1>
            <p style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                {{ $annonce->adresse_annonce }}
            </p>
        </header>
        
        <div class="prix-block">
            {{ $annonce->prix_nuit }} € 
            <span class="prix-unit">/ nuit</span>
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

@endsection
@push('scripts')
<script>

    function openModal() {
        document.getElementById('modal-overlay').style.display = 'flex';

    }

    window.onclick = function(event) {
        var modal = document.getElementById('modal-overlay');
        if (event.target == modal) {
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
</script>
@endpush