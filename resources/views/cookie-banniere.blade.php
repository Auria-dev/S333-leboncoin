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
    z-index: 9999999;
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

    <div id="cookie-banner" style="display:none;">
        <div class="banner-header">
            Votre vie privée est notre priorité
        </div>
        <div class="banner-text">

            Nous utilisons des cookies strictement nécessaires au fonctionnement du site et à la sécurité.
            <br>
            Avec votre accord, des cookies de mesure d’audience peuvent être déposés afin d’améliorer nos services.
            <br>
            Conformément au rgpd, vous pouvez modifier votre choix à tout moment.
        </div>
        <div class="banner-actions">
            <button class="btn-cookie btn-link" onclick="CookieManager.openModal()">Paramétrer</button>
            <button class="btn-cookie btn-primary" onclick="CookieManager.denyAll()">Tout refuser</button>
            <button class="btn-cookie btn-primary" onclick="CookieManager.acceptAll()">Tout accepter</button>
        </div>
    </div>


    <div id="cookie-modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Paramètres de confidentialité</h3>
                <span class="close-icon" onclick="CookieManager.closeModal()">

                    ✕
                </span>
            </div>

            <div class="modal-body">
                <p style="margin-bottom: 20px; font-size: 0.9rem; color: #666;">

                    Les cookies techniques sont indispensables au fonctionnement du site et ne peuvent pas être désactivés.
                </p>

                <div class="cookie-card">
                    <div class="card-top">

                        <span class="card-title">Cookies techniques (essentiels)</span>
                        <span class="badge-required">Toujours actif</span>
                    </div>
                    <p class="card-desc">
                        Nécessaires pour la gestion de session, l’authentification, la sécurité et la protection contre les attaques.
                    </p>
                </div>

                <div class="cookie-card">
                    <div class="card-top">

                        <span class="card-title">Mesure d’audience</span>
                        <label class="switch">
                            <input type="checkbox" id="chk-analytics">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="card-desc">

                        Permet de mesurer l’audience du site de manière agrégée (google analytics 4, ip masquée).
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cookie btn-secondary" onclick="CookieManager.denyAll()">Tout refuser</button>

                <button class="btn-cookie btn-primary" onclick="CookieManager.savePreferences()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>

const CookieManager = {

    consent: {
        analytics: false
    },

    init: function () {
        const stored = localStorage.getItem('cookie_consent');
        if (!stored) {
            document.getElementById('cookie-banner').style.display = 'block';
            return;
        }

        this.consent = JSON.parse(stored);
        this.applyConsent();
    },

    openModal: function () {
        document.getElementById('cookie-banner').style.display = 'none';
        document.getElementById('cookie-modal').style.display = 'flex';
        document.getElementById('chk-analytics').checked = !!this.consent.analytics;
    },

    closeModal: function () {
        document.getElementById('cookie-modal').style.display = 'none';
        if (!localStorage.getItem('cookie_consent')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }
    },

    acceptAll: function () {
        this.consent.analytics = true;
        this.savePreferences();
    },

    denyAll: function () {
        this.consent.analytics = false;
        this.savePreferences();
    },

    savePreferences: function () {
        localStorage.setItem('cookie_consent', JSON.stringify(this.consent));
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        document.getElementById('cookie-banner').style.display = 'none';
        document.getElementById('cookie-modal').style.display = 'none';
        this.applyConsent();
    },

    applyConsent: function () {
        if (this.consent.analytics) {
            this.loadAnalytics();
        }
    },

    loadAnalytics: function () {
        if (window.gtag) return;
        /*
        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX';
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        window.gtag = gtag;

        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX', {
            anonymize_ip: true
        });
        */
    }
};

document.addEventListener('DOMContentLoaded', () => CookieManager.init());
</script>