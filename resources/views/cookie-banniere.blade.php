<style>
#cookie-container {
    font-family: var(--font-family);
    color: var(--text-main);
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
    background-color: var(--bg-card);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    padding: 24px;
    z-index: 9999;
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
    color: var(--text-muted);
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
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-primary {
    background-color: var(--primary);
    color: var(--text-inverse);
}
.btn-primary:hover { background-color: var(--primary-hover); }

.btn-secondary {
    background-color: var(--white-rgb);
    color: var(--text-main);
    border: 1px solid var(--border-default);
}
.btn-secondary:hover { background-color: var(--bg-subtle); border-color: var(--border-hover); }

.btn-link {
    background: none;
    color: var(--text-muted);
    text-decoration: underline;
    padding: 10px;
}
.btn-link:hover { color: var(--text-main); }

#cookie-modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: var(--overlay-bg);
    backdrop-filter: var(--backdrop-blur);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: var(--bg-card);
    width: 95%;
    max-width: 650px;
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid var(--border-default);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--white-rgb);
}
.modal-header h3 { margin: 0; font-size: 1.3rem; }
.close-icon { 
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    cursor: pointer;
    width: 1rem;
    height: 1rem;
    color: var(--text-muted);
}

.modal-body {
    padding: 25px;
    overflow-y: auto;
    background-color: var(--bg-card);
}

.cookie-card {
    background: var(--bg-card);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-sm);
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

.card-title { font-weight: 700; font-size: 1rem; color: var(--text-main); }
.card-desc { font-size: 0.85rem; color: var(--text-muted); margin: 0; }

.badge-required {
    font-size: 0.75rem;
    background: var(--bg-subtle);
    color: var(--text-muted);
    padding: 4px 8px;
    border-radius: var(--radius-sm);
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
    background-color: var(--border-default);
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
    background-color: var(--primary);
    transition: .4s;
    border-radius: 50%;
    box-shadow: var(--shadow-sm);
}

input:checked + .slider { background-color: var(--primary-light); }
input:checked + .slider:before { transform: translateX(20px); }

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid var(--border-default);
    background: var(--white-rgb);
    display: flex;
    justify-content: space-between;
    width: 100%;
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
                <span class="close-icon" onclick="CookieManager.closeModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </span>
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

    
</script>