class GestionnaireDate {
    constructor(elementInput) {
        this.input = elementInput;
        this.isDual = this.input.getAttribute('data-picker-dual') === 'true';
        
        this.dateActuelle = new Date();
        this.selectionDebut = null;
        this.selectionFin = null;

        
        this.maxDate = new Date();
        this.maxDate.setFullYear(this.maxDate.getFullYear() + 2);
        
        this.mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        this.jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

        this.init();
    }
    
    chargerValeursInitiales() {
        const targetStartId = this.input.getAttribute('data-target-start');
        const targetEndId = this.input.getAttribute('data-target-end');
        
        const hiddenStart = targetStartId ? document.getElementById(targetStartId) : null;
        const hiddenEnd = targetEndId ? document.getElementById(targetEndId) : null;

        const parseDateSQL = (str) => {
            if (!str) return null;
            const parts = str.split('-');
            return new Date(parts[0], parts[1] - 1, parts[2]);
        };

        if (hiddenStart && hiddenStart.value) {
            this.selectionDebut = parseDateSQL(hiddenStart.value);
            this.dateActuelle = new Date(this.selectionDebut);
        }

        if (this.isDual && hiddenEnd && hiddenEnd.value) this.selectionFin = parseDateSQL(hiddenEnd.value);
        if (this.selectionDebut)                         this.majInput(); 
        
    }
    
