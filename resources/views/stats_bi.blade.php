<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - User Story 2</title>
    <style>
        /* CSS pour que le tableau prenne tout l'écran */
        body, html { 
            margin: 0; 
            padding: 0; 
            height: 100%; 
            font-family: sans-serif; 
            overflow: hidden; /* Empêche de scroller la page entière */
        }
        
        .header {
            height: 50px;
            background: #333;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .header a {
            color: #ddd;
            text-decoration: none;
            font-weight: bold;
        }

        .header a:hover { color: white; }

        .iframe-container {
            width: 100%;
            height: calc(100vh - 50px); /* 100% de la hauteur moins la barre du haut */
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

    <div class="header">
        <span>📊 Dashboard Directeur</span>
        <a href="{{ url('/') }}">Retour au site ↩</a>
    </div>

    <div class="iframe-container">
        <iframe title="userstory2" 
            src="https://app.powerbi.com/view?r=eyJrIjoiOTcxZTY1ODMtY2FmYi00ZDIxLTk0NTYtOTE5NWQ2N2ViZWM1IiwidCI6ImUyMWU5NzgzLWQwYTAtNDhmOC04NTBlLTBiMDgxYjQ2ZDc4OCIsImMiOjh9" 
            allowFullScreen="true">
        </iframe>
    </div>

</body>
</html>