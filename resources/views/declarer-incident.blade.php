@extends('layout')

@section('title', 'Déclarer un incident')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <h2>Veuillez décrire précisement votre litige</h2>

     <form class="form-ajouter add-annonce-form" method="POST" action="{{ url('reservation/save_incident') }}">
        @csrf   
        <label>Description</label>
        <textarea id="desc_incident" name="desc_incident" placeholder="" rows="10" required></textarea>
        <input type="hidden" name="idresa" value="{{  $reservation->idreservation }}">
        <input type="submit" value="Déclarer" class="submit-btn">
    </form>
@endsection
