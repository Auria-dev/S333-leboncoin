@extends('layout')
@section('title', 'Propriétaire')
@section('content')
    <div>

    <!-- Ici $proprietaire c'est jeankevin  -->
    <p> {{ $proprietaire->nom_utilisateur . ' ' . $proprietaire->prenom_utilisateur }}</p>
    

    </div>
@endsection
@push('scripts')
@endpush