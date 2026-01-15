@extends('layout')

@section('title', 'Mes Voyages')

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
            action="{{ url('/dashboard/mes-voyages') }}"
            :withLocation="true"
            :withDates="true"
            :customSlot="true"
            placeholder="Rechercher une destination..."
        >
            <label class="filter-label">Statut</label>
            <select name="statut">
                <option value="">Tous</option>
                <option value="pending" @selected(request('statut') == 'pending')>En attente</option>
                <option value="accepted" @selected(request('statut') == 'accepted')>Validés</option>
                <option value="cancelled" @selected(request('statut') == 'cancelled')>Annulés</option>
            </select>
        </x-filter-bar>

        <div class="listings-list"> 
            
            @forelse($reservations as $res)
                
                @php
                    // Status Logic
                    $s = strtolower($res->statut_reservation);
                    $st_class = 'st-default';
                    if(Str::contains($s, ['valid', 'accept'])) $st_class = 'st-accepted';
                    elseif(Str::contains($s, ['refus', 'annul'])) $st_class = 'st-rejected';
                    elseif(Str::contains($s, ['attent'])) $st_class = 'st-pending';

                    // Date Logic
                    $start = \Carbon\Carbon::parse($res->date_debut_resa);
                    $end = \Carbon\Carbon::parse($res->date_fin_resa);
                    $nights = $start->diffInDays($end);
                    $isFinished = $end->isPast();
                @endphp

                <div class="manage-card">
                    
                    <a href="{{ url('reservation/'.$res->idreservation) }}" class="manage-card-img-link">
                        @if($res->annonce->photo && count($res->annonce->photo) > 0)
                            <img src="{{ $res->annonce->photo[0]->nomphoto }}" alt="Cover" class="manage-card-img">
                        @else
                            <div class="no-photo-placeholder">Sans photo</div>
                        @endif
                    </a>

                    <a href="{{ url('reservation/'.$res->idreservation) }}" class="manage-card-content">
                        
                        <div class="manage-header">
                            <h3 class="manage-title">{{ $res->annonce->titre_annonce }}</h3>
                            
                            <div class="status-pill {{ $st_class }}">
                                {{ $res->statut_reservation }}
                            </div>
                        </div>

                        <div class="manage-meta">
                            <span class="meta-tag">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; margin-bottom:-1px"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                {{ $start->format('d M') }} - {{ $end->format('d M') }}
                            </span>
                            <span class="meta-dot">•</span>
                            <span>{{ $nights }} nuits</span>
                            <span class="meta-dot">•</span>
                            <span>{{ $res->nb_adultes + $res->nb_enfants }} voyageur(s)</span>
                        </div>

                        <div class="manage-location-row">
                            <div class="location-text">
                                <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                {{ $res->annonce->adresse_annonce }} • {{ $res->annonce->ville->nom_ville ?? 'Ville' }}
                            </div>

                            <div class="price-text">
                                {{ number_format($res->montant_total, 0, ',', ' ') }}€ <span class="unit">total</span>
                            </div>
                        </div>
                    </a>

                    <div class="manage-card-actions">
                        
                        {{-- Details --}}
                        <a href="{{ url('reservation/'.$res->idreservation) }}" class="action-icon-btn" title="Voir les détails">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>

                        {{-- Review (if eligible) --}}
                        @if($isFinished && Str::contains($s, 'valid'))
                            @if(!$res->avis)
                                <a href="{{ route('avis.create', $res->idreservation) }}" class="action-icon-btn btn-star" title="Noter ce séjour">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                </a>
                            @else
                                <div class="action-icon-btn disabled" title="Avis envoyé">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                            @endif
                        @endif

                    </div>

                </div>

            @empty
                
                <div class="full-width text-center" style="padding: 4rem; background: var(--bg-card); border-radius: var(--radius-card); border: 1px solid var(--border-default);">
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">
                        Vous n'avez effectué aucune réservation.
                    </p>
                    <a href="{{ url('/') }}" class="other-btn">
                        Réserver un séjour
                    </a>
                </div>

            @endforelse

        </div>

        <div class="pagination-container">
            {{ $reservations->links('pagination.custom') }}
        </div>

    </div>
</div>

<style>
    /* --- STATUS BADGES --- */
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
    
    .manage-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        margin-bottom: 0.5rem; 
    }

    .manage-location-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: auto;
    }

    .location-text {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .price-text {
        font-weight: 800;
        color: var(--primary);
        font-size: 1.1rem;
        white-space: nowrap;
        margin-left: 1rem;
    }
    .price-text .unit {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
    }

</style>
@endsection