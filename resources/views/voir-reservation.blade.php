@extends('layout')

@php
    // Determine Role
    $user = Auth::user();
    $isRequester = $user->idutilisateur == $reservation->idlocataire;
    $isOwner = $user->idutilisateur == $reservation->annonce->idproprietaire;

    $start = \Carbon\Carbon::parse($reservation->date_debut_resa);
    $end = \Carbon\Carbon::parse($reservation->date_fin_resa);
    $nb_nuits = $start->diffInDays($end);

    if ($isRequester) {
        $otherProfile = $reservation->annonce->utilisateur;
        $otherRoleName = "Hôte";
    } else {
        $otherProfile = $reservation->particulier->Utilisateur;
        $otherRoleName = "Voyageur";
    }

    $max_capacity = $reservation->annonce->nb_personnes_max;
    $max_bebe = $reservation->annonce->nb_bebe_max;
    $max_animaux = $reservation->annonce->nb_animaux_max;

    $canEdit = $isRequester && ($reservation->statut_reservation == 'en attente');
    $now = new DateTime();

    $taxe_unitaire = $reservation->annonce->ville->taxe_sejour;
    $base_total_sans_taxe = $reservation->montant_total - $reservation->taxe_sejour;
    
    $savedCards = $user->cartesBancaires ?? collect([]);
@endphp

