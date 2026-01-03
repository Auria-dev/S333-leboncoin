@extends('layout')

@section('title', 'Ajouter un Équipement')

@section('content')
<div class="reservation-container" style="max-width: 1200px; margin: 0 auto; padding: var(--spacing);">
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing);">
        
        <div class="reservation-card">
            <h2 class="reservation-subtitle">Équipements disponibles</h2>
            
            <div style="margin-bottom: 2rem;">
                <ul style="list-style: none; padding: 0;">
                    @foreach($equipements as $equipement)
                        <li style="padding: 10px; border-bottom: 1px solid var(--border-default); display: flex; justify-content: space-between;">
                            <span>{{ $equipement->nom_equipement }}</span>
                            <span class="text-muted" style="font-size: 0.8rem;">ID: {{ $equipement->idequipement }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <h3 class="font-bold mb-sm">Créer un nouvel équipement</h3>
            <form action="{{ url('/admin/equipement/store') }}" method="POST">
                @csrf
                <div class="reservation-input-group">
                    <input type="text" name="nom_equipement" class="reservation-input" placeholder="Ex: Wifi, Piscine, Parking..." required>
                </div>
                
                <div class="radio-group-container" style="margin-top: 1rem; display: flex; gap: 1rem;">
                    <div class="radio-option">
                        <input type="radio" id="typeEquipements" name="idcategorie" value="1" required>
                        <label for="typeEquipements">Equipements</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="typeExterieur" name="idcategorie" value="2">
                        <label for="typeExterieur">Extérieur</label>
                    </div>
                </div>

                <button type="submit" class="submit-btn mt-md w-full">Créer l'équipement</button>
            </form>
        </div>

        <div class="reservation-card" style="height: fit-content; position: sticky; top: 4rem;">
            <h2 class="reservation-subtitle">Lier un équipement à une annonce</h2>
            <p class="text-muted mb-md">Sélectionnez une annonce pour lui ajouter une option.</p>

            <form action="{{ url('/admin/equipement/link') }}" method="POST">
                @csrf
                <div class="reservation-input-group">
                    <label class="reservation-label">Choisir l'annonce</label>
                    <select name="idannonce" class="reservation-input" style="height: auto;">
                        @foreach($annonces as $annonce)
                            <option value="{{ $annonce->idannonce }}">
                                {{ $annonce->titre_annonce }} 
                                ({{ $annonce->ville->nom_ville ?? 'Ville inconnue' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="reservation-input-group mt-md">
                    <label class="reservation-label">Équipement à ajouter</label>
                    <select name="idequipement" class="reservation-input" style="height: auto;">
                        @foreach($equipements as $equipement)
                            <option value="{{ $equipement->idequipement }}">{{ $equipement->nom_equipement }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="other-btn mt-md w-full" style="border-color: var(--primary); color: var(--primary);">
                    Ajouter cet équipement
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