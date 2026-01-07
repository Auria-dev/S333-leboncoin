<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Directeur</title>
    <style>
        body { font-family: sans-serif; margin: 0; height: 100vh; display: flex; flex-direction: column; background: #f4f4f4; }
        .header { background: #333; color: white; padding: 15px; display: flex; justify-content: space-between; }
        .header a { color: #ddd; text-decoration: none; }
        
        .login-box { margin: auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 300px;}
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 10px; font-size: 14px; }
        
        .iframe-container { flex: 1; width: 100%; height: 100%; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

    <div class="header">
        <span>📊 Espace Directeur</span>
        <a href="{{ url('/') }}">Retour au site</a>
    </div>

    @if(session('acces_autorise'))
        <div class="iframe-container">
            <iframe title="Rapport SAE" 
                src="https://app.powerbi.com/view?r=eyJrIjoiOTcxZTY1ODMtY2FmYi00ZDIxLTk0NTYtOTE5NWQ2N2ViZWM1IiwidCI6ImUyMWU5NzgzLWQwYTAtNDhmOC04NTBlLTBiMDgxYjQ2ZDc4OCIsImMiOjh9" 
                allowFullScreen="true">
            </iframe>
        </div>
    @else
        <div class="login-box">
            <h2>Accès Restreint</h2>
            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            <form action="/check-stat-login" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    @endif

</body>
</html>