@extends('layout')

@section('title', 'Mes Demandes Reçues')

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
            action="{{ url('/dashboard/mes-demandes') }}"
            :withLocation="true" 
            :withDates="true"
            :customSlot="true"
            placeholder="Rechercher une annonce ou un voyageur..."
        >
            <label class="filter-label">Statut</label>
            <select name="statut">
                <option value="">Tous</option>
                <option value="pending" @selected(request('statut') == 'pending')>À traiter</option>
                <option value="accepted" @selected(request('statut') == 'accepted')>Acceptées</option>
                <option value="cancelled" @selected(request('statut') == 'cancelled')>Refusées/Annulées</option>
            </select>
        </x-filter-bar>

        <div class="listings-list"> 
            
            @forelse($demandes as $demande)
                
                @php
                    $s = strtolower($demande->statut_reservation);
                    $st_class = 'st-default';
                    if(Str::contains($s, ['valid', 'accept'])) $st_class = 'st-accepted';
                    elseif(Str::contains($s, ['refus', 'annul'])) $st_class = 'st-rejected';
                    elseif(Str::contains($s, ['attent'])) $st_class = 'st-pending';

                    $start = \Carbon\Carbon::parse($demande->date_debut_resa);
                    $end = \Carbon\Carbon::parse($demande->date_fin_resa);
                    $nights = $start->diffInDays($end);
                    
                    $guestUser = $demande->particulier->utilisateur;
                @endphp

                <div class="manage-card">
                    
                    <a href="{{ url('reservation/'.$demande->idreservation) }}" class="manage-card-img-link">
                        @if($demande->annonce->photo && count($demande->annonce->photo) > 0)
                            <img src="{{ $demande->annonce->photo[0]->nomphoto }}" alt="Cover" class="manage-card-img">
                        @else
                            <div class="no-photo-placeholder">Sans photo</div>
                        @endif
                    </a>

                    <a href="{{ url('reservation/'.$demande->idreservation) }}" class="manage-card-content">
                        
                        <div class="manage-header">
                            <h3 class="manage-title">{{ $demande->annonce->titre_annonce }}</h3>
                            
                            <div class="status-pill {{ $st_class }}">
                                {{ $demande->statut_reservation }}
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
                            <span>{{ $demande->nb_adultes + $demande->nb_enfants }} pers.</span>
                        </div>

                        <div class="manage-guest-row">
                            @if($guestUser->photo_profil)
                                <img src="{{ $guestUser->photo_profil }}" class="mini-guest-pfp">
                            @else
                                <img src="/images/photo-profil.jpg" class="mini-guest-pfp">
                            @endif
                            <span class="guest-name">
                                Demande de <strong>{{ $guestUser->prenom_utilisateur }} {{ $guestUser->nom_utilisateur }}</strong>
                            </span>
                        </div>

                        <div class="manage-location-row">
                            <div class="location-text">
                                <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                {{ $demande->annonce->ville->nom_ville ?? 'Ville' }}
                            </div>

                            <div class="price-text">
                                + {{ number_format($demande->montant_total, 0, ',', ' ') }}€
                            </div>
                        </div>
                    </a>

                    <div class="manage-card-actions">
                        
                        @if(Str::contains($s, 'attent'))
                            
                            <form action="{{ url('accepter_reservation') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $demande->idreservation }}">
                                <button type="submit" class="action-icon-btn btn-accept" title="Accepter la demande" onclick="return confirm('Accepter cette réservation ?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            </form>

                            <form action="{{ url('refuser_reservation') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $demande->idreservation }}">
                                <button type="submit" class="action-icon-btn btn-refuse" title="Refuser la demande" onclick="return confirm('Refuser cette réservation ?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>

                        @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                        @endif

                    </div>

                </div>

            @empty
                
                <div class="full-width text-center" style="padding: 4rem; background: var(--bg-card); border-radius: var(--radius-card); border: 1px solid var(--border-default);">
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">
                        Vous n'avez reçu aucune demande de réservation.
                    </p>
                    <a href="{{ url('/dashboard/mes-annonces') }}" class="other-btn">
                        Gérer mes annonces
                    </a>
                </div>

            @endforelse

        </div>

        <div class="pagination-container">
            {{ $demandes->links('pagination.custom') }}
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
    .st-accepted { background: var(--success-bg); color: var(--success-text); }
    .st-rejected { background: var(--danger-bg); color: var(--danger-text); }
    .st-pending { background: #FFF4E5; color: #B45309; }
    .st-default { background: var(--bg-card); color: var(--text-muted); }

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

    .manage-guest-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        background-color: var(--bg-highlight);
        border-radius: 8px;
        width: fit-content;
    }
    .mini-guest-pfp {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }
    .guest-name {
        font-size: 0.85rem;
        color: var(--text-main);
    }

    .btn-accept:hover {
        color: var(--success-text);
        border-color: var(--success);
        background-color: var(--success-bg);
    }
    .btn-refuse:hover {
        color: var(--danger-text);
        border-color: var(--danger);
        background-color: var(--danger-bg);
    }

</style>
@endsection