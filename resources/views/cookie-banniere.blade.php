<!-- resources/views/cookie-banner.blade.php -->
<!-- 
    Ce fichier gère le consentement des cookies conformément au RGPD.
    Il doit être inclus dans le layout principal (layout.blade.php).
-->

<style>
    /* --- STYLE CSS DU BANDEAU ET DE LA MODALE --- */
    :root {
        --primary-color: #ff6e14; /* Orange Leboncoin */
        --text-color: #1a1a1a;
        --bg-color: #ffffff;
        --border-radius: 8px;
    }

    /* 1. BANDEAU (En bas de page) */
    #cookie-banner {
        display: none; /* Masqué par défaut, géré par JS */
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: var(--bg-color);
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        padding: 20px;
        z-index: 9999;
        font-family: Arial, sans-serif;
        border-top: 4px solid var(--primary-color);
    }

    .banner-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .banner-text {
        flex: 1;
        font-size: 0.95rem;
        color: var(--text-color);
        line-height: 1.5;
    }

    .banner-title {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    .banner-buttons {
        display: flex;
        gap: 10px;
    }

    /* Styles des boutons */
    .btn-cookie {
        padding: 10px 20px;
        border-radius: var(--border-radius);
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.2s;
    }

    .btn-accept {
        background-color: var(--primary-color);
        color: white;
    }
    .btn-accept:hover { background-color: #e55e0c; }

    .btn-refuse {
        background-color: #f4f4f4;
        color: var(--text-color);
        border: 1px solid #ddd;
    }
    .btn-refuse:hover { background-color: #e0e0e0; }

    /* 2. MODALE DE PERSONNALISATION */
    #cookie-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .modal-box {
        background: white;
        width: 90%;
        max-width: 700px;
        border-radius: var(--border-radius);
        padding: 25px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .modal-header h3 { margin: 0; color: var(--text-color); }
    .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; }

    /* Liste des catégories */
    .cookie-category {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: var(--border-radius);
        background: #fafafa;
    }

    .cat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .cat-title { font-weight: bold; font-size: 1rem; }
    .cat-status { font-size: 0.8rem; font-weight: bold; color: var(--primary-color); text-transform: uppercase; }
    .cat-desc { font-size: 0.85rem; color: #555; }

    /* Toggle Switch (Boutons Oui/Non) */
    .toggle-group { display: flex; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; }
    .toggle-btn {
        padding: 5px 15px; border: none; cursor: pointer; background: white; font-size: 0.85rem;
    }
    .toggle-btn.active { background: var(--primary-color); color: white; }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
</style>

<!-- BANDEAU D'INFORMATION -->
<div id="cookie-banner">
    <div class="banner-content">
        <div class="banner-text">
            <span class="banner-title">🍪 Gestion de vos préférences</span>
            Nous utilisons des cookies pour assurer le bon fonctionnement du site. Avec votre accord, nous utilisons également des traceurs pour activer la <strong>Carte Interactive</strong>, notre <strong>Chatbot</strong> et mesurer l'audience. Vous pouvez tout accepter, tout refuser ou personnaliser vos choix.
        </div>
        <div class="banner-buttons">
            <button class="btn-cookie btn-refuse" onclick="CookieManager.denyAll()">Tout refuser</button>
            <button class="btn-cookie btn-refuse" onclick="CookieManager.openModal()">Personnaliser</button>
            <button class="btn-cookie btn-accept" onclick="CookieManager.acceptAll()">Tout accepter</button>
        </div>
    </div>
</div>

<!-- MODALE DE PERSONNALISATION -->
<div id="cookie-modal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Centre de préférences de confidentialité</h3>
            <button class="close-btn" onclick="CookieManager.closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size:0.9rem; color:#666; margin-bottom:20px;">
                Gérez vos consentements pour chaque catégorie. Les cookies techniques indispensables ne peuvent pas être désactivés.
            </p>

            <!-- 1. NÉCESSAIRES -->
            <div class="cookie-category">
                <div class="cat-header">
                    <span class="cat-title">🔒 Fonctionnement & Sécurité</span>
                    <span class="cat-status">OBLIGATOIRE</span>
                </div>
                <div class="cat-desc">
                    Indispensables pour l'authentification (Session Laravel), la sécurité des formulaires (CSRF) et les paiements sécurisés (Stripe).
                </div>
            </div>

            <!-- 2. FONCTIONNELS (Carte, Chat) -->
            <div class="cookie-category">
                <div class="cat-header">
                    <span class="cat-title">🛠️ Fonctionnalités & Outils</span>
                    <div class="toggle-group" id="toggle-func">
                        <button class="toggle-btn active" onclick="CookieManager.toggle('functional', false)">Non</button>
                        <button class="toggle-btn" onclick="CookieManager.toggle('functional', true)">Oui</button>
                    </div>
                </div>
                <div class="cat-desc">
                    Permet d'afficher la <strong>Carte Google Maps</strong> pour localiser les biens et d'activer le <strong>Chatbot</strong> d'assistance.
                </div>
            </div>

            <!-- 3. ANALYTICS (GA4) -->
            <div class="cookie-category">
                <div class="cat-header">
                    <span class="cat-title">📊 Mesure d'audience</span>
                    <div class="toggle-group" id="toggle-analytics">
                        <button class="toggle-btn active" onclick="CookieManager.toggle('analytics', false)">Non</button>
                        <button class="toggle-btn" onclick="CookieManager.toggle('analytics', true)">Oui</button>
                    </div>
                </div>
                <div class="cat-desc">
                    Nous permet d'analyser anonymement le trafic via <strong>Google Analytics 4</strong> (IP Anonymisée).
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn-cookie btn-refuse" onclick="CookieManager.denyAll()">Tout refuser</button>
            <button class="btn-cookie btn-accept" onclick="CookieManager.savePreferences()">Enregistrer mes choix</button>
        </div>
    </div>
</div>

<script>
    /* * GESTIONNAIRE DE CONSENTEMENT (Simule une CMP comme Tarteaucitron)
     * Stocke les choix dans le LocalStorage du navigateur.
     */
    const CookieManager = {
        // État par défaut : tout est refusé sauf le nécessaire
        consent: {
            functional: false, // Maps, Chatbot
            analytics: false   // GA4
        },

        init: function() {
            // Vérifie si l'utilisateur a déjà fait un choix
            const storedConsent = localStorage.getItem('sae_cookie_consent');
            
            if (!storedConsent) {
                // Pas de choix => Afficher le bandeau
                document.getElementById('cookie-banner').style.display = 'block';
            } else {
                // Choix existant => Charger les préférences et activer les scripts
                this.consent = JSON.parse(storedConsent);
                this.applyConsent(); 
            }
        },

        openModal: function() {
            document.getElementById('cookie-modal').style.display = 'flex';
            document.getElementById('cookie-banner').style.display = 'none';
            // Met à jour l'affichage des boutons selon l'état actuel
            this.updateToggleUI('functional', this.consent.functional);
            this.updateToggleUI('analytics', this.consent.analytics);
        },

        closeModal: function() {
            document.getElementById('cookie-modal').style.display = 'none';
            // Si pas encore sauvé, réafficher le bandeau
            if (!localStorage.getItem('sae_cookie_consent')) {
                document.getElementById('cookie-banner').style.display = 'block';
            }
        },

        toggle: function(category, value) {
            this.consent[category] = value;
            this.updateToggleUI(category, value);
        },

        updateToggleUI: function(category, value) {
            const group = document.getElementById('toggle-' + (category === 'functional' ? 'func' : 'analytics'));
            const btns = group.getElementsByClassName('toggle-btn');
            // Bouton 0 = Non, Bouton 1 = Oui
            btns[0].classList.toggle('active', !value);
            btns[1].classList.toggle('active', value);
        },

        acceptAll: function() {
            this.consent = { functional: true, analytics: true };
            this.savePreferences();
        },

        denyAll: function() {
            this.consent = { functional: false, analytics: false };
            this.savePreferences();
        },

        savePreferences: function() {
            // Sauvegarde dans le navigateur (valable pour ce navigateur)
            localStorage.setItem('sae_cookie_consent', JSON.stringify(this.consent));
            localStorage.setItem('sae_consent_date', new Date().toISOString());

            // Fermer les interfaces
            document.getElementById('cookie-modal').style.display = 'none';
            document.getElementById('cookie-banner').style.display = 'none';

            // Appliquer les changements (charger ou bloquer les scripts)
            this.applyConsent();
        },

        applyConsent: function() {
            console.log("Application des consentements :", this.consent);

            // 1. GESTION GOOGLE MAPS & CHATBOT
            if (this.consent.functional) {
                console.log("✅ Chargement de Google Maps et du Chatbot...");
                // Ici, vous pourriez décommenter le code pour charger les vrais scripts
                // loadScript('https://maps.googleapis.com/maps/api/js?key=VOTRE_API_KEY');
            } else {
                console.log("❌ Google Maps et Chatbot bloqués.");
            }

            // 2. GESTION GOOGLE ANALYTICS 4
            if (this.consent.analytics) {
                console.log("✅ Chargement de Google Analytics (Anonymisé)...");
                // Code GA4 standard ici
                /*
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'G-XXXXXXXX', { 'anonymize_ip': true });
                */
            } else {
                console.log("❌ Google Analytics bloqué.");
            }
        }
    };

    // Démarrage automatique au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        CookieManager.init();
    });
</script>