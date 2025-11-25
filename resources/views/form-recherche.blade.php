@extends('layout')

@section('title', 'Rechercher')

@section('content')
  <form method="post" action="{{ url('resultats') }}">
    @csrf
    <input name="search"/>
    <input type="submit"/>
  </form>
@endsection

