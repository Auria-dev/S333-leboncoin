@extends('layout')
@section('content')
<style>
    .activation-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
        margin-top: 30px;
        margin-bottom: 50px;
    }
    .activation-header {
        background: linear-gradient(to right, #fff5ec, #ffffff);
        padding: 25px;
        text-align: center;
        border-bottom: 1px solid #ffe6d4;
    }
    .activation-header h3 {
        color: #ff6e14;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .step-row {
        display: flex;
        padding: 25px;
        border-bottom: 1px solid #f1f1f1;
    }
    .step-number {
        background-color: #ff6e14;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        margin-right: 20px;
    }
    .step-content {
        width: 100%;
    }
    .qr-container {
        text-align: center;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        display: inline-block;
        margin: 15px 0;
        border: 1px solid #eee;
    }
    .manual-code {
        background: #eee;
        padding: 5px 10px;
        border-radius: 4px;
        font-family: monospace;
        font-weight: bold;
        letter-spacing: 1px;
        color: #333;
    }
    .code-input {
        text-align: center;
        font-size: 1.5em;
        letter-spacing: 8px;
        max-width: 250px;
        margin: 0 auto;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
    }
    .code-input:focus {
        border-color: #ff6e14;
        box-shadow: 0 0 0 3px rgba(255, 110, 20, 0.1);
        outline: none;
    }
    .btn-validate {
        background-color: var(--primary); 
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 6px;
        border: none;
        transition: 0.3s;
        max-width: 250px;
        width: 100%;
        margin-top: 1rem;
    }
    .btn-validate:hover {
        background-color:var(--primary-hover); 
        cursor: pointer;
    }
    .link-cancel {
        color: #888;
        text-decoration: none;
        font-size: 0.9em;
        margin-top: 15px;
        display: inline-block;
    }
    .link-cancel:hover {
        color: #333;
        text-decoration: underline;
    }
</style>

<div class="container justify-content-center" style="width: 100%; display:flex; align-items:center; justify-content:center;" >
    <div class="row justify-content-center max-w-650">
        <div class="col-lg-8">
            <div class="card activation-card">
                
                <div class="activation-header">
                    <h3>Activez votre sécurité</h3>
                    <p class="text-muted mb-0">Suivez ces 3 étapes simples pour sécuriser votre compte.</p>
                </div>

                <div class="card-body p-0">
                    
                    <div class="step-row">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5 class="font-weight-bold">Téléchargez l'application</h5>
                            <p class="text-muted mb-0">
                                Installez <strong>Google Authenticator</strong> (ou Authy) sur votre téléphone via l'App Store ou Google Play.
                            </p>
                        </div>
                    </div>

                    <div class="step-row">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5 class="font-weight-bold">Scannez le QR Code</h5>
                            <p class="text-muted">Ouvrez l'application et scannez ce code :</p>
                            
                            <div class="text-center">
                                <div class="qr-container">
                                    {!! $QR_Image !!}
                                </div>
                            </div>

                            <div class="text-center mt-2">
                                <p class="small text-muted mb-1">Impossible de scanner ? Entrez ce code manuellement :</p>
                                <span class="manual-code">{{ $secret }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="step-row" style="border-bottom: none;">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5 class="font-weight-bold">Confirmez l'activation</h5>
                            <p class="text-muted">Entrez le code à 6 chiffres affiché sur votre application.</p>

                            <form action="{{ route('2fa.confirm') }}" method="POST" class="mt-4 text-center">
                                @csrf
                                <input type="hidden" name="secret" value="{{ $secret }}">
                                
                                <div class="form-group mb-4">
                                    <input type="text" name="code" class="form-control code-input" placeholder="XXX XXX" required maxlength="6">
                                </div>

                                <button type="submit" class="btn-validate form-group mb-4">
                                    Activer maintenant
                                </button>
                                <br>
                                <a href="{{ route('view_modifier_compte') }}" class="link-cancel">Annuler et revenir au profil</a>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection