@extends('layout')

@section('title', 'Rechercher')

@section('content')
<div class="container">
    
    <main>
        <form method="post" action="{{ url('resultats') }}">
            @csrf

            <div class="form-group">
                <label for="search">Nom de ville</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Ex: Paris, Lyon..." 
                    style="width:100%;"
                    required
                />
            </div>

            <div class="form-group">
                <input type="submit" value="Rechercher" style="width:100%;"/>
            </div>

        </form>
    </main>

</div>
@endsection