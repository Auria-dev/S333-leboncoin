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
</body>
</html>