    init() {
        this.input.type = 'text';
        this.input.setAttribute('readonly', true);
        this.input.classList.add('date-custom-input');
        this.input.placeholder = 'Choisissez une date'

        const iconContainer = document.createElement('div');
        iconContainer.className = 'input-icon';
        iconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>`;
        
        this.input.parentNode.insertBefore(iconContainer, this.input.nextSibling);

        this.wrapper = document.createElement('div');
        this.wrapper.className = 'selecteur-date-wrapper';
        this.input.parentNode.appendChild(this.wrapper);

        this.input.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleCalendrier();
        });

        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target) && e.target !== this.input) this.fermerCalendrier();
        });

        this.chargerValeursInitiales();
        this.renderCalendrier(this.dateActuelle);
    }

    toggleCalendrier() {
        document.querySelectorAll('.selecteur-date-wrapper').forEach(el => {
            if (el !== this.wrapper) el.classList.remove('actif');
        });
        this.wrapper.classList.toggle('actif');
        
        this.cacherErreur();
    }

    fermerCalendrier() {
        this.wrapper.classList.remove('actif');
        this.cacherErreur();
    }

    
    afficherErreur(message) {
        const errorDiv = this.wrapper.querySelector('.cal-error-msg');
        if (errorDiv) {
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
        }
    }

    cacherErreur() {
        const errorDiv = this.wrapper.querySelector('.cal-error-msg');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    formatDate(date) {
        if (!date) return '';
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    formatSQL(date) {
        if (!date) return '';
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        return `${y}-${m}-${d}`;
    }

    majInput() {
        const targetStartId = this.input.getAttribute('data-target-start');
        const targetEndId = this.input.getAttribute('data-target-end');
        
        const hiddenStart = targetStartId ? document.getElementById(targetStartId) : null;
        const hiddenEnd = targetEndId ? document.getElementById(targetEndId) : null;

        if (this.isDual) {
            if (this.selectionDebut && this.selectionFin) {
                const d1 = this.selectionDebut < this.selectionFin ? this.selectionDebut : this.selectionFin;
                const d2 = this.selectionDebut < this.selectionFin ? this.selectionFin : this.selectionDebut;
                
                this.input.value = `${this.formatDate(d1)} - ${this.formatDate(d2)}`;
                
                if (hiddenStart) hiddenStart.value = this.formatSQL(d1);
                if (hiddenEnd) hiddenEnd.value = this.formatSQL(d2);

            } else if (this.selectionDebut) {
                this.input.value = `${this.formatDate(this.selectionDebut)} - ...`;
                if (hiddenStart) hiddenStart.value = '';
                if (hiddenEnd) hiddenEnd.value = '';
            } else {
                this.input.value = '';
                if (hiddenStart) hiddenStart.value = '';
                if (hiddenEnd) hiddenEnd.value = '';
            }
        } else {
            if (this.selectionDebut) {
                this.input.value = this.formatDate(this.selectionDebut);
                if (hiddenStart) hiddenStart.value = this.formatSQL(this.selectionDebut);
            }
        }
    }

    gererClickDate(annee, mois, jour) {
        const dateCliquee = new Date(annee, mois, jour);
        this.cacherErreur(); 

        if (this.isDual) {
            if (this.selectionDebut && this.selectionFin) {
                
                if (dateCliquee < this.selectionDebut) {
                    this.selectionDebut = dateCliquee;
                } 
                else if (dateCliquee > this.selectionFin) {
                    this.selectionFin = dateCliquee;
                } 
                else {
                    this.selectionDebut = dateCliquee;
                    this.selectionFin = null;
                }
            } 
            else if (!this.selectionDebut) {
                this.selectionDebut = dateCliquee;
            } 
            else {
                this.selectionFin = dateCliquee;
                if (this.selectionFin < this.selectionDebut) {
                    const temp = this.selectionDebut;
                    this.selectionDebut = this.selectionFin;
                    this.selectionFin = temp;
                }
            }
        } 
        else {
            this.selectionDebut = dateCliquee;
        }

        this.majInput();
        this.renderCalendrier(this.dateActuelle); 
    }

    estMemeJour(d1, d2) {
        if (!d1 || !d2) return false;
        return d1.getFullYear() === d2.getFullYear() &&
               d1.getMonth() === d2.getMonth() &&
               d1.getDate() === d2.getDate();
    }

    estDansPlage(dateTest) {
        if (!this.isDual || !this.selectionDebut || !this.selectionFin) return false;
        return dateTest > this.selectionDebut && dateTest < this.selectionFin;
    }

    renderCalendrier(dateRef) {
        this.wrapper.innerHTML = '';
        
        const annee = dateRef.getFullYear();
        const mois = dateRef.getMonth();
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const header = document.createElement('div');
        header.className = 'cal-header';
        
        const btnPrev = document.createElement('button');
        btnPrev.className = 'cal-btn-nav';
        btnPrev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>';
        
        const prevMonthEnd = new Date(annee, mois, 0);
        if (prevMonthEnd < today) {
             btnPrev.disabled = true;
             btnPrev.style.opacity = '0.3';
             btnPrev.style.cursor = 'not-allowed';
        }

        btnPrev.onclick = (e) => {
            e.stopPropagation();
            if (btnPrev.disabled) return;
            this.dateActuelle.setMonth(mois - 1);
            this.renderCalendrier(this.dateActuelle);
        };

        const titre = document.createElement('span');
        titre.className = 'cal-titre';
        titre.innerText = `${this.mois[mois]} ${annee}`;

        const btnNext = document.createElement('button');
        btnNext.className = 'cal-btn-nav';
        btnNext.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>';
        
        const firstDayNextMonth = new Date(annee, mois + 1, 1);
        if (firstDayNextMonth > this.maxDate) {
            btnNext.disabled = true;
            btnNext.style.opacity = '0.3';
            btnNext.style.cursor = 'not-allowed';
        }

        btnNext.onclick = (e) => {
            e.stopPropagation();
            if (btnNext.disabled) return;
            this.dateActuelle.setMonth(mois + 1);
            this.renderCalendrier(this.dateActuelle);
        };

        header.append(btnPrev, titre, btnNext);
        this.wrapper.appendChild(header);

        const grille = document.createElement('div');
        grille.className = 'cal-grid';

        this.jours.forEach(j => {
            const el = document.createElement('div');
            el.className = 'cal-jour-semaine';
            el.innerText = j;
            grille.appendChild(el);
        });

        const premierJourMois = new Date(annee, mois, 1).getDay();
        const decalage = premierJourMois === 0 ? 6 : premierJourMois - 1;

        for (let i = 0; i < decalage; i++) {
            const vide = document.createElement('div');
            vide.className = 'cal-cellule vide';
            grille.appendChild(vide);
        }

        const nbrJoursMois = new Date(annee, mois + 1, 0).getDate();

        for (let i = 1; i <= nbrJoursMois; i++) {
            const jourBtn = document.createElement('div');
            jourBtn.className = 'cal-cellule';
            jourBtn.innerText = i;
            
            const dateCourante = new Date(annee, mois, i);
            
            
            if (dateCourante < today || dateCourante > this.maxDate) {
                jourBtn.classList.add('disabled');
            } else {
                if (this.estMemeJour(dateCourante, this.selectionDebut)) {
                    jourBtn.classList.add('selectionne');
                    if (this.isDual && this.selectionFin) jourBtn.classList.add('debut-plage');
                }
                
                if (this.isDual && this.estMemeJour(dateCourante, this.selectionFin)) {
                    jourBtn.classList.add('selectionne', 'fin-plage');
                }

                if (this.estDansPlage(dateCourante)) {
                    jourBtn.classList.add('dans-plage');
                }

                jourBtn.onclick = (e) => {
                    e.stopPropagation();
                    this.gererClickDate(annee, mois, i);
                };
            }

            grille.appendChild(jourBtn);
        }

        this.wrapper.appendChild(grille);

        
        const errorMsg = document.createElement('div');
        errorMsg.className = 'cal-error-msg';
        errorMsg.style.color = '#e74c3c';
        errorMsg.style.fontSize = '0.85em';
        errorMsg.style.textAlign = 'center';
        errorMsg.style.padding = '5px';
        errorMsg.style.display = 'none'; 
        this.wrapper.appendChild(errorMsg);

        const footer = document.createElement('div');
        footer.className = 'cal-footer';
        
        footer.style.display = 'flex';
        footer.style.justifyContent = 'space-between';
        footer.style.gap = '5px';

        
        const btnAujourdhui = document.createElement('button');
        btnAujourdhui.type = 'button';
        btnAujourdhui.className = 'cal-btn';
        btnAujourdhui.innerText = "Auj.";
        btnAujourdhui.style.flex = '0 0 auto'; 
        
        btnAujourdhui.onclick = (e) => {
            e.stopPropagation();
            const now = new Date();
            this.dateActuelle = now;
            
            
            this.selectionDebut = now;
            this.selectionFin = null;
            
            this.cacherErreur();
            this.majInput();
            this.renderCalendrier(this.dateActuelle);
        };

        const btnEffacer = document.createElement('button');
        btnEffacer.type = 'button';
        btnEffacer.className = 'cal-btn cal-btn-effacer';
        btnEffacer.innerText = 'Effacer';
        
        btnEffacer.onclick = (e) => {
            e.stopPropagation();
            this.selectionDebut = null;
            this.selectionFin = null;
            this.cacherErreur();
            this.majInput();
            this.renderCalendrier(this.dateActuelle);
        };
        
        const btnValider = document.createElement('button');
        btnValider.type = 'button';
        btnValider.className = 'cal-btn cal-btn-valider';
        btnValider.innerText = 'Valider';
        
        btnValider.onclick = (e) => {
            e.stopPropagation();
            
            
            if (this.isDual) {
                if (!this.selectionDebut || !this.selectionFin) {
                    this.afficherErreur('Veuillez sélectionner 2 dates.');
                    return; 
                }
            } else {
                if (!this.selectionDebut) {
                    this.afficherErreur('Veuillez choisir une date.');
                    return;
                }
            }

            this.fermerCalendrier();
        };

        footer.append(btnAujourdhui, btnEffacer, btnValider);
        this.wrapper.appendChild(footer);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input[type="date"][data-picker-dual]');
    inputs.forEach(input => new GestionnaireDate(input));
});