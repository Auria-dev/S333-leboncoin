@extends('layout')

@section('title', 'Nos Engagements RSE - Leboncoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 10px;">Nos Engagements RSE</h1>
        <p style="color: #717171; font-size: 14px; margin-bottom: 40px;">Responsabilité Sociétale et Environnementale</p>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 15px;">
            <p style="margin-bottom: 40px; border-left: 4px solid #ec5a13; padding-left: 20px; font-weight: 500;">
                Chez Leboncoin, nous sommes convaincus que le numérique doit être un levier de transition écologique et solidaire. Notre stratégie RSE (Responsabilité Sociétale des Entreprises) est au cœur de notre modèle d'affaires, alignée sur les Objectifs de Développement Durable (ODD) de l'ONU.
            </p>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #2b323e; margin-bottom: 20px;">PILIER 1 : L'ÉCONOMIE CIRCULAIRE ET L'ENVIRONNEMENT</h2>
                <p style="margin-bottom: 15px;">
                    Notre mission première est de prolonger la durée de vie des produits. En favorisant la seconde main plutôt que l'achat neuf, nos utilisateurs évitent chaque année la production de millions de tonnes de CO2 et l'extraction de ressources naturelles limitées.
                </p>
                <div style="background: #f8fafc; padding: 25px; border-radius: 8px; margin-top: 20px;">
                    <strong style="display: block; color: #2b323e; margin-bottom: 10px;">Sobriété Numérique de nos Infrastructures</strong>
                    <p>
                        Le numérique représente 4% des émissions mondiales de gaz à effet de serre. Nous agissons concrètement pour réduire l'empreinte de notre plateforme :
                    </p>
                    <ul style="list-style-type: square; margin-left: 20px; margin-top: 10px;">
                        <li><strong>Éco-conception logicielle :</strong> Nos ingénieurs optimisent le code pour minimiser les requêtes serveurs et la consommation de données mobiles.</li>
                        <li><strong>Hébergement Vert :</strong> Nos serveurs, hébergés chez OVHcloud en France, utilisent une technologie de refroidissement par eau (Watercooling) permettant de supprimer la climatisation énergivore des datacenters.</li>
                        <li><strong>Politique Matériel :</strong> Nous avons allongé la durée d'amortissement de nos équipements internes (ordinateurs, serveurs) de 3 à 5 ans pour lutter contre l'obsolescence programmée.</li>
                    </ul>
                </div>
            </section>

            <section style="margin-bottom: 50px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #2b323e; margin-bottom: 20px;">PILIER 2 : INCLUSION SOCIALE ET DIVERSITÉ</h2>
                <p>
                    Leboncoin s'engage à être un employeur exemplaire et une plateforme ouverte à tous, sans distinction d'origine, de genre ou de handicap.
                </p>
                <div style="margin-top: 20px;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #2b323e;">Égalité Femmes-Hommes</h4>
                    <p style="margin-bottom: 15px;">
                        Nous menons une politique volontariste pour la mixité dans les métiers de la Tech. Notre Index de l'Égalité Professionnelle s'élève à <strong>94/100</strong> pour l'année 2025. Nous garantissons une stricte égalité salariale à poste et compétences égaux.
                    </p>

                    <h4 style="font-size: 16px; font-weight: 700; color: #2b323e;">Accessibilité Numérique (RGAA)</h4>
                    <p>
                        Nous travaillons continuellement à la mise en conformité de notre site avec le Référentiel Général d'Amélioration de l'Accessibilité. Notre objectif est de rendre 100% de nos parcours critiques utilisables par les personnes en situation de handicap (navigation au clavier, compatibilité lecteurs d'écran).
                    </p>
                </div>
            </section>

            <section>
                <h2 style="font-size: 22px; font-weight: 800; color: #2b323e; margin-bottom: 20px;">PILIER 3 : DYNAMISME DES TERRITOIRES</h2>
                <p>
                    Contrairement aux géants du web dématérialisés, Leboncoin est ancré dans les territoires. Nous favorisons le commerce de proximité et les échanges en circuit court.
                </p>
                <p style="margin-top: 15px;">
                    En matière de tourisme, notre offre de location saisonnière permet de répartir les flux touristiques sur l'ensemble du territoire français, y compris dans les zones rurales souvent délaissées par l'hôtellerie traditionnelle, contribuant ainsi directement à l'économie locale des communes.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection