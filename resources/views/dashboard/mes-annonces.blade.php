@extends('layout')

@section('title', 'Mes annonces')

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
            action="{{ url('/dashboard/mes-annonces') }}"
            :withLocation="true"
            :withType="true"
            :types="$types"
            :withPrice="true"
            :withGuests="true"
            placeholder="Rechercher une ville..."
        />

        <div class="listings-list"> 
            
            @forelse($annonces as $annonce)
                
                <div class="manage-card">
                    
                    <a href="{{ url('annonce/'.$annonce->idannonce) }}" class="manage-card-img-link">
                        @if(isset($annonce->photo) && count($annonce->photo) > 0)
                            <img class="similaire-image" loading="lazy" src="{{ $annonce->photo[0]->nomphoto }}" alt="Photo annonce"/>
                        @else
                            <div class="no-photo-placeholder">
                                Sans photo
                            </div>
                        @endif
                    </a>

                    <a href="{{ url('annonce/'.$annonce->idannonce) }}" class="manage-card-content">
                        <div class="manage-header">
                            <h3 class="manage-title">{{ $annonce->titre_annonce }}</h3>
                            <span class="manage-price">{{ ceil($annonce->prix_nuit) }}€ <span class="unit">/ nuit</span></span>
                        </div>

                        <div class="manage-meta">
                            <span class="meta-tag">{{ $annonce->type_hebergement->nom_type_hebergement ?? 'Type inconnu' }}</span>
                            <span class="meta-dot">•</span>
                            <span>{{ $annonce->nb_personnes_max }} pers.</span>
                            <span class="meta-dot">•</span>
                            <span>{{ $annonce->nb_bebe_max }} bébé(s)</span>
                            <span class="meta-dot">•</span>
                            <span>{{ $annonce->nombre_chambre }} chambre(s)</span>
                        </div>

                        <div class="manage-location">
                            <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $annonce->adresse_annonce }} • {{ $annonce->ville->nom_ville ?? 'Ville' }}
                        </div>
                    </a>

                    <div class="manage-card-actions">
                        
                        <form action="{{ url('modifier_annonce') }}" method="GET">
                            <input type="hidden" name="annonce_modif" value="{{ $annonce->idannonce }}">
                            <button type="submit" class="action-icon-btn btn-edit" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                        </form>

                        <form action="{{ url('supprimer_annonce') }}" method="POST">
                            @csrf
                            <input type="hidden" name="annonce_supp" value="{{ $annonce->idannonce }}">
                            <button type="submit" class="action-icon-btn btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sur de vouloir supprimer cette annonce ? Cette action est irréversible.')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>

                    </div>

                </div>

            @empty
                
                <div class="full-width text-center" style="padding: 4rem; background: var(--bg-card); border-radius: var(--radius-card); border: 1px solid var(--border-default);">
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">
                        Aucune annonce trouvée.
                    </p>
                    <a href="{{ url('/dashboard/mes-annonces') }}" class="other-btn">
                        Réinitialiser les filtres
                    </a>
                </div>

            @endforelse

        </div>

        <div style="margin-top: 2rem;">
            {{ $annonces->links() }}
        </div>

    </div>
</div>
@endsection