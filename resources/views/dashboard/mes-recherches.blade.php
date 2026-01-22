@extends('layout')

@section('title', 'Mes Recherches')

@section('content')
<div class="center-container">
    <div class="dashboard-container">
        
        <a href="{{ url('/dashboard') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Retour
        </a>

        <x-filter-bar 
            action="{{ url('/dashboard/mes-recherches') }}"
            :withLocation="true" 
            :withType="true"
            :types="$types"
            placeholder="Rechercher par titre..."
        >
            <div class="filter-group grow-2">
                <label class="filter-label">Mot-clé / Titre</label>
                <div class="input-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: Vacances été..." style="width: 100%;">
                </div>
            </div>
        </x-filter-bar>

        <div class="listings-list"> 
            
            @forelse($recherches as $search)
                
                <div class="manage-card">
                    
                    <a href="{{ route('resultats', [
                        'search'        => $search->mot_clef,
                        'nbVoyageurs'   => $search->nb_voyageurs,
                        'datedebut'     => $search->date_debut_recherche,
                        'datefin'       => $search->date_fin_recherche,
                        'prixMin'       => $search->prix_min,
                        'prixMax'       => $search->prix_max,
                        'filtreTypeHebergement' => $search->type_hebergement
                    ]) }}" class="manage-card-img-link search-visual">
                        
                        <div class="search-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="search-big-icon">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <span class="search-cta">Lancer la recherche</span>
                        </div>
                    </a>

                    <a href="{{ route('resultats', [
                        'search'        => $search->mot_clef,
                        'nbVoyageurs'   => $search->nb_voyageurs,
                        'datedebut'     => $search->date_debut_recherche,
                        'datefin'       => $search->date_fin_recherche,
                        'prixMin'       => $search->prix_min,
                        'prixMax'       => $search->prix_max,
                        'filtreTypeHebergement' => $search->type_hebergement
                    ]) }}" class="manage-card-content">
                        
                        <div class="manage-header">
                            <h3 class="manage-title">{{ $search->pivot->titre_recherche }}</h3>
                            
                            <div class="status-pill st-pending">
                                {{ $search->type_hebergement ?: 'Tous types' }}
                            </div>
                        </div>

                        <div class="search-criteria-grid">
                            
                            <div class="criteria-item">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span>
                                    @if($search->date_debut_recherche)
                                        {{ \Carbon\Carbon::parse($search->date_debut_recherche)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($search->date_fin_recherche)->format('d/m/Y') }}
                                    @else
                                        Dates flexibles
                                    @endif
                                </span>
                            </div>

                            <div class="criteria-item">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span>{{ $search->nb_voyageurs ?? 1 }} personnes</span>
                            </div>

                            <div class="criteria-item">
                                <svg class="criteria-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                <span>
                                    @if($search->prix_min || $search->prix_max)
                                        {{ $search->prix_min ?? 0 }}€ - {{ $search->prix_max ?? '∞' }}€
                                    @else
                                        Tous prix
                                    @endif
                                </span>
                            </div>

                        </div>
                        
                        <div class="manage-location-row" style="margin-top:auto;">
                            <span class="search-location-preview">{{ $search->mot_clef ?: 'Partout' }}</span>
                        </div>
                    </a>

                    <div class="manage-card-actions">
                        
                        <form action="{{ route('recherche.destroy', $search->idcritere) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-icon-btn btn-delete" title="Supprimer la recherche" onclick="return confirm('Supprimer cette recherche sauvegardée ?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>

                    </div>

                </div>

            @empty
                
                <div class="full-width text-center" style="padding: 4rem; background: var(--bg-card); border-radius: var(--radius-card); border: 1px solid var(--border-default);">
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">
                        Vous n'avez aucune recherche sauvegardée.
                    </p>
                    <a href="{{ url('/') }}" class="other-btn">
                        Lancer une recherche
                    </a>
                </div>

            @endforelse

        </div>

        <div class="pagination-container">
            {{ $recherches->links('pagination.custom') }}
        </div>

    </div>
</div>

<style>
    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        height: fit-content;
    }
    .st-pending { background: #FFF4E5; color: #B45309; }

    .search-visual {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        background: linear-gradient(135deg, var(--bg-subtle) 0%, var(--primary-light) 100%);
    }

    .search-icon-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .search-big-icon {
        opacity: 0.3;
        margin-bottom: 0.5rem;
    }
    .search-location-preview {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-main);
        text-align: center;
        padding: 0 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .search-criteria-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .criteria-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .criteria-icon {
        color: var(--primary);
        flex-shrink: 0;
    }

    .search-cta {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection