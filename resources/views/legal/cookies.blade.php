@extends('layout')

@section('title', 'Gestion des Cookies - Leboncoin')

@section('content')
<div class="legal-wrapper">
    <div class="legal-card">
        <h1 style="font-size: 32px; font-weight: 800; color: #2b323e; margin-bottom: 10px;">Politique d'Utilisation des Cookies</h1>
        <p style="color: #717171; font-size: 14px; margin-bottom: 40px;">Conformité ePrivacy & Délibération CNIL n°2020-091</p>

        <div style="line-height: 1.8; color: #4a4a4a; text-align: justify; font-size: 15px;">
            <p style="margin-bottom: 30px;">
                Lors de votre navigation sur notre plateforme, des informations relatives à la navigation de votre terminal (ordinateur, tablette, smartphone, etc.) sont susceptibles d'être enregistrées dans des fichiers texte appelés "Cookies", installés sur votre navigateur. Cette page vous permet de comprendre ce qu'est un Cookie, à quoi il sert, et comment vous pouvez gérer vos préférences à tout moment.
            </p>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; margin-bottom: 15px;">1. Qu'est-ce qu'un cookie ?</h3>
                <p>
                    Un cookie est un petit fichier texte, image ou logiciel, qui est placé et stocké sur votre terminal lors de la consultation d'un site web. Il permet à son émetteur d'identifier le terminal dans lequel il est enregistré, pendant la durée de validité ou d'enregistrement du cookie. Les cookies ne permettent pas de vous identifier nominativement en tant qu'individu, mais identifient seulement votre appareil via un identifiant unique aléatoire.
                </p>
            </section>

            <section style="margin-bottom: 40px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; margin-bottom: 15px;">2. Les différentes catégories de cookies utilisées</h3>
                
                <div style="margin-bottom: 30px;">
                    <strong style="display: block; color: #2b323e; font-size: 16px; margin-bottom: 5px;">A. Cookies Techniques (Strictement Nécessaires)</strong>
                    <p style="font-size: 14px; margin-left: 0;">
                        Ces cookies sont indispensables à la navigation sur notre site. Ils vous permettent d'utiliser les principales fonctionnalités (accès à votre compte, maintien de la session active, sécurisation des paiements via token CSRF). Sans ces cookies, le site ne peut pas fonctionner normalement. Ils ne sont pas soumis à votre consentement (Article 82 de la loi Informatique et Libertés).
                    </p>
                </div>

                <div style="margin-bottom: 30px;">
                    <strong style="display: block; color: #2b323e; font-size: 16px; margin-bottom: 5px;">B. Cookies de Mesure d'Audience (Analytics)</strong>
                    <p style="font-size: 14px; margin-left: 0;">
                        Ces cookies nous permettent de connaître l'utilisation et les performances de notre site, d'établir des statistiques, des volumes de fréquentation et d'utilisation des divers éléments (rubriques visitées, parcours). Nous utilisons <strong>Google Analytics 4</strong> avec anonymisation des IP. Ces données nous permettent d'améliorer l'intérêt et l'ergonomie de nos services.
                        <br><em>Durée de conservation : 13 mois.</em>
                    </p>
                </div>

                <div style="margin-bottom: 30px;">
                    <strong style="display: block; color: #2b323e; font-size: 16px; margin-bottom: 5px;">C. Cookies de Fonctionnalité Avancée</strong>
                    <p style="font-size: 14px; margin-left: 0;">
                        Ces cookies permettent d'enrichir votre expérience utilisateur en activant des services tiers interactifs tels que Google Maps pour la localisation des biens ou notre module de gestion de consentement.
                    </p>
                </div>
            </section>

            <section style="background-color: #f7fafc; border: 1px solid #edf2f7; padding: 30px; border-radius: 12px; text-align: center;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2b323e; margin-top: 0; margin-bottom: 15px;">Gérer vos préférences</h3>
                <p style="margin-bottom: 25px; color: #4a5568;">
                    Vous pouvez à tout moment modifier vos choix en matière de cookies via notre module de gestion dédié.
                </p>
                <button id="openCookieModalBtn" style="background-color: #ec5a13; color: white; border: none; padding: 12px 30px; font-size: 16px; font-weight: 600; border-radius: 6px; cursor: pointer; transition: background 0.3s; box-shadow: 0 4px 6px rgba(236, 90, 19, 0.2);">
                    Paramétrer mes cookies
                </button>
            </section>
        </div>
    </div>
</div>

