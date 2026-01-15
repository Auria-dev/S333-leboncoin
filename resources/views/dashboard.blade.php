@extends('layout')

@section('title', 'Tableau de bord')

@section('content')
<div class="center-container">
    <div class="dashboard-container">

        @php
            $utilisateur = auth()->user();
        @endphp

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

        <div class="bento-grid">
            
            <a href="{{ url('/dashboard/mes-annonces') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Mes annonces</h3>
                    <p class="bento-subtitle">Gérez vos biens, tarifs et calendriers</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
            </a>

            <a href="{{ url('/dashboard/mes-favoris') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Mes favoris</h3>
                    <p class="bento-subtitle">Accédez à vos hébergements préférés</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>
            </a>

            <a href="{{ url('/dashboard/mes-voyages') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Mes voyages</h3>
                    <p class="bento-subtitle">Vos séjours à venir et votre historique</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                </div>
            </a>

            <a href="{{ url('/dashboard/mes-demandes') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Demandes reçues</h3>
                    <p class="bento-subtitle">Répondez aux voyageurs intéressés</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                </div>
            </a>

            <a href="{{ url('/dashboard/mes-recherches') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Mes recherches</h3>
                    <p class="bento-subtitle">Vos alertes et filtres sauvegardés</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </a>

            <a href="{{ url('/dashboard/centre-d-aide') }}" class="bento-card">
                <div class="bento-content">
                    <h3 class="bento-title">Centre d'aide</h3>
                    <p class="bento-subtitle">Suivi de vos signalements et litiges</p>
                </div>
                <div class="bento-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                </div>
            </a>

        </div>

        <form method="POST" action="{{ url('logout') }}" class="w-fit">
            @csrf
            <button type="submit" class="other-btn">Se déconnecter</button>
        </form>
    </div>
</div>

<style>
    .bento-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
        margin-top: 1rem;
    }

    @media (min-width: 768px) {
        .bento-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .bento-card {
        position: relative;
        background-color: var(--bg-card);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-card);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 220px;
        overflow: hidden;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
    }

    .bento-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card);
        border-color: rgba(var(--primary-rgb), 0.3);
    }

    .bento-content {
        z-index: 2;
    }

    .bento-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .bento-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.4;
    }

    .bento-icon {
        position: absolute;
        bottom: -20px;
        left: -20px;
        z-index: 1;
        opacity: 0.08;
        color: var(--text-main);
        transition: all 0.5s ease;
    }

    .bento-icon svg {
        width: 140px;
        height: 140px;
    }

    .bento-card:hover .bento-icon {
        transform: scale(1.1) rotate(5deg);
        opacity: 0.12;
        color: var(--primary);
    }
</style>
@endsection