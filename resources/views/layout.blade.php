<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LeMauvaisCoin')</title>
    
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LeMauvaisCoin')</title>
    
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    

    <style>
        body {
            min-height: 100vh; 
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1; 
        }

        .lbc-footer {
            background-color: #2b323e; 
            color: white;
            padding: 50px 0 20px 0;
            margin-top: 60px; 
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            width: 100%;
        }
        
        .footer-content {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            box-sizing: border-box;
        }

        .footer-col h3 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a5568;
            color: #fff;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col li {
            margin-bottom: 10px;
        }

        .footer-col a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-col a:hover {
            color: #ec5a13; 
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #4a5568;
            text-align: center;
            color: #a0aec0;
            font-size: 12px;
        }
    </style>
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
                <li><a href="{{ url('/creer_annonce') }}" class="{{ Request::is('creer_annonce') ? 'active' : '' }}" id="btn-deposer-annonce">Déposer une annonce</a></li>
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
    
    @stack('scripts')
    @include('cookie-banniere')

    <div class="light-box-save-search" id="modal-premiere-annonce" style="display: none;">
    <form action="{{ url('verifier_profil') }}" method="POST" class="inline-form" enctype="multipart/form-data" style="width: 100%; max-width: 500px;">
        @csrf
        
        <div class="light-box-content">
            <h4 class="text-center" style="margin-bottom: 1rem; font-size: 22px;">Pour déposer une annonce veuillez transmettre votre pièce d'identité</h4>         
            <button type="button" id="customSelectBtn" class="btn btn-select">
                Sélectionner un fichier
            </button>
            <span id="fileChosen">Aucun fichier sélectionné</span>
            <input type="file" name="file" id="realFileInput" accept=".pdf" hidden>

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
        const isLogged = @json(Auth::check());
        const isParticulier = @json(Auth::check() && DB::table('particulier')->where('idparticulier', Auth::id())->exists());
        
        const rawCNI = @json(Auth::check() ? optional(Auth::user()->particulier)->piece_identite : null);
        const hasCNI = (rawCNI !== null && rawCNI !== "");

        if (btnDepot) {
            btnDepot.addEventListener('click', function(e) {
                if (!isLogged) return;

                if (isParticulier && !hasCNI) {
                    e.preventDefault();
                    console.log("Modal ouvert : CNI manquante");
                    if(modal) modal.style.display = 'flex';
                } 
                else {
                    console.log("Accès autorisé sans modal");
                }
            });
        }

        if (btnCancel && modal) {
            btnCancel.addEventListener('click', function(e) {
                e.preventDefault();
                modal.style.display = 'none';
            });
        }
        
        const customBtn = document.getElementById('customSelectBtn');
        const fileText = document.getElementById('fileChosen');
        const realInput = document.getElementById('realFileInput');

        if (customBtn && realInput && fileText) {
            customBtn.addEventListener('click', function(e) {
                e.preventDefault();
                realInput.click();
            });
            realInput.addEventListener('change', function() {
                if (realInput.files.length > 0) {
                    fileText.textContent = realInput.files[0].name;;
                } else {
                    fileText.textContent = "Aucun fichier sélectionné";
                }
            });
        }
    });
    </script>