@section('title', $isRequester ? 'Votre demande' : 'Demande reçue')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 1000;
            display: flex; justify-content: center; align-items: flex-start;
            overflow-y: auto; padding-top: 2rem; padding-bottom: 2rem;
        }
        .modal-card {
            background: white; padding: 2rem; border-radius: 12px;
            width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }
        .price-diff { font-size: 1.5rem; font-weight: bold; color: var(--primary); margin: 0.5rem 0; }
        
        .payment-card-option {
            border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 10px;
            cursor: pointer; transition: all 0.2s;
        }
        .payment-card-option.selected { border-color: var(--primary); background-color: var(--primary-light); }
        .payment-card-label { display: flex; align-items: center; cursor: pointer; width: 100%; }
        .payment-radio-input { display: none; }
        .payment-custom-radio {
            width: 16px; height: 16px; border: 2px solid #ccc; border-radius: 50%; margin-right: 10px;
            position: relative;
        }
        .payment-card-option.selected .payment-custom-radio { border-color: var(--primary); }
        .payment-card-option.selected .payment-custom-radio::after {
            content: ''; position: absolute; top: 3px; left: 3px; width: 6px; height: 6px;
            background: var(--primary); border-radius: 50%;
        }
        .input-cvv-small { width: 80px !important; text-align: center; }
        .is-invalid { border-color: red !important; }
        .text-error { color: red; font-size: 12px; margin-top: 4px; }
    </style>

    <div class="reservation-container" 
         x-data="formManager({
            adultes: {{ $reservation->nb_adultes }},
            enfants: {{ $reservation->nb_enfants }},
            bebes: {{ $reservation->nb_bebes ?? 0 }},
            animaux: {{ $reservation->nb_animaux ?? 0 }},
            dates: 'Du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}',
            maxPersons: {{ $max_capacity }},
            maxBebes: {{ $max_bebe }},
            maxAnimaux: {{ $max_animaux }},
            taxeUnitaire: {{ $taxe_unitaire }},
            originalTotal: {{ $reservation->montant_total }},
            baseTotalSansTaxe: {{ $base_total_sans_taxe }},
            defaultCardId: '{{ count($savedCards) > 0 ? $savedCards[0]->idcartebancaire : "new" }}'
         })">

        <div class="reservation-form-column">
            
            <div style="padding: 15px; border-radius: var(--radius-sm); font-weight: bold; 
                  margin-bottom: 1rem;
                  background-color: {{ $reservation->statut_reservation == 'validée' ? 'var(--success-bg)' : ($reservation->statut_reservation == 'refusée' ? 'var(--danger-bg)' : 'var(--secondary-subtle-bg)') }};
                  color: {{ $reservation->statut_reservation == 'validée' ? 'var(--success-text)' : ($reservation->statut_reservation == 'refusée' ? 'var(--danger-text)' : 'var(--text-main)') }};">
                Statut : {{ $reservation->statut_reservation ?? 'En attente' }}
            </div>

            <div class="reservation-card">

                <form action="{{ url('/reservation/update/' . $reservation->idreservation) }}" 
                      method="POST" 
                      id="updateForm"
                      @submit.prevent="submitCheck">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="nb_adultes" x-model="form.adultes">
                    <input type="hidden" name="nb_enfants" x-model="form.enfants">
                    <input type="hidden" name="nb_bebes" x-model="form.bebes">
                    <input type="hidden" name="nb_animaux" x-model="form.animaux">
                    
                    <input type="hidden" name="taxe_sejour" :value="taxeSejour">
                    <input type="hidden" name="montant_total" :value="total">

                    <template x-if="showPaymentModal">
                        <div class="modal-overlay" x-transition>
                            <div class="modal-card" @click.away="showPaymentModal = false">
                                <h3 class="reservation-subtitle">Régularisation du paiement</h3>
                                <p class="text-sm text-muted">La modification des voyageurs a augmenté le prix.</p>
                                
                                <div style="margin: 1.5rem 0; text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                    <p class="text-muted mb-0">Supplément à payer :</p>
                                    <div class="price-diff" x-text="(total - initial.originalTotal).toFixed(2) + ' €'"></div>
                                </div>

                                @if(count($savedCards) > 0)
                                    <p class="reservation-label mb-sm">Vos cartes enregistrées :</p>
                                    @foreach ($savedCards as $carte)
                                    <div class="payment-card-option" 
                                        :class="selectedCardId == '{{ $carte->idcartebancaire }}' ? 'selected' : ''">
                                        
                                        <label class="payment-card-label" for="carte_{{ $carte->idcartebancaire }}">
                                            <input type="radio" name="carte_id" value="{{ $carte->idcartebancaire }}" 
                                                id="carte_{{ $carte->idcartebancaire }}" class="payment-radio-input"
                                                x-model="selectedCardId" @click="clearSavedCvv()">
                                            
                                            <div class="payment-custom-radio"></div>
                                            <div class="card-text-group">
                                                <span class="card-text-main">**** **** **** {{ substr(decrypt($carte->numcarte), -4) }}</span>
                                                <span class="card-text-sub">Exp: {{ $carte->dateexpiration }}</span>
                                            </div>
                                        </label>

                                        <div x-show="selectedCardId == '{{ $carte->idcartebancaire }}'" class="mt-sm" x-transition>
                                            <label class="reservation-label text-sm">Confirmer le CVV*</label>
                                            <input type="text" name="cvv_verify_{{ $carte->idcartebancaire }}" 
                                                class="reservation-input input-cvv-small" placeholder="123" maxlength="4" 
                                                x-model="savedCardCvv" :class="{'is-invalid': errors.savedCvv}">
                                            <div class="text-error" x-show="errors.savedCvv" x-text="errors.savedCvvMsg"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                <div class="payment-card-option" :class="selectedCardId == 'new' ? 'selected' : ''">
                                    <label class="payment-card-label" for="carte_new">
                                        <input type="radio" name="carte_id" value="new" id="carte_new" 
                                            class="payment-radio-input" x-model="selectedCardId" @click="clearSavedCvv()">
                                        <div class="payment-custom-radio"></div>
                                        <div class="card-text-group">
                                            <span class="card-text-main">Nouvelle carte</span>
                                        </div>
                                    </label>

                                    <div x-show="selectedCardId === 'new'" x-transition class="new-card-container mt-md">
                                        <div class="reservation-input-group mb-sm">
                                            <input type="text" name="titulairecarte" class="reservation-input" placeholder="Nom du titulaire"
                                                x-model="newCard.name" :class="{'is-invalid': errors.newName}">
                                        </div>
                                        <div class="reservation-input-group mb-sm">
                                            <input type="text" name="numcarte" class="reservation-input" placeholder="Numéro de carte" maxlength="19"
                                                x-model="newCard.number" @input="formatCardNumber()" :class="{'is-invalid': errors.newNumber}">
                                        </div>
                                        <div class="form-row" style="display:flex; gap:10px;">
                                            <div class="form-col">
                                                <input type="text" name="dateexpiration" class="reservation-input" placeholder="MM/AA" maxlength="5"
                                                    x-model="newCard.expiry" @input="formatExpiry()" :class="{'is-invalid': errors.newExpiry}">
                                            </div>
                                            <div class="form-col">
                                                <input type="text" name="cvv" class="reservation-input" placeholder="CVV" maxlength="4"
                                                    x-model="newCard.cvv" @input="limitCvv()" :class="{'is-invalid': errors.newCvv}">
                                            </div>
                                        </div>
                                        <div class="mt-sm">
                                            <label><input type="checkbox" name="est_sauvegardee" value="1"> Sauvegarder</label>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; margin-top: 1.5rem;">
                                    <button type="button" class="other-btn w-full" @click="showPaymentModal = false">Annuler</button>
                                    <button type="button" class="submit-btn w-full" @click="confirmAndPay">Payer et mettre à jour</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="field-group" x-data="inputField()">
                        <h3 class="font-bold mb-sm">Dates du séjour</h3>
                        <div class="input-wrapper">
                            <input type="text" class="flatpickr-range"
                                value="Du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}" 
                                :disabled="!editing"
                                x-ref="input" @input="updateDate($el.value)">

                            @if($isRequester && $reservation->statut == 'en attente')
                                <button type="button" class="action-btn edit-trigger" @click="enable()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="mt-md">
                        <h3 class="font-bold mb-sm">Voyageurs</h3>

                        @if($canEdit)
                            <div class="picker-group">
                                <div class="picker-label-container">
                                    <span class="picker-label">Adultes</span>
                                    <span class="picker-subtext">18 ans et plus</span>
                                </div>
                                <div class="picker-controls">
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('adultes', -1)" 
                                        :disabled="form.adultes <= 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /></svg>
                                    </button>
                                    <span class="picker-value" x-text="form.adultes"></span>
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('adultes', 1)"
                                        :disabled="(form.adultes + form.enfants) >= limits.maxPersons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
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
                                        :disabled="form.enfants <= 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /></svg>
                                    </button>
                                    <span class="picker-value" x-text="form.enfants"></span>
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('enfants', 1)"
                                        :disabled="(form.adultes + form.enfants) >= limits.maxPersons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
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
                                        :disabled="form.bebes <= 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /></svg>
                                    </button>
                                    <span class="picker-value" x-text="form.bebes"></span>
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('bebes', 1)"
                                        :disabled="form.bebes >= limits.maxBebes">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="picker-group"
                                style="{{ $max_animaux <= 0 ? 'opacity:0.5; pointer-events:none;' : '' }} border-bottom: none;">
                                <div class="picker-label-container">
                                    <span class="picker-label">Animaux</span>
                                    <span class="picker-subtext">Cela inclut d'assistance acceptés</span>
                                </div>
                                <div class="picker-controls">
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('animaux', -1)"
                                        :disabled="form.animaux <= 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /></svg>
                                    </button>
                                    <span class="picker-value" x-text="form.animaux"></span>
                                    <button type="button" class="picker-btn" 
                                        @click="updateCounter('animaux', 1)"
                                        :disabled="form.animaux >= limits.maxAnimaux">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="read-only-guests"
                                style="display:flex; flex-direction:column; padding: 10px 0; gap:1rem;">
                                <p>{{ $reservation->nb_adultes }} Adulte(s)</p>
                                <p>{{ $reservation->nb_enfants }} Enfant(s)</p>
                                @if(($reservation->nb_bebes ?? 0) > 0)
                                <p>{{ $reservation->nb_bebes }} Bébé(s)</p> @endif
                                @if(($reservation->nb_animaux ?? 0) > 0)
                                <p>{{ $reservation->nb_animaux }} Animaux</p> @endif
                            </div>
                        @endif
                    </div>

                    @if($isRequester && $reservation->statut_reservation == 'en attente')
                        <div class="mt-md">
                            <button type="submit" class="submit-btn" :disabled="!isDirty">
                                Enregistrer
                            </button>
                        </div>
                    @endif
                </form>
                <div class="mt-md" style="border-top: 1px solid var(--border-default);">
                    <h3 class="font-bold mb-sm mt-md">Actions</h3>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <form action="{{ route('contact.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receveur_id" value="{{ $otherProfile->idutilisateur }}">
                            
                            <button type="submit" class="other-btn">
                                Contacter {{ $otherProfile->prenom_utilisateur }} {{ $otherProfile->nom_utilisateur }}
                            </button>
                        </form>

                        @if($isRequester)
                            @if($reservation->statut_reservation == 'en attente')
                                <form action="{{ url('/reservation/cancel/' . $reservation->idreservation) }}" method="POST" style="width:100%; display:inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ? Cette action est irréversible.');">
                                    @csrf
                                    <button type="submit" class="other-btn" style="color: var(--danger); border-color: var(--danger);">
                                        Annuler la demande
                                    </button>
                                </form>
                            @elseif ($reservation->statut_reservation == 'validée' && $now->format('Y-m-d') >= $reservation->date_debut_resa && $now->format('Y-m-d') <= $reservation->date_fin_resa && !isset($reservation->incident))
                                <a href="{{ url('/reservation/declare/' . $reservation->idreservation) }}" class="other-btn" style="color: var(--danger); border-color: var(--danger);">
                                    Déclarer un incident
                                </a>
                            @endif
                        @endif

                        @if($isOwner)
                            @if($reservation->statut_reservation == 'en attente')
                                <div style="display:flex; justify-content: space-between; align-items: center; width: 100%;">
                                    <form action="{{ url('/reservation/accept/' . $reservation->idreservation) }}" method="POST"
                                        style="width: calc(50% - 0.5rem); display:inline;">
                                        @csrf
                                        <button type="submit" class="submit-btn" style="width: 100%;">Accepter</button>
                                    </form>
                                    <form action="{{ url('/reservation/refuse/' . $reservation->idreservation) }}" method="POST"
                                        style="width: calc(50% - 0.5rem); display:inline;">
                                        @csrf
                                        <button type="submit" class="other-btn"
                                            style="width: 100%; color: var(--danger); border-color: var(--danger);">Refuser</button>
                                    </form>
                                </div>
                            @endif
                        @endif


                        @if ($reservation->statut_reservation == 'complétée' && $isRequester)
                            <input type="submit" style="submit-btn" value="Laisser un avis (todo)">
                        @endif
                    </div>
                </div>
            </div>

            @if($reservation->incident)
            <div class="reservation-summary-card mt-md">
                <div class="res-header">
                    <div>
                        <h3 class="font-bold mb-sm">Incident</h3>
                        <span class="res-dates">
                            <p class="card-meta" style="line-height: 0;">Incident signalé le {{\Carbon\Carbon::parse($reservation->incident->date_signalement)->format('d/m/Y')}}</p>
                        </span>
                    </div>
                        @if($reservation->incident->statut_incident === "clos")
                        <span class="status-dot st-accepted">
                            {{ $reservation->incident->statut_incident }}
                        </span>
                        @else
                        <span class="status-dot st-pending">
                            {{ $reservation->incident->statut_incident }}
                        </span>
                        @endif
                </div>

                <div>
                    <p style="margin-top: 2rem">{{$reservation->incident->description_incident}}</p>
                    <div class="mt-md" style="border-top: 1px solid var(--border-default);"></div>
                    @if($reservation->incident->reponse_incident !== null)
                        <p style="margin-top: 1rem; margin-bottom: 1rem">{{$reservation->incident->reponse_incident}}</p>
                    @endif

                    @if($isRequester && isset($reservation->incident))
                        @if($reservation->incident->reponse_incident === null)
                            <p class="card-meta">Auncune réponse</p>
                        @endif
                        <div>
                            @if($reservation->incident->statut_incident !== "clos")
                                <form action="{{ url('reservation/clore_incident') }}" method="POST"
                                    style="width:100%; display:inline;"
                                    class="mt-md"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir clore l\'incident ? Cette action est irréversible.');">
                                    @csrf
                                    <input type="hidden" name="idincident" value="{{  $reservation->incident->idincident }}">
                                    <button type="submit" class="submit-btn" style="margin-top: 2rem;">Clore l'incident</button>
                                </form>
                            @endif
                        </div>
                    @endif


                    @if($isOwner && isset($reservation->incident))
                        <div>
                            @if($reservation->incident->statut_incident === "clos" && $reservation->incident->reponse_incident === null)
                                <p class="card-meta">Vous n'avez fourni auncune réponse</p>
                            @endif
                            @if($reservation->incident->statut_incident !== "clos")
                                <form method="POST"
                                    style="width:100%; display:inline;"
                                    class="mt-md">
                                    @csrf
                                    <input type="hidden" name="idincident" value="{{  $reservation->incident->idincident }}">
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                        @if($reservation->incident->reponse_incident === null)
                                            <textarea name="justif_incident" id="justif_incident" placeholder="En attente d'une justification de votre part" style="margin-top : 1rem; margin-bottom : 1rem;" rows="5"></textarea>
                                            <button type="submit" class="other-btn" formaction="{{ url('reservation/justifier_incident') }}"
                                            onclick="setRequired(true)">
                                            Donner une justification sans clore</button>
                                        @endif
                                        <button type="submit" class="submit-btn" formaction="{{ url('reservation/clore_incident') }}"
                                        onclick="setRequired(false); return confirm('Êtes-vous sûr de vouloir clore l\'incident ? Cette action est irréversible.');">
                                        Reconnaître l'incident et clore</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="reservation-summary-column">

            <div style="position: sticky; top: 4.5rem;">
                <div class="reservation-summary-card mb-mm">
                    <div class="summary-header">
                        @if($reservation->annonce->photo && count($reservation->annonce->photo) > 0)
                            <img src="{{ $reservation->annonce->photo[0]->nomphoto }}" alt="" class="summary-image">
                        @else
                            <img src="/path/to/default-image.jpg" alt="Sans photo" class="summary-image">
                        @endif

                        <div>
                            <h3 class="summary-title">{{ $reservation->annonce->titre_annonce }}</h3>
                            <div class="text-muted text-sm mb-sm">
                                <p>{{ $reservation->annonce->nombre_chambre }} chambre(s),
                                    {{ $reservation->annonce->nb_personnes_max }} personne(s)</p>
                            </div>
                            <div class="text-sm mb-sm">
                                <p>À {{ $reservation->annonce->ville->nom_ville ?? '' }}</p>
                            </div>
                            <a href="{{ url('/annonce/' . $reservation->annonce->idannonce) }}"
                                class="hyperlink text-sm">Voir l'annonce</a>

                        </div>
                    </div>

                    <h3 class="price-title">Détails du prix</h3>
                    <div class="price-row">
                        <span>{{ $reservation->annonce->prix_nuit }} € x {{ $nb_nuits }} nuits</span>
                        <span>{{ $reservation->montant_total - $reservation->frais_services - $reservation->taxe_sejour }}
                            €</span>
                    </div>
                    <div class="price-row">
                        <span>Frais de service</span>
                        <span>{{ $reservation->frais_services }} €</span>
                    </div>
                    <div class="price-row bordered">
                        <span>Taxe de séjour</span>
                        <span x-text="taxeSejour + ' €'">{{ $reservation->taxe_sejour }} €</span>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span x-text="total + ' €'">{{ $reservation->montant_total }} €</span>
                    </div>
                </div>

                <div class="reservation-summary-card">
                    <h3 class="font-bold mb-sm">{{ $otherRoleName }}</h3>
                    <div class="host-info" style="margin-bottom: 15px;">
                        <img src="{{ asset($otherProfile->photo_profil ?? 'images/photo-profil.jpg') }}"
                            class="host-avatar" style="width: 50px; height: 50px;">
                        <div>
                            <div class="host-name font-bold">{{ $otherProfile->prenom_utilisateur }}
                                {{ $otherProfile->nom_utilisateur }}</div>
                            <div class="text-muted text-sm">{{ $otherProfile->ville->nom_ville ?? '' }}</div>
                        </div>
                    </div>
                    <a href="{{ url('/proprio/' . $otherProfile->idutilisateur) }}" class="other-btn"
                        style="width: 100%; display: block; text-align: center;">Voir le profil</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formManager', (initialData) => ({
                initial: { ...initialData },
                form: { 
                    adultes: initialData.adultes,
                    enfants: initialData.enfants,
                    bebes: initialData.bebes,
                    animaux: initialData.animaux,
                    dates: initialData.dates
                },
                
                prices: {
                    taxeUnitaire: initialData.taxeUnitaire,
                    baseTotal: initialData.baseTotalSansTaxe
                },

                limits: {
                    maxPersons: initialData.maxPersons,
                    maxBebes: initialData.maxBebes,
                    maxAnimaux: initialData.maxAnimaux
                },

                showPaymentModal: false,
                selectedCardId: initialData.defaultCardId,
                savedCardCvv: '',
                newCard: { number: '', expiry: '', cvv: '', name: '' },
                errors: { 
                    savedCvv: false, savedCvvMsg: '',
                    newName: false, newNumber: false, newExpiry: false, newCvv: false 
                },

                get taxeSejour() {
                    return ((this.form.adultes + this.form.enfants) * this.prices.taxeUnitaire).toFixed(2);
                },

                get total() {
                    return (this.prices.baseTotal + parseFloat(this.taxeSejour)).toFixed(2);
                },

                get isDirty() {
                    return this.form.adultes !== this.initial.adultes || 
                           this.form.enfants !== this.initial.enfants ||
                           this.form.bebes !== this.initial.bebes || 
                           this.form.animaux !== this.initial.animaux ||
                           this.form.dates !== this.initial.dates;
                },

                updateCounter(type, change) {
                    let newVal = this.form[type] + change;
                    let min = (type === 'adultes') ? 1 : 0;
                    if (newVal < min) return;
                    if (type === 'bebes' && newVal > this.limits.maxBebes) return;
                    if (type === 'animaux' && newVal > this.limits.maxAnimaux) return;

                    if (type === 'adultes' || type === 'enfants') {
                        let totalHumans = (type === 'adultes') ? newVal + this.form.enfants : this.form.adultes + newVal;
                        if (totalHumans > this.limits.maxPersons) return;
                    }
                    this.form[type] = newVal;
                },

                updateDate(value) {
                    this.form.dates = value;
                },

                submitCheck() {
                    if (parseFloat(this.total) > parseFloat(this.initial.originalTotal)) {
                        this.showPaymentModal = true;
                    } else {
                        document.getElementById('updateForm').submit();
                    }
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

                confirmAndPay() {
                    let allValid = true;
                    Object.keys(this.errors).forEach(k => this.errors[k] = false);

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
                        if (!dateRegex.test(this.newCard.expiry)) { 
                            this.errors.newExpiry = true; allValid = false; 
                        } else {
                            const [m, y] = this.newCard.expiry.split('/');
                            const now = new Date();
                            const curY = now.getFullYear() % 100;
                            const curM = now.getMonth() + 1;
                            if (parseInt(y) < curY || (parseInt(y) === curY && parseInt(m) < curM)) {
                                this.errors.newExpiry = true; allValid = false;
                            }
                        }

                        if (this.newCard.cvv.length < 3) { this.errors.newCvv = true; allValid = false; }
                        if (this.newCard.name.trim().length < 2) { this.errors.newName = true; allValid = false; }
                    }

                    if (allValid) {
                        document.getElementById('updateForm').submit();
                    }
                }
            }));

            Alpine.data('inputField', () => ({
                editing: false,
                enable() { this.editing = true; this.$nextTick(() => this.$refs.input.focus()); }
            }));
        });

        function setRequired(value) {
            const input = document.getElementById('justif_incident');
            if (input){
                if (value) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            }
        }
    </script>
@endsection