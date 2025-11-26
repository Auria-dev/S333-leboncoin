@extends('layout')

@section('title', 'Détail Annonce')

@section('content')

<div class="annonce-grid">
    
    <div class="carousel-placeholder">
        [CARROUSEL AREA]
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

@endsection