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
@endphp

@section('title', $isRequester ? 'Votre demande' : 'Demande reçue')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="//unpkg.com/alpinejs" defer></script>


    <div class="reservation-container">
        <div class="reservation-form-column">
            <h1 class="reservation-page-title">

            </h1>

            <div
                style="padding: 15px; border-radius: var(--radius-sm); font-weight: bold; 
                    margin-bottom: 1rem;
                    background-color: {{ $reservation->statut_reservation == 'validée' ? 'var(--success-bg)' : ($reservation->statut_reservation == 'refusée' ? 'var(--danger-bg)' : 'var(--secondary-subtle-bg)') }};
                    color: {{ $reservation->statut_reservation == 'validée' ? 'var(--success-text)' : ($reservation->statut_reservation == 'refusée' ? 'var(--danger-text)' : 'var(--text-main)') }};">
                Statut : {{ $reservation->statut_reservation ?? 'En attente' }}
            </div>

            <div class="reservation-card">

                <form action="{{ url('/reservation/update/' . $reservation->idreservation) }}" method="POST" x-data="formManager({
                        dates: 'Du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}',
                        adultes: {{ $reservation->nb_adultes }},
                        enfants: {{ $reservation->nb_enfants }},
                        bebes: {{ $reservation->nb_bebes ?? 0 }},
                        animaux: {{ $reservation->nb_animaux ?? 0 }}
                    })" @submit.prevent="submitForm">
                    @csrf
                    @method('PUT')

                    <div class="field-group" x-data="inputField()">
                        <label class="font-bold">Dates du séjour</label>
                        <div class="input-wrapper">
                            <input type="text" class="flatpickr-range"
                                value="Du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}" :disabled="!editing"
                                x-ref="input" @input="updateDate($el.value)">

                            @if($isRequester && $reservation->statut == 'en attente')
                                <button type="button" class="action-btn edit-trigger" @click="enable()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                        </path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="mt-md">
                        <h3 class="font-bold mb-sm">Voyageurs</h3>

                        <input type="hidden" name="nb_adultes" id="input_adultes" x-model.number="form.adultes">
                        <input type="hidden" name="nb_enfants" id="input_enfants" x-model.number="form.enfants">
                        <input type="hidden" name="nb_bebes" id="input_bebes" x-model.number="form.bebes">
                        <input type="hidden" name="nb_animaux" id="input_animaux" x-model.number="form.animaux">

                        @if($canEdit)
                            <div class="picker-group">
                                <div class="picker-label-container">
                                    <span class="picker-label">Adultes</span>
                                    <span class="picker-subtext">18 ans et plus</span>
                                </div>
                                <div class="picker-controls">
                                    <button type="button" class="picker-btn" id="btn_minus_adultes"
                                        onclick="updateCounter('adultes', -1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span id="display_adultes" class="picker-value">{{ $reservation->nb_adultes }}</span>
                                    <button type="button" class="picker-btn" id="btn_plus_adultes"
                                        onclick="updateCounter('adultes', 1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="picker-group">
                                <div class="picker-label-container">
                                    <span class="picker-label">Enfants</span>
                                    <span class="picker-subtext">De 3 à 17 ans</span>
                                </div>
                                <div class="picker-controls">
                                    <button type="button" class="picker-btn" id="btn_minus_enfants"
                                        onclick="updateCounter('enfants', -1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span id="display_enfants" class="picker-value">{{ $reservation->nb_enfants }}</span>
                                    <button type="button" class="picker-btn" id="btn_plus_enfants"
                                        onclick="updateCounter('enfants', 1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="picker-group" style="{{ $max_bebe <= 0 ? 'opacity:0.5; pointer-events:none;' : '' }}">
                                <div class="picker-label-container">
                                    <span class="picker-label">Bébés</span>
                                    <span class="picker-subtext">Moins de 3 ans</span>
                                </div>
                                <div class="picker-controls">
                                    <button type="button" class="picker-btn" id="btn_minus_bebes"
                                        onclick="updateCounter('bebes', -1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span id="display_bebes" class="picker-value">{{ $reservation->nb_bebes ?? 0 }}</span>
                                    <button type="button" class="picker-btn" id="btn_plus_bebes"
                                        onclick="updateCounter('bebes', 1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
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
                                    <button type="button" class="picker-btn" id="btn_minus_animaux"
                                        onclick="updateCounter('animaux', -1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span id="display_animaux" class="picker-value">{{ $reservation->nb_animaux ?? 0 }}</span>
                                    <button type="button" class="picker-btn" id="btn_plus_animaux"
                                        onclick="updateCounter('animaux', 1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
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
                        <button class="other-btn">
                            Contacter {{ $otherProfile->prenom_utilisateur }} {{ $otherProfile->nom_utilisateur }}
                        </button>

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
            @if($isRequester && isset($reservation->incident))
                <div class="reservation-summary-card mt-md">
                    <h3 class="font-bold mb-sm">Incident</h3>
                    <div style="margin-bottom: 15px;">
                        <p>{{$reservation->incident->date_signalement}}</p>
                        <p>{{$reservation->incident->description_incident}}</p>
                        @if({{$reservation->incident->reponse_incident === null}})
                            <p>Auncune réponse</p>
                        @else
                            <p>{{$reservation->incident->reponse_incident}}</p>
                        @endif
                        <div>
                            @if($reservation->incident->statut_incident !== "clos")
                                <form action="{{ url('reservation/clore_incident') }}" method="POST"
                                    style="width:100%; display:inline;"
                                    class="mt-md"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir clore l''incident ? Cette action est irréversible.');">
                                    @csrf

                                    <input type="hidden" name="idincident" value="{{  $reservation->incident->idincident }}">
                                    <input type="submit" class="submit-btn" value="Clore l'incident "/>
                                </form>
                            @else
                                <div>
                                    <label class="pill-label">
                                        <input type="checkbox" name="clore" checked disabled>
                                        <span class="pill-content">
                                            <span class="icon-wrapper">
                                                <span class="checkmark-draw"></span>
                                            </span>
                                            <span>Incident clos</span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                        </div>
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
                        <span>{{ $reservation->taxe_sejour }} €</span>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>{{ $reservation->montant_total }} €</span>
                    </div>
                </div>

                <div class="reservation-summary-card">
                    <h3 class="font-bold mb-sm">{{ $otherRoleName }}</h3>
                    <div class="host-info" style="margin-bottom: 15px;">
                        <img src="{{ asset($otherProfile->photo_profil ?? 'assets/default-avatar.png') }}"
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

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('formManager', (initialData) => ({
                    initial: { ...initialData },
                    form: { ...initialData },

                    get isDirty() {
                        return JSON.stringify(this.initial) !== JSON.stringify(this.form);
                    },

                    updateDate(value) {
                        this.form.dates = value;
                    },

                    submitForm() {
                        this.$el.submit();
                    }
                }));

                Alpine.data('inputField', () => ({
                    editing: false,
                    enable() { this.editing = true; this.$nextTick(() => this.$refs.input.focus()); }
                }));
            });

            // Counter Logic
            const MAX_PERSONS = {{ $max_capacity }};
            const MAX_BEBES = {{ $max_bebe }};
            const MAX_ANIMAUX = {{ $max_animaux }};

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

                input.dispatchEvent(new Event('input'));
                updateButtonStates();
            }

            function updateButtonStates() {
                if (!document.getElementById('input_adultes')) return; // Safety check

                const adults = parseInt(document.getElementById('input_adultes').value);
                const enfants = parseInt(document.getElementById('input_enfants').value);
                const bebes = parseInt(document.getElementById('input_bebes').value);
                const animaux = parseInt(document.getElementById('input_animaux').value);

                const totalHumans = adults + enfants;

                document.getElementById('btn_minus_adultes').disabled = (adults <= 1);
                document.getElementById('btn_plus_adultes').disabled = (totalHumans >= MAX_PERSONS);

                document.getElementById('btn_minus_enfants').disabled = (enfants <= 0);
                document.getElementById('btn_plus_enfants').disabled = (totalHumans >= MAX_PERSONS);

                if (document.getElementById('btn_minus_bebes')) {
                    document.getElementById('btn_minus_bebes').disabled = (bebes <= 0);
                    document.getElementById('btn_plus_bebes').disabled = (bebes >= MAX_BEBES);
                }

                if (document.getElementById('btn_minus_animaux')) {
                    document.getElementById('btn_minus_animaux').disabled = (animaux <= 0);
                    document.getElementById('btn_plus_animaux').disabled = (animaux >= MAX_ANIMAUX);
                }
            }

            // Initialize buttons on load
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('input_adultes')) {
                    updateButtonStates();
                }
            });
        </script>
@endsection