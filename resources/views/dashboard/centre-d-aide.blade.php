@extends('layout')

@section('title', 'Centre d\'aide')

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
            action="{{ url('/dashboard/centre-d-aide') }}"
            :withLocation="false" 
            :withType="false"
            :customSlot="true"
            placeholder="Rechercher..."
        >
            <div class="filter-group grow-2">
                <label class="filter-label">Annonce</label>
                <div class="input-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre de l'annonce..." style="min-width: 200px;">
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Statut Incident</label>
                <select name="statut">
                    <option value="">Tous</option>
                    <option value="remboursé" @selected(request('statut') == 'remboursé')>Remboursé</option>
                    <option value="clos" @selected(request('statut') == 'clos')>Clos</option>
                    <option value="déclaré" @selected(request('statut') == 'déclaré')>Déclaré</option>
                    <option value="au contentieux" @selected(request('statut') == 'au contentieux')>Au contentieux</option>
                    <option value="sans suite" @selected(request('statut') == 'sans suite')>Sans suite</option>
                </select>
            </div>
        </x-filter-bar>

        <div class="res-section">
            <h2 class="section-title">Mes signalements (Locataire)</h2>
            <div class="listings-list"> 
                @forelse($myIncidents as $res)
                    @include('components.incident-card', ['reservation' => $res, 'isOwner' => false])
                @empty
                    <div class="empty-box">
                        <p>Vous n'avez signalé aucun incident.</p>
                    </div>
                @endforelse
                
                <div class="pagination-container">
                    {{ $myIncidents->links('pagination.custom') }}
                </div>
            </div>
        </div>

        @if(auth()->user()->getTypeParticulier() !== 'Locataire')
        <div class="res-section" style="margin-top: 3rem;">
            <h2 class="section-title">Incidents sur mes propriétés (Propriétaire)</h2>
            <div class="listings-list"> 
                @forelse($propertyIncidents as $res)
                    @include('components.incident-card', ['reservation' => $res, 'isOwner' => true])
                @empty
                    <div class="empty-box">
                        <p>Aucun incident signalé sur vos propriétés.</p>
                    </div>
                @endforelse

                <div class="pagination-container">
                    {{ $propertyIncidents->links('pagination.custom') }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    .section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary);
        padding-left: 10px;
    }
    
    .empty-box {
        width: 100%;
        text-align: center;
        padding: 3rem;
        background: var(--bg-card);
        border-radius: var(--radius-card);
        border: 1px dashed var(--border-default);
        color: var(--text-muted);
        font-style: italic;
    }

    .listings-list { display: flex; flex-direction: column; gap: 1.25rem; width: 100%; }
    .manage-card { display: flex; background-color: var(--bg-card); border: 1px solid var(--border-default); border-radius: var(--radius-card); overflow: hidden; transition: all 0.2s ease; position: relative; height: 180px; }
    .manage-card:hover { border-color: var(--primary); box-shadow: var(--shadow-sm); transform: translateY(-2px); }
    .manage-card-img-link { width: 220px; flex-shrink: 0; background-color: var(--bg-subtle); position: relative; }
    .manage-card-img { width: 100%; height: 100%; object-fit: cover; }
    .no-photo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.9rem; background-color: var(--bg-subtle); }
    
    .manage-card-content { flex: 1; padding: 1.25rem; display: flex; flex-direction: column; justify-content: center; text-decoration: none !important; border-right: 1px solid var(--border-default); }
    .manage-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
    .manage-title { font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    
    .manage-meta { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px; }
    .meta-tag { background-color: var(--bg-subtle); padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; color: var(--text-muted); }

    .manage-card-actions { width: 80px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; background-color: var(--bg-subtle); flex-shrink: 0; }
    .action-icon-btn { width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-default); background-color: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--text-muted); }
    .action-icon-btn:hover { transform: scale(1.1); color: var(--text-main); border-color: var(--primary); }
    
    .pagination-container { margin-top: 1.5rem; width: 100%; display: flex; justify-content: center; }
    
    .manage-guest-row {
        display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; padding: 0.5rem; background-color: var(--bg-highlight); border-radius: 8px; width: fit-content;
    }
    .mini-guest-pfp { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }
    .guest-name { font-size: 0.85rem; color: var(--text-main); }
    
    .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; height: fit-content; color: white;}
    .st-incident-pending { background: #f59e0b; }
    .st-incident-resolved { background: #10b981; }
    .st-incident-closed { background: #6b7280; }
    .st-incident-rejected { background: #ef4444; }
    
    .incident-preview {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-style: italic;
        margin-top: auto;
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .manage-card { flex-direction: column; height: auto; }
        .manage-card-img-link { width: 100%; height: 150px; }
        .manage-card-content { border-right: none; border-bottom: 1px solid var(--border-default); }
        .manage-card-actions { width: 100%; flex-direction: row; padding: 1rem; height: auto; }
    }
</style>
@endsection