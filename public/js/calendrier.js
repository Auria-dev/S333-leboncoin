class GestionnaireDate {
    constructor(elementInput) {
        this.input = elementInput;
        this.isDual = this.input.getAttribute('data-picker-dual') === 'true';
        
        this.dateActuelle = new Date();
        this.selectionDebut = null;
        this.selectionFin = null;
        
        this.mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        this.jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

        this.init();
    }

    init() {
        this.input.type = 'text';
        this.input.setAttribute('readonly', true);
        this.input.classList.add('date-custom-input');

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
            if (!this.wrapper.contains(e.target) && e.target !== this.input) {
                this.fermerCalendrier();
            }
        });

        this.renderCalendrier(this.dateActuelle);
    }

    toggleCalendrier() {
        document.querySelectorAll('.selecteur-date-wrapper').forEach(el => {
            if (el !== this.wrapper) el.classList.remove('actif');
        });
        this.wrapper.classList.toggle('actif');
    }

    fermerCalendrier() {
        this.wrapper.classList.remove('actif');
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

        if (this.isDual) {
            if (!this.selectionDebut || (this.selectionDebut && this.selectionFin)) {
                this.selectionDebut = dateCliquee;
                this.selectionFin = null;
            } else {
                this.selectionFin = dateCliquee;
                if (this.selectionFin < this.selectionDebut) {
                    const temp = this.selectionDebut;
                    this.selectionDebut = this.selectionFin;
                    this.selectionFin = temp;
                }
            }
        } else {
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

        const header = document.createElement('div');
        header.className = 'cal-header';
        
        const btnPrev = document.createElement('button');
        btnPrev.className = 'cal-btn-nav';
        btnPrev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>';
        btnPrev.onclick = (e) => {
            e.stopPropagation();
            this.dateActuelle.setMonth(mois - 1);
            this.renderCalendrier(this.dateActuelle);
        };

        const titre = document.createElement('span');
        titre.className = 'cal-titre';
        titre.innerText = `${this.mois[mois]} ${annee}`;

        const btnNext = document.createElement('button');
        btnNext.className = 'cal-btn-nav';
        btnNext.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>';
        btnNext.onclick = (e) => {
            e.stopPropagation();
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

            grille.appendChild(jourBtn);
        }

        this.wrapper.appendChild(grille);

        const footer = document.createElement('div');
        footer.className = 'cal-footer';
        
        const btnValider = document.createElement('button');
        btnValider.className = 'cal-btn-valider';
        btnValider.innerText = 'Valider';
        
        btnValider.onclick = (e) => {
            e.stopPropagation();
            this.fermerCalendrier();
        };

        footer.appendChild(btnValider);
        this.wrapper.appendChild(footer);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input[type="date"][data-picker-dual]');
    inputs.forEach(input => new GestionnaireDate(input));
});




/*
<div class="input-groupe">
    <label for="birth">Date de naissance</label>
    <input 
        type="date" 
        id="birth"
        data-picker-dual="false"
        data-target-start="birth_date_sql"
    />
    <input type="hidden" name="birth_date" id="birth_date_sql">
</div>
*/

//  OU 

/*
<div class="input-groupe">
    <label for="vyg">Dates voyage</label>
    
    <input 
        type="date" 
        id="vyg"
        data-picker-dual="true"
        data-target-start="datedebut" 
        data-target-end="datefin"
    />

    <input type="hidden" name="datedebut" id="datedebut">
    <input type="hidden" name="datefin" id="datefin">
</div>
*/