@extends('layout')

@section('title', 'Mes Favoris')

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
            action="{{ url('/dashboard/mes-favoris') }}"
            :withLocation="true"
            :withType="true"
            :types="$types"
            :withPrice="true"
            placeholder="Lyon, Paris, ..."
        />

        <div class="listings-list"> 
            
            @forelse($annonces as $annonce)
                
                <div class="manage-card">
                    
                    <a href="{{ url('annonce/'.$annonce->idannonce) }}" class="manage-card-img-link">
                        @if($annonce->photo && count($annonce->photo) > 0)
                            <img src="{{ $annonce->photo[0]->nomphoto }}" alt="Cover" class="manage-card-img">
                        @else
                            <div class="no-photo-placeholder">Sans photo</div>
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
                            <span>{{ $annonce->nb_personnes_max }} personnes </span>
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
                        
                        <form action="{{ url('/supprimer_fav/' . $annonce->idannonce ) }}" method="GET">
                            @csrf
                            <input type="hidden" name="annonce_id" value="{{ $annonce->idannonce }}">
                            
                            <button type="submit" class="action-icon-btn btn-delete" title="Retirer des favoris" onclick="return confirm('Supprimer cette annonce des favoris ?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-crack-icon lucide-heart-crack"><path d="M12.409 5.824c-.702.792-1.15 1.496-1.415 2.166l2.153 2.156a.5.5 0 0 1 0 .707l-2.293 2.293a.5.5 0 0 0 0 .707L12 15"/><path d="M13.508 20.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5a5.5 5.5 0 0 1 9.591-3.677.6.6 0 0 0 .818.001A5.5 5.5 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5z"/></svg>
                            </button>
                        </form>

                    </div>

                </div>

            @empty
                
                <div class="full-width text-center" style="padding: 4rem; background: var(--bg-card); border-radius: var(--radius-card); border: 1px solid var(--border-default);">
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">
                        Vous n'avez aucun favori pour le moment.
                    </p>
                    <a href="{{ url('/') }}" class="other-btn">
                        Explorer les annonces
                    </a>
                </div>

            @endforelse

            <div class="pagination-container">
                {{ $annonces->links('pagination.custom') }}
            </div>
        </div>
    </div>
</div>

@endsection