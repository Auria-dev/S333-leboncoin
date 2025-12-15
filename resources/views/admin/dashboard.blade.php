<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - LeBonCoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">🛡️ Administration des Annonces</h2>
            <div>
                <span class="text-muted me-3">Connecté en tant que : {{ Auth::user()->email }}</span>
                <a href="/" class="btn btn-outline-secondary">Retour au site</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Annonce</th>
                    <th>Propriétaire</th>
                    <th>Téléphone Vérifié ?</th>
                    <th>Garantie Actuelle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($annonces as $a)
                <tr>
                    <td>
                        <strong>{{ $a->titre_annonce }}</strong><br>
                        <small class="text-muted">{{ $a->ville->nom_ville ?? 'Ville inconnue' }} - {{ $a->prix_nuit }}€/nuit</small>
                    </td>
                    <td>
                        {{ $a->utilisateur->nom }} {{ $a->utilisateur->prenom }}<br>
                        <small>{{ $a->utilisateur->email }}</small><br>
                        <small>{{ $a->utilisateur->telephone ?? 'Pas de numéro' }}</small>
                    </td>
                    <td>
                        @if($a->utilisateur->telephone_verifie)
                            <span class="badge bg-success">OUI ✅</span>
                        @else
                            <span class="badge bg-danger">NON ❌</span>
                        @endif
                    </td>
                    <td>
                        @if($a->est_garantie)
                            <span class="badge bg-warning text-dark">GARANTIE 🌟</span>
                        @else
                            <span class="badge bg-secondary">Aucune</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.toggle.garantie', $a->idannonce) }}" method="POST">
                            @csrf
                            
                            @if($a->est_garantie)
                                {{-- Si déjà garantie, on peut la retirer --}}
                                <button class="btn btn-sm btn-outline-danger">Retirer Garantie</button>
                            @else
                                {{-- Si pas garantie, on vérifie le téléphone --}}
                                @if($a->utilisateur->telephone_verifie)
                                    <button class="btn btn-sm btn-success">Donner Garantie 🌟</button>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled title="Le téléphone doit être vérifié">
                                        Non Eligible 🔒
                                    </button>
                                @endif
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>