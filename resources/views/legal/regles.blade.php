@extends('layout')

@section('title', 'Règles de diffusion - Lemauvaiscoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 10px;">Règles Générales de Diffusion</h1>
        <p style="color: #717171; font-size: 14px; margin-bottom: 40px;">Référentiel de conformité - Version 2026.1</p>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 15px;">
            <p style="margin-bottom: 30px;">
                Afin de garantir une qualité de service optimale et de sécuriser les échanges, Lemauvaiscoin a établi des règles de diffusion strictes. Tout utilisateur (particulier ou professionnel) souhaitant publier une annonce sur notre plateforme s'engage à respecter l'intégralité des dispositions ci-dessous ainsi que nos Conditions Générales d'Utilisation. Le non-respect de ces règles entraînera le refus systématique de l'annonce, et en cas de récidive, la suspension ou la suppression définitive du compte utilisateur.
            </p>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 1. PRINCIPES GÉNÉRAUX ET BONNES MŒURS</h3>
                <p>
                    L'annonceur certifie être l'auteur unique et exclusif du contenu de l'annonce. Toute annonce doit être rédigée en français (obligation légale loi Toubon). Il est formellement interdit de publier des contenus incitant à la haine raciale, à la discrimination, à la violence, ou comportant des éléments pornographiques ou pédophiles. De même, les annonces politiques, religieuses ou sectaires sont prohibées.
                </p>
                <p style="margin-top: 15px;">
                    L'annonce doit porter sur un bien disponible. En cas de location, le bien doit être libre à la location pour les périodes indiquées. Il est interdit de proposer des biens contrefaits ou dont la vente est réglementée ou interdite en France (tabac, drogues, armes, médicaments, espèces protégées).
                </p>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 2. RÈGLES DE RÉDACTION</h3>
                <div style="margin-left: 20px;">
                    <strong style="display: block; color: #2b323e; margin-bottom: 5px;">2.1. Titre de l'annonce</strong>
                    <p style="margin-bottom: 15px;">
                        Le titre doit décrire précisément le bien. Il ne doit pas contenir de termes génériques tels que "A voir", "Affaire", "Urgent". Il est interdit d'utiliser des titres trompeurs ou sans lien avec le contenu.
                    </p>

                    <strong style="display: block; color: #2b323e; margin-bottom: 5px;">2.2. Description détaillée</strong>
                    <p style="margin-bottom: 15px;">
                        Le texte doit décrire la réalité du bien. Il est interdit d'insérer des mots-clés (tags) n'ayant aucun lien avec l'annonce dans le seul but de manipuler le moteur de recherche (spamdexing). Il est interdit de mentionner des coordonnées personnelles (téléphone, email) dans le corps du texte ; celles-ci doivent être renseignées dans les champs prévus à cet effet.
                    </p>

                    <strong style="display: block; color: #2b323e; margin-bottom: 5px;">2.3. Liens externes</strong>
                    <p>
                        Il est strictement interdit d'insérer des liens hypertextes dirigeant vers d'autres sites internet, notamment des sites concurrents, des sites de vente en ligne personnels ou des réseaux sociaux.
                    </p>
                </div>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 3. PHOTOGRAPHIES ET VISUELS</h3>
                <p>
                    Les photographies insérées doivent représenter le bien réel mis en location ou en vente. L'utilisation de photos issues de banques d'images, de catalogues constructeurs ou copiées sur d'autres sites internet sans autorisation est interdite (droit d'auteur).
                </p>
                <p style="margin-top: 10px;">
                    Les visuels ne doivent pas comporter de :
                    <br>- Logos d'entreprises (sauf pour la catégorie Emploi/Services Pro) ;
                    <br>- Coordonnées téléphoniques ou emails incrustés ;
                    <br>- Visages de mineurs reconnaissables ;
                    <br>- Représentations contraires à l'ordre public.
                </p>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 4. SPÉCIFICITÉS IMMOBILIER ET LOCATION SAISONNIÈRE</h3>
                <p>
                    Pour la catégorie "Vacances" et "Locations", l'annonceur doit impérativement respecter la législation en vigueur (Loi ALUR, Loi ELAN).
                </p>
                <ul style="list-style-type: disc; margin-left: 20px; margin-top: 15px;">
                    <li style="margin-bottom: 10px;"><strong>Numéro d'enregistrement :</strong> Dans les villes de plus de 200 000 habitants et les zones tendues, l'annonce doit obligatoirement mentionner le numéro de déclaration de meublé de tourisme à 13 chiffres délivré par la mairie.</li>
                    <li style="margin-bottom: 10px;"><strong>Sous-location :</strong> La sous-location est interdite pour les logements sociaux. Pour le parc privé, elle nécessite l'accord écrit du propriétaire et le loyer ne peut excéder le loyer initial.</li>
                    <li style="margin-bottom: 10px;"><strong>Diagnostic de Performance Énergétique (DPE) :</strong> Bien que facultatif pour le saisonnier, il est obligatoire pour toute location longue durée.</li>
                </ul>
            </section>

            <section>
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">ARTICLE 5. DOUBLONS ET MODÉRATION</h3>
                <p>
                    Il est interdit de publier plusieurs annonces pour un même bien. Pour remonter une annonce en tête de liste, l'utilisateur doit souscrire à une option payante et non supprimer puis recréer son annonce. Lemauvaiscoin utilise des algorithmes de détection automatique couplés à une modération humaine. Tout contournement de ces règles entraînera le blocage immédiat du compte.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection