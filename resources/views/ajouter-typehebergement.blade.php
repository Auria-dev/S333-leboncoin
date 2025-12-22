@extends('layout')

@section('title', 'Ajouter un type d\'hébergement')

@section('content')
<div class="reservation-container" style="max-width: 1200px; margin: 0 auto; padding: var(--spacing);">
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing);">
        
        <div class="reservation-card">
            <h2 class="reservation-subtitle">Types d'hébergement actuels</h2>
            
            <div style="margin-bottom: 2rem;">
                <ul style="list-style: none; padding: 0;">
                    @foreach($type_hebergements as $type)
                        <li style="padding: 10px; border-bottom: 1px solid var(--border-default); display: flex; justify-content: space-between;">
                            <span>{{ $type->nom_type_hebergement }}</span>
                            <span class="text-muted" style="font-size: 0.8rem;">ID: {{ $type->idtypehebergement }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <h3 class="font-bold mb-sm">Ajouter un nouveau type</h3>
            <form action="{{ url('/admin/typehebergement/store') }}" method="POST">
                @csrf
                <div class="reservation-input-group">
                    <input type="text" name="nom_type_hebergement" class="reservation-input" placeholder="Ex: Yourte, Loft, Cabane..." required>
                </div>
                <button type="submit" class="submit-btn mt-md w-full">Créer le type</button>
            </form>
        </div>

        <div class="reservation-card" style="height: fit-content; position: sticky; top: 4rem;">
            <h2 class="reservation-subtitle">Attribuer un type à une annonce</h2>
            <p class="text-muted mb-md">Sélectionnez une annonce pour modifier sa catégorie.</p>

            <form action="{{ url('/admin/annonce/update-type') }}" method="POST">
                @csrf
                <div class="reservation-input-group">
                    <label class="reservation-label">Choisir l'annonce</label>
                    <select name="idannonce" class="reservation-input" style="height: auto;">
                        @foreach($annonces as $annonce)
                            <option value="{{ $annonce->idannonce }}">
                                {{ $annonce->titre_annonce }} ({{ $annonce->ville->nom_ville ?? 'Ville inconnue' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="reservation-input-group mt-md">
                    <label class="reservation-label">Nouveau type à appliquer</label>
                    <select name="idtypehebergement" class="reservation-input" style="height: auto;">
                        @foreach($type_hebergements as $type)
                            <option value="{{ $type->idtypehebergement }}">{{ $type->nom_type_hebergement }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="other-btn mt-md w-full" style="border-color: var(--primary); color: var(--primary);">
                    Mettre à jour l'annonce
                </button>
            </form>
        </div>

    </div>
</div>

@if(session('success'))
    <div style="position: fixed; bottom: 20px; right: 20px; background: var(--success-bg); color: var(--success-text); padding: 1rem var(--spacing); border-radius: var(--radius-sm); border: 1px solid var(--success); z-index: 1000;">
        {{ session('success') }}
    </div>
@endif

@endsection