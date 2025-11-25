
@extends('layout')

@section('title', 'Résultat(s)')

@section('content')
  @foreach($annonces as $annonce)
    <div>
      {{ $annonce->titre_annonce  }} <br> {{ $annonce->ville->nomville }} <br><br>
    </div>
  @endforeach
@endsection

