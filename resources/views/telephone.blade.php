<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Vérification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Vérification du téléphone</h2>
    
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif

    <div class="card p-4 mb-3">
        <h4>1. Renvoyer le code (si besoin)</h4>
        <form action="{{ route('action.envoyer.sms') }}" method="POST">
            @csrf
            <input type="text" name="telephone" class="form-control mb-2" value="{{ $user->telephone }}" required>
            <button class="btn btn-primary">Envoyer SMS</button>
        </form>
    </div>

    <div class="card p-4">
        <h4>2. Valider le code</h4>
        <form action="{{ route('action.verifier.code') }}" method="POST">
            @csrf
            <input type="text" name="code" class="form-control mb-2" placeholder="Ex: 123456" required>
            <button class="btn btn-success">Valider</button>
        </form>
    </div>
</body>
</html>