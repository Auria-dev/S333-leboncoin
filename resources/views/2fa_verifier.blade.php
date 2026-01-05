@extends('layout')

@section('content')
<div class="container" style="max-width: 500px; margin: 100px auto; text-align: center;">
    <div class="card shadow">
        <div class="card-header text-white" style="background-color: #ff6e14;">
            <h3 class="mb-0">Vérification de sécurité</h3>
        </div>
        <div class="card-body p-4">
            <p class="mb-4">Ouvrez votre application <strong>Google Authenticator</strong> et entrez le code temporaire.</p>

            <form action="{{ route('2fa.verify') }}" method="POST">
                @csrf
                
                <div class="form-group mb-4">
                    <input type="text" name="code" class="form-control form-control-lg" placeholder="123 456" required autofocus
                           style="text-align: center; font-size: 2em; letter-spacing: 10px; width: 100%;">
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <button class="btn btn-primary btn-lg btn-block w-100" style="background-color: #ff6e14; border: none;">
                    Valider la connexion
                </button>
            </form>
        </div>
    </div>
</div>
<style>
    .auth-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .auth-header {
        background: linear-gradient(135deg, #ff6e14 0%, #ff8f4b 100%);
        padding: 30px;
        text-align: center;
        color: white;
    }
    .otp-input {
        letter-spacing: 12px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        border: 2px solid #eee;
        border-radius: 8px;
        height: 60px;
        transition: all 0.3s;
    }
    .otp-input:focus {
        border-color: #ff6e14;
        box-shadow: 0 0 0 3px rgba(255, 110, 20, 0.1);
        outline: none;
    }
    .btn-orange {
        background-color: #ff6e14;
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        border: none;
        transition: background 0.3s;
    }
    .btn-orange:hover {
        background-color: #e55e0d;
        color: white;
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card auth-card">
            <div class="auth-header">
                <i class="fas fa-shield-alt fa-3x mb-3"></i>
                <h4 class="mb-0">Double Authentification</h4>
                <p class="mb-0" style="opacity: 0.9; font-size: 0.9em;">Sécurité renforcée</p>
            </div>
            
            <div class="card-body p-4 text-center">
                <p class="text-muted mb-4">
                    Pour continuer, veuillez entrer le code à 6 chiffres généré par votre application <strong>Google Authenticator</strong>.
                </p>

                <form action="{{ route('2fa.verify') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <input type="text" 
                               name="code" 
                               class="form-control otp-input @if(session('error')) is-invalid @endif" 
                               placeholder="123 456" 
                               maxlength="6"
                               autocomplete="off"
                               required autofocus>
                        
                        @if(session('error'))
                            <div class="text-danger mt-2 small">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    <button class="btn btn-orange btn-block w-100">
                        Vérifier et Connexion
                    </button>
                </form>
                
                <div class="mt-4 text-muted small">
                    Code perdu ? Contactez le support.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection