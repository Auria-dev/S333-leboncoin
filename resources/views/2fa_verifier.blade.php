@extends('layout')

@section('content')
<div class="container" style="max-width: 500px; margin: 100px auto; text-align: center;">
    <div class="card shadow" style="display:flex; flex-direction: column; gap:1rem;">
        <div class="card-header text-white">
            <h3 class="mb-0">Vérification de sécurité</h3>
        </div>
        <div class="card-body p-4" style="display:flex; flex-direction: column; gap:1rem;">
            <p class="mb-4">Ouvrez votre application <strong>Google Authenticator</strong> et entrez le code temporaire.</p>

            <form action="{{ route('2fa.verify') }}" method="POST" style="display:flex; flex-direction: column; gap:1rem;">
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

                <button class="submit-btn">
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

@endsection