@extends('layout')

@section('title', 'Votre demande de réservation')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

@php
    $start = \Carbon\Carbon::parse($date_debut_resa);
    $end = \Carbon\Carbon::parse($date_fin_resa);
    $nb_nuits = $start->diffInDays($end);
    $montant_location = $nb_nuits * $annonce->prix_nuit;
    $frais_service = ceil($montant_location * 0.1);
    $taxe_sejour = 5.3;
    $total = $montant_location + $frais_service + $taxe_sejour;
    
    // Limits
    $max_capacity = $annonce->nb_personnes_max;
    $max_bebe = $annonce->nb_bebe_max;
    $max_animaux = $annonce->nb_animaux_max;
@endphp

<div class="reservation-container">
    <div class="reservation-form-column">
    <div class="reservation-card">
        <p class="reservation-dates">
            <strong>Vos dates de séjour :</strong><br>
            du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}
        </p>

        <form action="{{ url('/confirmer_reservation') }}" method="POST" id="formulaire_reservation"
            x-data="bookingForm()"
            @submit.prevent="submitAll">

            <h2 class="reservation-subtitle">Nombre de voyageurs</h2>
            
            @csrf
            <input type="hidden" name="date_debut_resa" value="{{ $date_debut_resa }}">
            <input type="hidden" name="date_fin_resa" value="{{ $date_fin_resa }}">
            
            <input type="hidden" name="nb_adultes" x-model="guests.adultes">
            <input type="hidden" name="nb_enfants" x-model="guests.enfants">
            <input type="hidden" name="nb_bebes" x-model="guests.bebes">
            <input type="hidden" name="nb_animaux" x-model="guests.animaux">

            <input type="hidden" name="idannonce" value="{{ $annonce->idannonce }}">
            <input type="hidden" name="idutilisateur" value="{{ auth()->user()->idutilisateur }}">
            <input type="hidden" name="montant_location" value="{{ $montant_location }}">
            <input type="hidden" name="frais_service" value="{{ $frais_service }}">
            <input type="hidden" name="taxe_sejour" value="{{ $taxe_sejour }}">
            <input type="hidden" name="total" value="{{ $total }}">

            <div class="picker-group">
                <div class="picker-label-container">
                    <span class="picker-label">Adultes</span>
                    <span class="picker-subtext">18 ans et plus</span>
                </div>
                <div class="picker-controls">
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('adultes', -1)" 
                        :disabled="guests.adultes <= 1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </button>
                    
                    <span class="picker-value" x-text="guests.adultes"></span>
                    
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('adultes', 1)"
                        :disabled="(guests.adultes + guests.enfants) >= limits.maxPersons">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
            </div>

            <div class="picker-group">
                <div class="picker-label-container">
                    <span class="picker-label">Enfants</span>
                    <span class="picker-subtext">De 3 à 17 ans</span>
                </div>
                <div class="picker-controls">
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('enfants', -1)"
                        :disabled="guests.enfants <= 0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </button>
                    
                    <span class="picker-value" x-text="guests.enfants"></span>
                    
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('enfants', 1)"
                        :disabled="(guests.adultes + guests.enfants) >= limits.maxPersons">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
            </div>

            <div class="picker-group" style="{{ $max_bebe <= 0 ? 'opacity:0.5; pointer-events:none;' : '' }}">
                <div class="picker-label-container">
                    <span class="picker-label">Bébés</span>
                    <span class="picker-subtext">Moins de 3 ans</span>
                </div>
                <div class="picker-controls">
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('bebes', -1)"
                        :disabled="guests.bebes <= 0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </button>
                    
                    <span class="picker-value" x-text="guests.bebes"></span>
                    
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('bebes', 1)"
                        :disabled="guests.bebes >= limits.maxBebes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
            </div>

            <div class="picker-group" style="{{ $max_animaux <= 0 ? 'opacity:0.5; pointer-events:none;' : '' }}">
                <div class="picker-label-container">
                    <span class="picker-label">Animaux</span>
                    <span class="picker-subtext">Cela inclut les animaux d'assistance</span>
                </div>
                <div class="picker-controls">
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('animaux', -1)"
                        :disabled="guests.animaux <= 0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </button>
                    
                    <span class="picker-value" x-text="guests.animaux"></span>
                    
                    <button type="button" class="picker-btn" 
                        @click="updateCounter('animaux', 1)"
                        :disabled="guests.animaux >= limits.maxAnimaux">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
            </div>

            <h2 class="reservation-subtitle mt-large">Vos informations</h2>

            <div class="reservation-input-group">
                <label class="reservation-label">Prénom*</label>
                <input type="text" name="prenom" required 
                    class="reservation-input" 
                    :class="{'is-invalid': errors.prenom}"
                    x-model="prenom" @input="errors.prenom = false">
                <div class="text-error" x-show="errors.prenom" style="color:red; font-size:12px;">Requis</div>
            </div>

            <div class="reservation-input-group mt-md">
                <label class="reservation-label">Nom*</label>
                <input type="text" name="nom" required 
                    class="reservation-input" 
                    :class="{'is-invalid': errors.nom}"
                    x-model="nom" @input="errors.nom = false">
                <div class="text-error" x-show="errors.nom" style="color:red; font-size:12px;">Requis</div>
            </div>

            <div class="reservation-input-group mt-md">
                <label class="reservation-label">Numéro de téléphone*</label>
                <input type="tel" name="telephone" required maxlength="10"
                    class="reservation-input" 
                    placeholder="06 12 34 56 78"
                    x-model="telephone"
                    @input="validatePhone($el.value)"
                    :class="{'is-invalid': errors.telephone}">
                <div class="text-error" x-show="errors.telephone" x-text="phoneErrorMessage" style="color:red; font-size:12px;"></div>
            </div>

            <div class="payment-section mt-large">
                <h2 class="reservation-subtitle">Paiement</h2>

                @if (auth()->user()->cartesBancaires && count(auth()->user()->cartesBancaires) > 0)
                <div class="mb-md">
                    <p class="reservation-label mb-sm">Utiliser une carte enregistrée :</p>
                    
                    @foreach (auth()->user()->cartesBancaires as $carte)
                    <div class="payment-card-option" 
                        :class="selectedCardId == '{{ $carte->idcartebancaire }}' ? 'selected' : ''">
                        
                        <label class="payment-card-label" for="carte_{{ $carte->idcartebancaire }}">
                            <input type="radio" 
                                name="carte_id" 
                                value="{{ $carte->idcartebancaire }}" 
                                id="carte_{{ $carte->idcartebancaire }}" 
                                class="payment-radio-input"
                                x-model="selectedCardId"
                                @click="clearSavedCvv()">
                            
                            <div class="payment-custom-radio"></div>

                            <div class="card-text-group">
                                <span class="card-text-main">
                                    **** **** **** {{ substr(decrypt($carte->numcarte), -4) }}
                                </span>
                                <span class="card-text-sub">
                                    Expire le {{ $carte->dateexpiration }}
                                </span>
                            </div>
                        </label>

                        <div x-show="selectedCardId == '{{ $carte->idcartebancaire }}'" class="payment-cvv-reveal" x-transition>
                            <label class="reservation-label text-sm">Confirmer le CVV*</label>
                            <div class="input-wrapper" style="width: 100%;">
                                <input type="text" 
                                    name="cvv_verify_{{ $carte->idcartebancaire }}" 
                                    class="reservation-input input-cvv-small" 
                                    placeholder="123" 
                                    maxlength="4" 
                                    x-model="savedCardCvv"
                                    :class="{'is-invalid': errors.savedCvv}">
                            </div>
                            <p class="error-msg" x-show="errors.savedCvv" x-text="errors.savedCvvMsg"></p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="payment-card-option" 
                    :class="selectedCardId == 'new' ? 'selected' : ''">
                    
                    <label class="payment-card-label" for="carte_new">
                        <input type="radio" 
                            name="carte_id" 
                            value="new" 
                            id="carte_new" 
                            class="payment-radio-input"
                            x-model="selectedCardId" 
                            @click="clearSavedCvv()">
                        
                        <div class="payment-custom-radio"></div>
                        
                        <div class="card-text-group">
                            <span class="card-text-main">Ajouter une nouvelle carte</span>
                            <span class="card-text-sub">Carte de crédit ou débit</span>
                        </div>
                    </label>

                    <div x-show="selectedCardId === 'new'" x-transition class="new-card-container">
                        <div class="reservation-input-group">
                            <label class="reservation-label">Titulaire*</label>
                            <input type="text" 
                                name="titulairecarte" 
                                class="reservation-input"
                                x-model="newCard.name" 
                                :class="{'is-invalid': errors.newName}">
                            <p class="error-msg" x-show="errors.newName">Requis</p>
                        </div>

                        <div class="reservation-input-group mt-md">
                            <label class="reservation-label">Numéro de carte*</label>
                            <input type="text" 
                                name="numcarte" 
                                class="reservation-input"
                                placeholder="0000 0000 0000 0000" 
                                maxlength="19"
                                x-model="newCard.number" 
                                @input="formatCardNumber()"
                                :class="{'is-invalid': errors.newNumber}">
                            <p class="error-msg" x-show="errors.newNumber">16 chiffres requis</p>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="reservation-label">Exp (MM/AA)*</label>
                                <input type="text" 
                                    name="dateexpiration" 
                                    class="reservation-input"
                                    placeholder="MM/AA" 
                                    maxlength="5"
                                    x-model="newCard.expiry" 
                                    @input="formatExpiry()"
                                    :class="{'is-invalid': errors.newExpiry}">
                                <p class="error-msg" x-show="errors.newExpiry">Date invalide</p>
                            </div>
                            <div class="form-col">
                                <label class="reservation-label">CVV*</label>
                                <input type="text" 
                                    name="cvv" 
                                    class="reservation-input"
                                    placeholder="123" 
                                    maxlength="4"
                                    x-model="newCard.cvv" 
                                    @input="limitCvv()"
                                    :class="{'is-invalid': errors.newCvv}">
                                <p class="error-msg" x-show="errors.newCvv">Requis</p>
                            </div>
                        </div>

                        <div class="mt-md side-checkbox">
                            <input type="checkbox" name="est_sauvegardee" id="est_sauvegardee" value="1">
                            <label for="est_sauvegardee" class="checkbox-label">
                                Sauvegarder cette carte pour de futurs paiements
                            </label>
                        </div>
                    </div>
                </div>

                <p class="reservation-help-text mt-large">
                    <i class="fas fa-lock"></i> Vous paierez uniquement si l'hôte accepte votre demande de réservation. </p>
                </p>

                @php
                use App\Models\TypePaiement;
                @endphp
                <select name="typepaiement">
                    @foreach (TypePaiement::all() as $t)
                    <option value="{{ $t->idtypepaiement }}">{{ strtoupper($t->nom_type_paiement[0]) . substr($t->nom_type_paiement, 1) }}</option>
                    @endforeach
                </select>

                <button type="submit" class="submit-btn mt-md w-full">
                    Payer et envoyer la demande
                </button>
            </div>

        </form>
        </div>
    </div>

    <div class="reservation-summary-column">
        <div class="reservation-summary-card" style="position: sticky;">
            <div class="summary-header">
                @if($annonce->photo && count($annonce->photo) > 0)
                    <img src="{{ $annonce->photo[0]->nomphoto }}" alt="" class="summary-image">
                @else
                    <img src="/path/to/default-image.jpg" alt="Sans photo" class="summary-image">
                @endif

                <div>
                    <h3 class="summary-title">{{ $annonce->titre_annonce }}</h3>
                    <p class="summary-meta">{{ $annonce->nb_personnes_max }} pers / {{ $annonce->nombre_chambre }} chambres</p>
                    <p class="summary-meta">à {{$annonce->ville->nom_ville }}</p>
                </div>
            </div>
            <div class="host-info">
                 @if($annonce->utilisateur->photo_profil === null)
                    <img src="/images/photo-profil.jpg" class="host-avatar">
                @else
                    <img src="{{ $annonce->utilisateur->photo_profil }}" class="host-avatar">
                @endif
                <span class="host-name">{{ strtoupper($annonce->utilisateur->nom_utilisateur) . ' ' . $annonce->utilisateur->prenom_utilisateur}}</span>
            </div>
            <h3 class="price-title">Détails du prix</h3>
            
            <div class="price-row">
                <span>Montant de la location</span>
                <span>{{ $montant_location }} €</span>
            </div>
            
            <div class="price-row">
                <span>Frais de service</span>
                <span>{{ $frais_service }} €</span>
            </div>

            <div class="price-row bordered">
                <span>Taxe de séjour</span>
                <span>{{ $taxe_sejour }} €</span>
            </div>

            <div class="total-row">
                <span>Total</span>
                <span>{{ $total }} €</span>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingForm', () => ({
            prenom: '{{ old('prenom', auth()->user()->prenom_utilisateur) }}',
            nom: '{{ old('nom', auth()->user()->nom_utilisateur) }}',
            telephone: '{{ old('telephone', auth()->user()->telephone) }}',
            
            selectedCardId: '{{ auth()->user()->carte_credit[0]->idcarte ?? "new" }}', 
            savedCardCvv: '',
            newCard: { number: '', expiry: '', cvv: '', name: '' },

            limits: {
                maxPersons: {{ $max_capacity }},
                maxBebes: {{ $max_bebe }},
                maxAnimaux: {{ $max_animaux }}
            },

            guests: {
                adultes: {{ old('nb_adultes', 1) }},
                enfants: {{ old('nb_enfants', 0) }},
                bebes:   {{ old('nb_bebes', 0) }},
                animaux: {{ old('nb_animaux', 0) }}
            },

            errors: {
                prenom: false, nom: false, telephone: false,
                savedCvv: false, savedCvvMsg: '',
                newNumber: false, newExpiry: false, newCvv: false, newName: false
            },
            phoneErrorMessage: '',

            updateCounter(type, change) {
                let currentVal = this.guests[type];
                let newVal = currentVal + change;

                let min = (type === 'adultes') ? 1 : 0;
                if (newVal < min) return;

                if (type === 'bebes' && newVal > this.limits.maxBebes) return;
                if (type === 'animaux' && newVal > this.limits.maxAnimaux) return;

                if (type === 'adultes' || type === 'enfants') {
                    let totalHumans = 0;
                    if (type === 'adultes') {
                        totalHumans = newVal + this.guests.enfants;
                    } else {
                        totalHumans = this.guests.adultes + newVal;
                    }

                    if (totalHumans > this.limits.maxPersons) return;
                }

                this.guests[type] = newVal;
            },

            formatCardNumber() {
                let val = this.newCard.number.replace(/\D/g, '');
                this.newCard.number = val.replace(/(.{4})/g, '$1 ').trim();
                this.errors.newNumber = false;
            },
            formatExpiry() {
                let val = this.newCard.expiry.replace(/\D/g, '');
                if (val.length >= 2) val = val.substring(0, 2) + '/' + val.substring(2, 4);
                this.newCard.expiry = val;
                this.errors.newExpiry = false;
            },
            limitCvv() {
                this.newCard.cvv = this.newCard.cvv.replace(/\D/g, '');
                this.errors.newCvv = false;
            },
            clearSavedCvv() {
                this.savedCardCvv = '';
                this.errors.savedCvv = false;
            },

            validatePhone(val) {
                const valid = /^0[0-9]{9}$/.test(val.replace(/\s/g, ''));
                if (!valid) {
                    this.errors.telephone = true;
                    this.phoneErrorMessage = 'Doit comporter 10 chiffres (ex: 0612345678)';
                } else {
                    this.errors.telephone = false;
                }
                return valid;
            },

            submitAll() {
                let allValid = true;
                Object.keys(this.errors).forEach(k => this.errors[k] = false);

                if (!this.prenom.trim()) { this.errors.prenom = true; allValid = false; }
                if (!this.nom.trim()) { this.errors.nom = true; allValid = false; }
                if (!this.validatePhone(this.telephone)) { allValid = false; }

                if (this.selectedCardId !== 'new') {
                    if (this.savedCardCvv.length < 3) {
                        this.errors.savedCvv = true;
                        this.errors.savedCvvMsg = 'CVV requis';
                        allValid = false;
                    }
                } else {
                    let rawNum = this.newCard.number.replace(/\s/g, '');
                    if (rawNum.length < 16) { this.errors.newNumber = true; allValid = false; }
                    
                    let dateRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
                    if (!dateRegex.test(this.newCard.expiry)) { this.errors.newExpiry = true; allValid = false; }
                    
                    if (this.newCard.cvv.length < 3) { this.errors.newCvv = true; allValid = false; }
                    if (this.newCard.name.trim().length < 2) { this.errors.newName = true; allValid = false; }
                }

                if (allValid) {
                    this.$el.submit();
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        }));
    });
</script>
@endsection