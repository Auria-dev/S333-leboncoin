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

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    if (typeof flatpickr === 'undefined') {
        console.error("Erreur : Flatpickr n'est pas chargé.");
        return;
    }

    const FrenchLocale = {
        weekdays: {
            shorthand: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
            longhand: [
                "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi",
            ],
        },
        months: {
            shorthand: [
                "Janv", "Févr", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc",
            ],
            longhand: [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre",
            ],
        },
        firstDayOfWeek: 1,
        ordinal: (nth) => {
            if (nth > 1) return "";
            return "er";
        },
        rangeSeparator: " au ",
        weekAbbreviation: "Sem",
        scrollTitle: "Défiler pour changer",
        toggleTitle: "Cliquer pour ouvrir",
    };

    const commonConfig = {
        locale: FrenchLocale,
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: true,
        monthSelectorType: 'dropdown',
    };

    const startDateInput = flatpickr("#datedebut", {
        ...commonConfig,
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            endDateInput.set('minDate', dateStr);
            
            if (endDateInput.selectedDates[0] < selectedDates[0]) {
               endDateInput.open();
            }
        }
    });

    const endDateInput = flatpickr("#datefin", {
        ...commonConfig,
        minDate: "today" 
    });
});
</script>
</body>
</html>