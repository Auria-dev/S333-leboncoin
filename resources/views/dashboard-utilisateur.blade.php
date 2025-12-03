@extends('layout')

@section('title', 'Dashboard')

@section('content')
    
    <h1>Hello {{ $utilisateur->prenom_utilisateur . ' ' . $utilisateur->nom_utilisateur }} </h1>

    <p>Type de compte: {{ $utilisateur->getTypeCompte() }}</p>

    <a href="{{ url('/modifier_compte') }}" class="other-btn"  style='margin-top: 1rem;' wire:navigate>Modifier mon compte</a>

    <form method="POST" action="{{ url('logout') }}" style='margin-top: 1rem;'>
        @csrf
        <button type="submit" class="other-btn">Se déconnecter</button>
    </form>
@endsection