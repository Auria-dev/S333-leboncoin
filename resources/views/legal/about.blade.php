@extends('layout')

@section('title', 'Qui sommes-nous ? - Lemauvaiscoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        
        <div style="text-align: center; margin-bottom: 60px;">
            <img src="{{ asset('assets/logo.svg') }}" alt="Lemauvaiscoin Groupe" style="height: 60px; margin-bottom: 24px;">
            <h1 style="font-size: 40px; font-weight: 900; color: #2b323e; letter-spacing: -1px; margin: 0;">L'acteur de référence de la vie locale</h1>
        </div>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 16px;">
            
            <section style="margin-bottom: 60px;">
                <h2 style="font-size: 24px; font-weight: 800; color: #2b323e; margin-bottom: 20px;">Notre Histoire</h2>
                <p style="margin-bottom: 20px;">
                    Fondé en 2006, <strong>Lemauvaiscoin</strong> est né d'une ambition simple : permettre à chacun de tout vendre et de tout acheter, près de chez soi. Initialement filiale du groupe de médias norvégien Schibsted, Lemauvaiscoin a connu une croissance exponentielle pour devenir le 1er site de vente entre particuliers en France.
                </p>
                <p>
                    En 2020, une nouvelle page de notre histoire s'est écrite avec l'intégration au sein d'<strong>Adevinta</strong>, suite à l'acquisition stratégique de la branche eBay Classifieds Group. Cette fusion a donné naissance au leader mondial des spécialistes de la petite annonce en ligne, présent dans 11 pays et touchant plus d'un milliard de consommateurs chaque mois. Aujourd'hui, nous accélérons notre diversification en proposant une alternative souveraine et éthique sur le marché de la location saisonnière.
                </p>
            </section>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 60px; text-align: center;">
                <div style="padding: 20px; border-radius: 12px; background: #fff5f0;">
                    <span style="display: block; font-size: 32px; font-weight: 900; color: #ec5a13;">29 M</span>
                    <span style="font-size: 14px; color: #717171; font-weight: 600;">VISITEURS UNIQUES / MOIS</span>
                </div>
                <div style="padding: 20px; border-radius: 12px; background: #fff5f0;">
                    <span style="display: block; font-size: 32px; font-weight: 900; color: #ec5a13;">800 000</span>
                    <span style="font-size: 14px; color: #717171; font-weight: 600;">ANNONCES DE LOCATION</span>
                </div>
                <div style="padding: 20px; border-radius: 12px; background: #fff5f0;">
                    <span style="display: block; font-size: 32px; font-weight: 900; color: #ec5a13;">1 500</span>
                    <span style="font-size: 14px; color: #717171; font-weight: 600;">COLLABORATEURS</span>
                </div>
            </div>

            <section style="margin-bottom: 60px;">
                <h2 style="font-size: 24px; font-weight: 800; color: #2b323e; margin-bottom: 20px;">Nos Valeurs et Notre Mission</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #ec5a13; margin-bottom: 10px;">Proximité & Accessibilité</h3>
                        <p>
                            Nous croyons que le numérique doit servir le lien social. Notre plateforme est conçue pour être accessible à tous, favorisant les échanges locaux et la rencontre.
                        </p>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #ec5a13; margin-bottom: 10px;">Confiance & Sécurité</h3>
                        <p>
                            La confiance ne se décrète pas, elle se construit. Nous investissons massivement dans l'IA et la modération humaine pour garantir des échanges sûrs et fiables.
                        </p>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #ec5a13; margin-bottom: 10px;">Engagement RSE</h3>
                        <p>
                            Promouvoir la seconde main et l'économie du partage, c'est agir concrètement pour la planète. Nous visons la neutralité carbone de nos activités d'ici 2030.
                        </p>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #ec5a13; margin-bottom: 10px;">Innovation Locale</h3>
                        <p>
                            Nos équipes techniques sont basées en France. Nous développons nos propres solutions pour garantir notre indépendance technologique et la souveraineté des données.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection