@extends('layout')

@section('title', 'Créer un compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Optionnel: lier a l'ajout de carte banquaire -->

    <form action="{{ url('register') }}" method="POST" style="display: flex;flex-direction: column; gap: 1rem;">
        @csrf

        <input placeholder="Name" type="text" id="name" name="name" value="{{ old('name') }}" autofocus required>
        <input placeholder="Email" type="text" id="email" name="email" value="{{ old('email') }}" required>
        <input placeholder="Password" type="password" id="password" name="password" value="{{ old('password') }}" required>
        <input placeholder="Confirm password" type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
        <input type=submit value="Register">
    </form>
@endsection