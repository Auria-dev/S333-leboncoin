@extends('layouts.app') @section('content')
<div class="container">
    <h1>Vérifiez votre adresse email</h1>
    
    @if (session('message'))
        <div class="alert alert-success">
            Un nouveau lien de vérification a été envoyé à votre adresse email.
        </div>
    @endif

    <p>Avant de continuer, veuillez vérifier votre email pour un lien de validation.</p>
    <p>Si vous n'avez pas reçu l'email :</p>
    
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Renvoyer le lien</button>
    </form>
</div>
@endsection