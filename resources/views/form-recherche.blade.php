@extends('layout')

@section('title', 'Rechercher')

@section('content')
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
            <input type="submit" class="submit-btn" value="Rechercher" />
        </div>
    </form>
@endsection