<div id="toast-container"></div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const Toast = {
        container: document.getElementById('toast-container'),
        
        config: {
            success: { title: 'Succès', icon: '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>' },
            error:   { title: 'Erreur', icon: '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>' },
            warning: { title: 'Attention', icon: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>' },
            info:    { title: 'Information', icon: '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>' }
        },

        fire(type, data) {
            let title = this.config[type]?.title || 'Notification';
            let message = '';

            if (typeof data === 'object' && data !== null) {
                title = data.title || title;
                message = data.message || '';
            } else {
                message = data;
            }
            this.create(type, title, message);
        },

        create(type, title, message) {
            const el = document.createElement('div');
            el.className = `toast-card toast-${type} toast-entering`;
            
            const iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${this.config[type]?.icon || this.config.info.icon}</svg>`;

            el.innerHTML = `
                <div class="toast-accent"></div>
                <div class="toast-body">
                    <div class="toast-icon-wrapper">${iconSvg}</div>
                    <div class="toast-texts">
                        <div class="toast-title">${title}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="toast-progress-bg">
                    <div class="toast-progress-bar"></div>
                </div>
            `;

            this.container.appendChild(el);

            const duration = 5000;
            const progressBar = el.querySelector('.toast-progress-bar');
            let remaining = duration;
            let timerId;
            let startTime;
            let isPaused = false;

            const close = () => {
                if(isPaused) return;
                
                el.classList.remove('toast-entering');
                el.classList.add('toast-leaving');
                
                el.addEventListener('animationend', () => {
                    if(el.parentElement) el.remove();
                });
            };

            const startTimer = () => {
                isPaused = false;
                startTime = Date.now();
                
                progressBar.style.transition = `width ${remaining}ms linear`;
                void progressBar.offsetWidth;
                progressBar.style.width = '0%';

                timerId = setTimeout(() => {
                    close(); 
                }, remaining);
            };

            const pauseTimer = () => {
                isPaused = true;
                clearTimeout(timerId);
                remaining -= Date.now() - startTime;
                
                const computedWidth = getComputedStyle(progressBar).width;
                progressBar.style.transition = 'none';
                progressBar.style.width = computedWidth;
            };

            el.addEventListener('mouseenter', pauseTimer);
            el.addEventListener('mouseleave', startTimer);
            
            el.querySelector('.toast-close').addEventListener('click', (e) => {
                e.stopPropagation();
                clearTimeout(timerId);
                isPaused = false;
                close();
            });

            requestAnimationFrame(() => startTimer());
        }
    };

    @if(session('success'))
        Toast.fire('success', @json(session('success')));
    @endif

    @if(session('error'))
        Toast.fire('error', @json(session('error')));
    @endif

    @if(session('warning'))
        Toast.fire('warning', @json(session('warning')));
    @endif

    @if(session('info') || session('status'))
        Toast.fire('info', @json(session('info') ?? session('status')));
    @endif
});
</script>
<script src="{{ asset('js/calendrier.js') }}" defer></script>


<script>
    var botmanWidget = {
        title: 'Assistant Immo',
        aboutText: 'Guide de navigation',
        introMessage: "Bonjour ! Je suis votre guide. Comment puis-je vous aider à utiliser le site ?",
        placeholderText: "Posez votre question...",
        desktopHeight: 450,
        desktopWidth: 370
    };
</script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

<link rel="preload" href="{{ asset('css/app.css') }}" as="style">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<footer class="lbc-footer">
        <div class="footer-content">
            <div class="footer-col">
                <h3>À propos du Boncoin</h3>
                <ul>
                    <li><a href="{{ route('legal.about') }}">Qui sommes-nous ?</a></li>
                    <li><a href="{{ route('legal.engagements') }}">Nos engagements RSE</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Informations Légales</h3>
                <ul>
                    <li><a href="{{ route('legal.cgu') }}">Conditions Générales d'Utilisation</a></li>
                    <li><a href="{{ route('legal.regles') }}">Règles de diffusion</a></li>
                    <li><a href="{{ route('legal.mentions') }}">Mentions Légales</a></li>
                    <li><a href="{{ route('legal.privacy') }}">Politique de Confidentialité</a></li>
                    <li><a href="{{ route('legal.cookies') }}">Gestion des cookies</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Des questions ?</h3>
                <ul>
                    <li><a href="{{ route('legal.securite') }}">Centre de sécurité</a></li>
                    <li><a href="{{ route('legal.contact') }}">Nous contacter</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Projet SAE 3.01</h3>
                <p style="color: #a0aec0; margin-bottom: 10px;">
                    Application développée par le Groupe 1 - IUT Annecy.
                </p>
                <p style="color: #a0aec0;">
                    Hébergé par <strong style="color: #ec5a13;">OVHcloud</strong>.
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; {{ date('Y') }} Leboncoin (ScoobyFoo). Tous droits réservés.
        </div>
    </footer>
</body>
</html>