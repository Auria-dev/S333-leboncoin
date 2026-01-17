@props(['reservation', 'isOwner'])

@if(!$reservation->incident)
    @php return; @endphp
@endif

@php
    $incident = $reservation->incident;
    $status = strtolower($incident->statut_incident ?? 'en attente');
    
    $st_class = 'st-incident-pending';
    if(Str::contains($status, ['resolu', 'résolu'])) $st_class = 'st-incident-resolved';
    elseif(Str::contains($status, ['clos', 'cloture'])) $st_class = 'st-incident-closed';
    elseif(Str::contains($status, ['rejet', 'refus'])) $st_class = 'st-incident-rejected';

    $dateSignalement = $incident->date_signalement 
        ? \Carbon\Carbon::parse($incident->date_signalement)->format('d/m/Y') 
        : 'Date inconnue';
@endphp

<div class="manage-card">
    
    <a href="{{ url('reservation/'.$reservation->idreservation) }}" class="manage-card-img-link">
        @if(isset($reservation->annonce->photo) && count($reservation->annonce->photo) > 0)
            <img src="{{ $reservation->annonce->photo[0]->nomphoto }}" alt="Cover" class="manage-card-img">
        @else
            <div class="no-photo-placeholder">Sans photo</div>
        @endif
        
        <div class="status-pill {{ $st_class }}" style="position: absolute; top: 10px; left: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            {{ $incident->statut_incident ?? 'En attente' }}
        </div>
    </a>

    <a href="{{ url('reservation/'.$reservation->idreservation) }}" class="manage-card-content">
        
        <div class="manage-header">
            <h3 class="manage-title">{{ $reservation->annonce->titre_annonce }}</h3>
            <span class="meta-tag">
                Signalé le {{ $dateSignalement }}
            </span>
        </div>

        @if($isOwner)
            <div class="manage-guest-row">
                @php $guest = $reservation->particulier->utilisateur ?? null; @endphp
                @if($guest)
                    @if($guest->photo_profil)
                        <img src="{{ $guest->photo_profil }}" class="mini-guest-pfp">
                    @else
                        <img src="/images/photo-profil.jpg" class="mini-guest-pfp">
                    @endif
                    <span class="guest-name">
                        Signalé par <strong>{{ $guest->prenom_utilisateur }} {{ $guest->nom_utilisateur }}</strong>
                    </span>
                @else
                    <span class="guest-name">Utilisateur inconnu</span>
                @endif
            </div>
        @else
            <div class="manage-meta">
                <svg class="icon-pin" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                {{ $reservation->annonce->ville->nom_ville ?? 'Ville' }}
            </div>
        @endif

        <div class="incident-preview">
            "{{ Str::limit($incident->description_incident ?? 'Aucune description', 100) }}"
        </div>
        
    </a>

    <div class="manage-card-actions">
        <a href="{{ url('reservation/'.$reservation->idreservation) }}" class="action-icon-btn" title="Gérer l'incident">
             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
        </a>
    </div>
</div>