<div id="cookieModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    
    <div style="background-color: white; width: 90%; max-width: 700px; max-height: 90vh; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); display: flex; flex-direction: column; overflow: hidden; font-family: 'Inter', sans-serif;">
        
        <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background-color: #fff;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #2b323e;">Centre de Préférences de la Confidentialité</h2>
            <button id="closeCookieModalBtn" style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px; overflow-y: auto; flex: 1;">
            <p style="font-size: 14px; color: #4b5563; margin-bottom: 24px; line-height: 1.6;">
                Lorsque vous visitez notre site web, il peut stocker ou récupérer des informations sur votre navigateur, principalement sous la forme de cookies. Ces informations peuvent concerner vos préférences ou votre appareil et sont principalement utilisées pour faire fonctionner le site comme vous le souhaitez. Les informations ne vous identifient généralement pas directement, mais elles peuvent vous offrir une expérience web plus personnalisée.
            </p>

            <div style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 16px; overflow: hidden;">
                <div style="padding: 16px; background-color: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="display: block; font-size: 15px; color: #1f2937;">Cookies Strictement Nécessaires</strong>
                        <span style="font-size: 12px; color: #6b7280;">Toujours actif</span>
                    </div>
                    <span style="font-size: 12px; font-weight: 700; color: #059669; background-color: #d1fae5; padding: 4px 8px; border-radius: 4px;">Toujours Actif</span>
                </div>
                <div style="padding: 16px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #4b5563; line-height: 1.5;">
                    Ces cookies sont nécessaires au fonctionnement du site Web et ne peuvent pas être désactivés dans nos systèmes. Ils sont généralement établis en tant que réponse à des actions que vous avez effectuées et qui constituent une demande de services, telles que la définition de vos préférences en matière de confidentialité, la connexion ou le remplissage de formulaires.
                </div>
            </div>

            <div style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 16px; overflow: hidden;">
                <div style="padding: 16px; background-color: #fff; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="display: block; font-size: 15px; color: #1f2937;">Cookies de Performance & Analytics</strong>
                        <span style="font-size: 12px; color: #6b7280;">Google Analytics 4</span>
                    </div>
                    <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ec5a13; transition: .4s; border-radius: 34px;"></span>
                        <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; transform: translateX(20px);"></span>
                    </label>
                </div>
                <div style="padding: 16px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #4b5563; line-height: 1.5;">
                    Ces cookies nous permettent de déterminer le nombre de visites et les sources du trafic, afin de mesurer et d’améliorer les performances de notre site Web. Ils nous aident également à identifier les pages les plus / moins visitées et d’évaluer comment les visiteurs naviguent sur le site Web. Toutes les informations collectées par ces cookies sont agrégées et donc anonymisées.
                </div>
            </div>

            <div style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 16px; overflow: hidden;">
                <div style="padding: 16px; background-color: #fff; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="display: block; font-size: 15px; color: #1f2937;">Cookies de Fonctionnalité</strong>
                        <span style="font-size: 12px; color: #6b7280;">Google Maps, Chatbot</span>
                    </div>
                    <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                        <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                        <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
                        <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </label>
                </div>
                <div style="padding: 16px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #4b5563; line-height: 1.5;">
                    Ces cookies permettent d’améliorer et de personnaliser les fonctionnalités du site Web. Ils peuvent être activés par nos équipes, ou par des tiers (comme Google Maps pour l'affichage des cartes) dont les services sont utilisés sur les pages de notre site Web. Si vous n'acceptez pas ces cookies, une partie ou la totalité de ces services risquent de ne pas fonctionner correctement.
                </div>
            </div>

        </div>

        <div style="padding: 24px; border-top: 1px solid #e5e7eb; background-color: #f9fafb; display: flex; justify-content: flex-end; gap: 12px;">
            <button id="rejectAllBtn" style="padding: 10px 20px; border: 1px solid #d1d5db; background-color: white; color: #374151; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 14px;">Tout Refuser</button>
            <button id="confirmChoicesBtn" style="padding: 10px 20px; border: none; background-color: #ec5a13; color: white; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Confirmer mes choix</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('cookieModal');
        const openBtn = document.getElementById('openCookieModalBtn');
        const closeBtn = document.getElementById('closeCookieModalBtn');
        const confirmBtn = document.getElementById('confirmChoicesBtn');
        const rejectBtn = document.getElementById('rejectAllBtn');
        const checkboxes = modal.querySelectorAll('input[type="checkbox"]');

        openBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; 
        });
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; 
        }

        closeBtn.addEventListener('click', closeModal);
        confirmBtn.addEventListener('click', closeModal); 
        rejectBtn.addEventListener('click', closeModal); 
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
        });
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const slider = this.nextElementSibling;
                const knob = slider.nextElementSibling;
                
                if(this.checked) {
                    slider.style.backgroundColor = '#ec5a13';
                    knob.style.transform = 'translateX(20px)';
                } else {
                    slider.style.backgroundColor = '#ccc';
                    knob.style.transform = 'translateX(0)';
                }
            });
        });
    });
</script>
@endsection