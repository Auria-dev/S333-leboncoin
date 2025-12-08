<style>
    :root {
        --lbc-orange: #ec5a13;
        --lbc-orange-hover: #d34b0e;
        --lbc-black: #1a1a1a;
        --lbc-grey: #f4f6f7;
        --lbc-dark-grey: #757575;
        --lbc-border: #e0e0e0;
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.15);
        --radius: 12px;
        --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    #cookie-container {
        font-family: var(--font-family);
        color: var(--lbc-black);
        line-height: 1.5;
    }

    #cookie-banner {
        display: none;
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 1000px;
        background-color: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        padding: 24px;
        z-index: 9999;
        border-left: 6px solid var(--lbc-orange);
    }

    .banner-header {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .banner-text {
        font-size: 0.95rem;
        color: #4a4a4a;
        margin-bottom: 20px;
    }

    .banner-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }


    .btn-cookie {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: var(--lbc-orange);
        color: white;
    }
    .btn-primary:hover { background-color: var(--lbc-orange-hover); }

    .btn-secondary {
        background-color: white;
        color: var(--lbc-black);
        border: 1px solid var(--lbc-border);
    }
    .btn-secondary:hover { background-color: var(--lbc-grey); border-color: #ccc; }

    .btn-link {
        background: none;
        color: var(--lbc-dark-grey);
        text-decoration: underline;
        padding: 10px;
    }
    .btn-link:hover { color: var(--lbc-black); }

    #cookie-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(2px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        width: 95%;
        max-width: 650px;
        border-radius: var(--radius);
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        overflow: hidden;
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--lbc-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    .modal-header h3 { margin: 0; font-size: 1.3rem; }
    .close-icon { cursor: pointer; font-size: 1.5rem; color: var(--lbc-dark-grey); }

    .modal-body {
        padding: 25px;
        overflow-y: auto;
        background-color: #fafafa;
    }

    .cookie-card {
        background: white;
        border: 1px solid var(--lbc-border);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title { font-weight: 700; font-size: 1rem; color: var(--lbc-black); }
    .card-desc { font-size: 0.85rem; color: var(--lbc-dark-grey); margin: 0; }
    
    .badge-required {
        font-size: 0.75rem;
        background: #eee;
        color: #666;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        text-transform: uppercase;
    }


    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 26px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    input:checked + .slider { background-color: var(--lbc-orange); }
    input:checked + .slider:before { transform: translateX(20px); }

    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid var(--lbc-border);
        background: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    @media (max-width: 600px) {
        .banner-actions { flex-direction: column; }
        .modal-footer { flex-direction: column; gap: 10px; }
        .btn-cookie { width: 100%; }
    }
</style>

<div id="cookie-container">
    
    <div id="cookie-banner">
        <div class="banner-header">
            Votre vie privée est notre priorité
        </div>
        <div class="banner-text">
            Nous utilisons des cookies pour assurer le bon fonctionnement du site et la sécurité de votre compte. 
            Avec votre accord, nous utilisons également des traceurs pour vous proposer une carte interactive et un support via Chatbot.
            <br>Conformément au RGPD, vous pouvez retirer votre consentement à tout moment.
        </div>
        <div class="banner-actions">
            <button class="btn-cookie btn-link" onclick="CookieManager.openModal()">Paramétrer les cookies</button>
            <button class="btn-cookie btn-secondary" onclick="CookieManager.denyAll()">Continuer sans accepter</button>
            <button class="btn-cookie btn-primary" onclick="CookieManager.acceptAll()">Tout accepter</button>
        </div>
    </div>

    <div id="cookie-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Paramètres de confidentialité</h3>
                <span class="close-icon" onclick="CookieManager.closeModal()">&times;</span>
            </div>

            <div class="modal-body">
                <p style="margin-bottom: 20px; font-size: 0.9rem; color: #666;">
                    Gérez vos préférences ci-dessous. Les cookies techniques sont nécessaires au fonctionnement du site et ne peuvent être désactivés.
                </p>

                <div class="cookie-card">
                    <div class="card-top">
                        <span class="card-title">Cookies techniques (Essentiels)</span>
                        <span class="badge-required">Toujours actif</span>
                    </div>
                    <p class="card-desc">
                        Nécessaires pour l'authentification, la sécurité (protection CSRF) et le paiement. Sans eux, le site ne peut pas fonctionner.
                    </p>
                </div>

                <div class="cookie-card">
                    <div class="card-top">
                        <span class="card-title">Fonctionnalités avancées</span>
                        <label class="switch">
                            <input type="checkbox" id="chk-functional" onchange="CookieManager.updateState()">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="card-desc">
                        Autorise l'affichage de la <strong>Carte Interactive (Google Maps)</strong> pour localiser les biens et active notre <strong>Chatbot</strong> d'assistance technique.
                    </p>
                </div>

                <div class="cookie-card">
                    <div class="card-top">
                        <span class="card-title">Mesure d'audience</span>
                        <label class="switch">
                            <input type="checkbox" id="chk-analytics" onchange="CookieManager.updateState()">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="card-desc">
                        Nous permet d'analyser le trafic de manière anonyme (Google Analytics 4 avec masquage IP) pour améliorer nos services.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cookie btn-secondary" onclick="CookieManager.denyAll()">Tout refuser</button>
                <button class="btn-cookie btn-primary" onclick="CookieManager.savePreferences()">Enregistrer mes choix</button>
            </div>
        </div>
    </div>
</div>

<script>
    const CookieManager = {
        consent: { functional: false, analytics: false },

        init: function() {
            const stored = localStorage.getItem('sae_cookie_consent');
            if (!stored) {
                document.getElementById('cookie-banner').style.display = 'block';
            } else {
                this.consent = JSON.parse(stored);
                this.applyConsent();
            }
        },

        openModal: function() {
            document.getElementById('cookie-banner').style.display = 'none';
            document.getElementById('cookie-modal').style.display = 'flex';
            document.getElementById('chk-functional').checked = this.consent.functional;
            document.getElementById('chk-analytics').checked = this.consent.analytics;
        },

        closeModal: function() {
            document.getElementById('cookie-modal').style.display = 'none';
            if (!localStorage.getItem('sae_cookie_consent')) {
                document.getElementById('cookie-banner').style.display = 'block';
            }
        },

        updateState: function() {
            this.consent.functional = document.getElementById('chk-functional').checked;
            this.consent.analytics = document.getElementById('chk-analytics').checked;
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
            localStorage.setItem('sae_cookie_consent', JSON.stringify(this.consent));
            localStorage.setItem('sae_consent_date', new Date().toISOString());
            document.getElementById('cookie-modal').style.display = 'none';
            document.getElementById('cookie-banner').style.display = 'none';
            this.applyConsent();
        },

        applyConsent: function() {
            console.log("Application des consentements RGPD :", this.consent);
            

            if (this.consent.functional) {
                console.log("--> Chargement Modules Fonctionnels");
            }
            
            if (this.consent.analytics) {
                console.log("--> Chargement Analytics");
            }
        }
    };

    document.addEventListener('DOMContentLoaded', () => CookieManager.init());

    init: function() {

    localStorage.removeItem('sae_cookie_consent'); 

    const stored = localStorage.getItem('sae_cookie_consent');
    if (!stored) {
        document.getElementById('cookie-banner').style.display = 'block';
    } else {
        this.consent = JSON.parse(stored);
        this.applyConsent();
    }
},
</script>