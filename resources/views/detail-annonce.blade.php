
<!-- 
Champs de la table "annonce":
    "idannonce"
    "idtypehebergement"
    "idville"
    "idproprietaire"
    "titre_annonce"
    "prix_nuit"
    "nb_nuit_min"
    "nb_bebe_max"
    "nb_personnes_max"
    "nb_animaux_max"
    "adresse_annonce"
    "description_annonce"
    "date_publication"
-->
    
@extends('layout')

@section('title', 'Détail Annonce')

@section('content')

<style>
    :root {
        --c-text-main: #1f2937;
        --c-text-muted: #6b7280;
        --c-bg-subtle: #f3f4f6;
        --c-border: #e5e7eb;
        --radius: 0.5rem;
    }

    * { box-sizing: border-box; }

    .container {
        max-width: 75rem;
        margin: 0 auto;
        padding: 1.5rem; 
        font-family: system-ui, -apple-system, sans-serif;
        color: var(--c-text-main);
    }

    .annonce-grid {
        display: grid;
        grid-template-columns: 3fr 4fr;
        gap: 2.5rem;
        align-items: start;
    }

    .carousel-placeholder {
        background-color: #e0e0e0;
        aspect-ratio: 16 / 9; 
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0.125rem dashed #a3a3a3;
        border-radius: var(--radius);
        font-weight: bold;
        color: #555;
        font-size: 1.25rem;
    }

    .info-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .titre-annonce {
        margin: 0;
        font-size: clamp(1.5rem, 3vw, 2.25rem);
        line-height: 1.1;
        font-weight: 800;
    }

    .prix-block {
        font-size: 1.75rem;
        font-weight: 700;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--c-border);
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }

    .prix-unit {
        font-size: 1rem;
        font-weight: normal;
        color: var(--c-text-muted);
    }

    .details-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .detail-item {
        background: var(--c-bg-subtle);
        padding: 0.75rem 1rem;
        border-radius: var(--radius);
        border: 0.0625rem solid var(--c-border);
        font-size: 1rem;
    }

    .label {
        display: block;
        color: var(--c-text-muted);
        font-size: 0.75em;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25em;
        font-weight: 600;
    }

    .description-block {
        margin-top: 1rem;
    }

    .description-block h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .description-text {
        line-height: 1.6;
        color: #374151;
        max-width: 65ch; 
    }

    @media (max-width: 768px) { 
        .annonce-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .carousel-placeholder {
            min-height: 15rem; 
        }
    }
</style>

<div class="container">
    <div class="annonce-grid">
        
        <div class="carousel-placeholder">
            [CARROUSEL AREA]
        </div>

        <div class="info-column">
            
            <header>
                <h1 class="titre-annonce">{{ $annonce->titre_annonce }}</h1>
                <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.9rem;">
                    {{ $annonce->adresse_annonce }}
                </p>
            </header>
            
            <div class="prix-block">
                {{ $annonce->prix_nuit }} € 
                <span class="prix-unit">/ nuit</span>
            </div>

            <ul class="details-list">
                <li class="detail-item">
                    <span class="label">Voyageurs</span>
                    {{ $annonce->nb_personnes_max }} max
                </li>
                <li class="detail-item">
                    <span class="label">Séjour min</span>
                    {{ $annonce->nb_nuit_min }} nuits
                </li>
                <li class="detail-item">
                    <span class="label">Bébés</span>
                    {{ $annonce->nb_bebe_max > 0 ? 'Acceptés' : 'Non' }}
                </li>
                <li class="detail-item">
                    <span class="label">Animaux</span>
                    {{ $annonce->nb_animaux_max > 0 ? 'Admis' : 'Interdits' }}
                </li>
                <li class="detail-item" style="grid-column: span 2;">
                    <span class="label">Réf.</span>
                    #{{ $annonce->idannonce }}
                </li>
            </ul>

            <div class="description-block">
                <h3>À propos</h3>
                <div class="description-text">
                    {{ $annonce->description_annonce }}
                </div>
            </div>
            
            <div style="margin-top: auto; padding-top: 1rem; border-top: 0.0625rem solid #eee;">
                <small style="color: #9ca3af;">
                    Publié le {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d/m/Y') }}
                </small>
            </div>

        </div>
    </div>
</div>

@endsection