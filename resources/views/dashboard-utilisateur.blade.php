@extends('layout')

@section('title', 'Dashboard')

@section('content')
    
    <h1>Hello {{ $utilisateur->prenom_utilisateur . ' ' . $utilisateur->nom_utilisateur }} </h1>

    <p>ID du compte: {{ $utilisateur->idutilisateur }}</p>

    <p>Type de compte: {{ $utilisateur->getTypeCompte() }}</p>

    <!-- TODO (auria): Modifier mon compte -->

    <form method="POST" action="{{ url('logout') }}">
        @csrf
        <button type="submit">Se déconnecter</button>
    </form>
@endsection