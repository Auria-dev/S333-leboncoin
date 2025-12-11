<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Lumina')</title>
    
    <!-- link icon (favicon.ico) -->
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!--map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="{{ asset('js/calendrier.js') }}" defer></script>
</head>

<body class="container">
    <nav>
        @section('nav')
            <div>
                <a href="{{ url('/') }}" class="logo" wire:navigate>
                    <img src="{{ asset('assets/logo.svg') }}" alt="Leboncoin Logo" height="40">
                </a>
            </div>

            <ul>
                <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}" wire:navigate>Accueil</a></li>
                <li><a href="{{ url('/recherche') }}" class="{{ Request::is('recherche') ? 'active' : '' }}" wire:navigate>Rechercher</a></li>
                <li><a href="{{ url('/creer_annonce') }}" class="{{ Request::is('creer_annonce') ? 'active' : '' }}" id="btn-deposer-annonce">Déposer une annonce</a>

            </ul>
            <div>
                @auth
                    <a href="{{ url('/profile') }}" class="button" wire:navigate>Mon compte</a>
                @else
                    <a href="{{ url('/login') }}" class="button" wire:navigate>Se connecter</a>
                @endauth
            </div>
        @show
    </nav>

    <header>
        <h1>@yield('title', 'Leboncoin')</h1>
    </header>

    <main>
        @yield('content')
    </main>
    
    <footer>
        &copy; {{ date('Y') }} Leboncoin (ScoobyFoo)
    </footer>


    @stack('scripts')
    @include('cookie-banniere')

    <div class="light-box-save-search" id="modal-premiere-annonce" style="display: none;">
    <form action="{{ url('verifier_profil') }}" method="POST" class="inline-form" enctype="multipart/form-data" style="width: 100%; max-width: 500px;">
        @csrf
        
        <div class="light-box-content">
            <h4 class="text-center" style="margin-bottom: 5rem; font-size: 22px;">Pour déposer une annonce veuillez transmettre votre pièce d'identité</h4>         
            <input type="file" name="file" id="fileInput" accept=".pdf" required>
            <div class="lightbox-actions">
                <button type="button" class="other-btn" id="btn-cancel-first-ad">Annuler</button>               
                <button type="submit" class="submit-btn">Transmettre et Continuer</button>
            </div>
        </div>

        
    </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const btnDepot = document.getElementById('btn-deposer-annonce');
            const modal = document.getElementById('modal-premiere-annonce');
            const btnCancel = document.getElementById('btn-cancel-first-ad');

            const hasAnnonces = @json(Auth::check() && Auth::user()->annonce()->exists());
            const hasCNI = @json(Auth::check() && Auth::user()->particulier->piece_identite);
            const isParticulier = @json(Auth::check() && DB::table('particulier')->where('idparticulier', Auth::id())->exists());

            if (btnDepot) {
                btnDepot.addEventListener('click', function(e) {
                    if ((hasAnnonces &&  hasCNI != null) || !isParticulier) {
                        return; 
                    }

                    e.preventDefault();
                    modal.style.display = 'flex';
                });
            }

            if (btnCancel) {
                btnCancel.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>