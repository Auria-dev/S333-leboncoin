@extends('layout')

@section('title', 'Rechercher')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <form method="post" action="{{ url('resultats') }}" style="width: 100%; max-width: 400px; margin: 0 auto;">
        @csrf

        <div class="full-width">
            <label for="search">Nom de ville</label>
            <input 
                type="text" 
                id="search" 
                name="search" 
                placeholder="Ex: Paris, Lyon..." 
                required
            />
        </div>

        <div class="full-width">
            <label for="datedebut">Date début</label>
            <input 
                type="date" 
                id="datedebut" 
                name="datedebut" 
            />
        </div>

        <div class="full-width">
            <label for="datefin">Date fin</label>
            <input 
                type="date" 
                id="datefin" 
                name="datefin" 
            />
        </div>

        <div class="full-width">
            <input type="submit" class="submit-btn" value="Rechercher" />
        </div>
    </form>
@endsection