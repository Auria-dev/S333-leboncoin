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
                  x-data="reservationForm()"
                  @submit.prevent="submitForm">

                @csrf
                <input type="hidden" name="date_debut_resa" value="{{ $date_debut_resa }}">
                <input type="hidden" name="date_fin_resa" value="{{ $date_fin_resa }}">

                <h2 class="reservation-subtitle">Nombre de voyageurs</h2>
                
                <input type="hidden" name="nb_adultes" id="input_adultes" value="{{ old('nb_adultes', 1) }}">
                <input type="hidden" name="nb_enfants" id="input_enfants" value="{{ old('nb_enfants', 0) }}">
                <input type="hidden" name="nb_bebes" id="input_bebes" value="{{ old('nb_bebes', 0) }}">
                <input type="hidden" name="nb_animaux" id="input_animaux" value="{{ old('nb_animaux', 0) }}">
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
                        <button type="button" class="picker-btn" id="btn_minus_adultes" onclick="updateCounter('adultes', -1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                        </button>
                        <span id="display_adultes" class="picker-value">{{ old('nb_adultes', 1) }}</span>
                        <button type="button" class="picker-btn" id="btn_plus_adultes" onclick="updateCounter('adultes', 1)">
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
                        <button type="button" class="picker-btn" id="btn_minus_enfants" onclick="updateCounter('enfants', -1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                        </button>
                        <span id="display_enfants" class="picker-value">{{ old('nb_enfants', 0) }}</span>
                        <button type="button" class="picker-btn" id="btn_plus_enfants" onclick="updateCounter('enfants', 1)">
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
                        <button type="button" class="picker-btn" id="btn_minus_bebes" onclick="updateCounter('bebes', -1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                        </button>
                        <span id="display_bebes" class="picker-value">{{ old('nb_bebes', 0) }}</span>
                        <button type="button" class="picker-btn" id="btn_plus_bebes" onclick="updateCounter('bebes', 1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </button>
                    </div>
                </div>

                <div class="picker-group" style="{{ $max_animaux <= 0 ? 'opacity:0.5; pointer-events:none;' : '' }}">
                    <div class="picker-label-container">
                        <span class="picker-label">Animaux</span>
                        <span class="picker-subtext">Cela inclut d'assistance acceptés</span>
                    </div>
                    <div class="picker-controls">
                        <button type="button" class="picker-btn" id="btn_minus_animaux" onclick="updateCounter('animaux', -1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                        </button>
                        <span id="display_animaux" class="picker-value">{{ old('nb_animaux', 0) }}</span>
                        <button type="button" class="picker-btn" id="btn_plus_animaux" onclick="updateCounter('animaux', 1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </button>
                    </div>
                </div>

                <h2 class="reservation-subtitle mt-large">Vos informations</h2>

                <div class="reservation-input-group">
                    <label class="reservation-label">Prénom*</label>
                    <div class="text-error" x-show="errors.prenom">Le prénom est obligatoire.</div>
                    <input type="text" name="prenom" required 
                           class="reservation-input" 
                           :class="{'is-invalid': errors.prenom}"
                           x-model="prenom"
                           @input="errors.prenom = false">
                </div>

                <div class="reservation-input-group">
                    <label class="reservation-label">Nom*</label>
                    <div class="text-error" x-show="errors.nom">Le nom est obligatoire.</div>
                    <input type="text" name="nom" required 
                           class="reservation-input" 
                           :class="{'is-invalid': errors.nom}"
                           x-model="nom"
                           @input="errors.nom = false">
                </div>

                <div class="reservation-input-group">
                    <label class="reservation-label">Numéro de téléphone*</label>
                    <div class="text-error" x-show="errors.telephone" x-text="phoneErrorMessage"></div>
                    <input type="tel" name="telephone" required maxlength="10"
                           class="reservation-input" 
                           placeholder="06 12 34 56 78"
                           x-model="telephone"
                           @input="validatePhone($el.value)"
                           :class="{'is-invalid': errors.telephone}">
                    
                    
                    <small class="reservation-help-text" x-show="!errors.telephone">Votre numéro de téléphone sera partagé à l'hôte une fois votre demande de réservation acceptée</small>
                </div>

                <h2 class="reservation-subtitle mt-large">Paiement</h2>
                <p>todo :]</p><br>

                <input type="submit" class="submit-btn" value="Envoyer la demande de réservation" />
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
    // Alpine Logic for Form Validation
    document.addEventListener('alpine:init', () => {
        Alpine.data('reservationForm', () => ({
            prenom: '{{ old('prenom', auth()->user()->prenom_utilisateur) }}',
            nom: '{{ old('nom', auth()->user()->nom_utilisateur) }}',
            telephone: '{{ old('telephone', auth()->user()->telephone) }}',
            
            errors: {
                prenom: false,
                nom: false,
                telephone: false
            },
            phoneErrorMessage: '',

            init() {
                // Initial validation if data exists
                if(this.telephone) this.validatePhone(this.telephone);
            },

            validatePhone(val) {
                const valid = /^0[0-9]{9}$/.test(val);
                if (!valid) {
                    this.errors.telephone = true;
                    this.phoneErrorMessage = 'Doit comporter 10 caractères et commencer par un 0';
                } else {
                    this.errors.telephone = false;
                    this.phoneErrorMessage = '';
                }
                return valid;
            },

            submitForm(e) {
                let isValid = true;

                // Validate Prenom
                if (!this.prenom.trim()) {
                    this.errors.prenom = true;
                    isValid = false;
                }

                // Validate Nom
                if (!this.nom.trim()) {
                    this.errors.nom = true;
                    isValid = false;
                }

                // Validate Phone
                if (!this.validatePhone(this.telephone)) {
                    isValid = false;
                }

                if (isValid) {
                    this.$el.submit();
                }
            }
        }));
    });

    // Existing Counter Logic (Preserved)
    const MAX_PERSONS = {{ $max_capacity }};
    const MAX_BEBES = {{ $max_bebe }};
    const MAX_ANIMAUX = {{ $max_animaux }};

    document.addEventListener('DOMContentLoaded', function() {
        updateCounter('adultes', 0);
        updateCounter('enfants', 0);
        updateCounter('bebes', 0);
        updateCounter('animaux', 0);

        updateButtonStates();
    });

    function updateCounter(type, change) {
        const input = document.getElementById('input_' + type);
        const display = document.getElementById('display_' + type);
        let currentValue = parseInt(input.value);
        let newValue = currentValue + change;

        let minLimit = (type === 'adultes') ? 1 : 0;
        if (newValue < minLimit) return;

        if (type === 'bebes' && newValue > MAX_BEBES) return;
        if (type === 'animaux' && newValue > MAX_ANIMAUX) return;

        if (type === 'adultes' || type === 'enfants') {
            const currentAdults = parseInt(document.getElementById('input_adultes').value);
            const currentEnfants = parseInt(document.getElementById('input_enfants').value);
            
            let projectedAdults = (type === 'adultes') ? newValue : currentAdults;
            let projectedEnfants = (type === 'enfants') ? newValue : currentEnfants;
            
            if ((projectedAdults + projectedEnfants) > MAX_PERSONS) {
                return; 
            }
        }

        input.value = newValue;
        display.textContent = newValue;
        
        updateButtonStates();
    }

    function updateButtonStates() {
        const adults = parseInt(document.getElementById('input_adultes').value);
        const enfants = parseInt(document.getElementById('input_enfants').value);
        const bebes = parseInt(document.getElementById('input_bebes').value);
        const animaux = parseInt(document.getElementById('input_animaux').value);
        
        const totalHumans = adults + enfants;

        document.getElementById('btn_minus_adultes').disabled = (adults <= 1);
        document.getElementById('btn_plus_adultes').disabled = (totalHumans >= MAX_PERSONS);

        document.getElementById('btn_minus_enfants').disabled = (enfants <= 0);
        document.getElementById('btn_plus_enfants').disabled = (totalHumans >= MAX_PERSONS);

        if(document.getElementById('btn_minus_bebes')) {
            document.getElementById('btn_minus_bebes').disabled = (bebes <= 0);
            document.getElementById('btn_plus_bebes').disabled = (bebes >= MAX_BEBES);
        }

        if(document.getElementById('btn_minus_animaux')) {
            document.getElementById('btn_minus_animaux').disabled = (animaux <= 0);
            document.getElementById('btn_plus_animaux').disabled = (animaux >= MAX_ANIMAUX);
        }
    }
</script>
@endsection