<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Lumina')</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.css" rel="stylesheet">
    
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="container">
    <header>
        <h1>@yield('title', 'Leboncoin')</h1>
    </header>

    <nav>
        @section('nav')
            <ul>
                <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Accueil</a></li>
                <li><a href="{{ url('/recherche') }}" class="{{ Request::is('recherche') ? 'active' : '' }}">Rechercher</a></li>
            </ul>
        @show
    </nav>

    <main>
        @yield('content')
    </main>
    
    <footer>
        &copy; {{ date('Y') }} Lumina Inc.
    </footer>

    @stack('scripts')
</body>
</html>