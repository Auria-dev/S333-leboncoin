@extends('layout')

@section('title', 'Modifier mon compte')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="center-container">
        <div class="max-w-650">

            <div class="pdp">
                @php
                    $currentPhoto = Auth::user()->photo_profil ?? 'images/default_profile.png';
                @endphp

                @if ($user->photo_profil === null)
                    <img src="/images/photo-profil.jpg" class="profile-img">
                @else
                    <img src="{{ asset($currentPhoto) }}" id="imagePreview" class="profile-img">
                @endif
            </div>

            <form class="form-pdp" method="POST" action="{{ url('modifier_compte/upload') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="upload-container">
                    <div class="button-group">
                        <button type="button" id="customSelectBtn" class="btn btn-select">
                            Choisir
                        </button>

                        <button type="submit" id="uploadBtn" class="btn btn-upload">
                            Modifier
                        </button>
                    </div>

                    <span id="fileChosen">Aucune image sélectionnée</span>

                    <input type="file" name="file" id="fileInput" accept=".png, .jpeg, .jpg" hidden>
                </div>
            </form>


            <form action="{{ url('modifier_compte/update') }}" method="POST" class="form-container" x-data="formManager()"
                @submit.prevent="submitForm" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert-error">
                        Veuillez corriger les erreurs ci-dessous.
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <span>Vous avez créé votre compte {{ \Carbon\Carbon::parse($user->date_creation)->format('d/m/Y') }}</span>
                <div class="row-group">
                    <div class="field-group" x-data="inputField()">
                        <label class="font-bold">Prénom</label>
                        <div class="input-wrapper">
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom_utilisateur) }}"
                                :disabled="!editing" x-ref="input" @input="touch()">
                            <button type="button" class="action-btn edit-trigger" @click="enable()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('prenom')
                            <div class="text-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group" x-data="inputField()">
                        <label class="font-bold">Nom</label>
                        <div class="input-wrapper">
                            <input type="text" name="nom" value="{{ old('nom', $user->nom_utilisateur) }}"
                                :disabled="!editing" x-ref="input" @input="touch()">
                            <button type="button" class="action-btn edit-trigger" @click="enable()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('nom')
                            <div class="text-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-group" x-data="inputField()">
                    <label class="font-bold">Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email', $user->mail) }}" :disabled="!editing"
                            x-ref="input" @input="touch(); validateEmail($el.value)" :class="{ 'is-invalid': error }">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="text-error" x-show="error" x-text="error"></div>
                    @error('email')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group" x-data="inputField()">
                    <label class="font-bold">Téléphone</label>
                    <div class="input-wrapper">
                        <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                            :disabled="!editing" x-ref="input" maxlength="10"
                            @input="touch(); validatePhone($el.value)" :class="{ 'is-invalid': error }">
                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="text-error" x-show="error" x-text="error"></div>
                    @error('telephone')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group" x-data="addressField(
                    '{{ addslashes(old('adresse', $user->adresse_utilisateur)) }}',
                    '{{ addslashes(old('ville', $ville->nom_ville ?? '')) }}',
                    '{{ addslashes(old('code_postal', $ville->code_postal ?? '')) }}'
                )" @click.outside="closeDropdown()">

                    <label class="font-bold">Adresse</label>
                    <div class="input-wrapper">
                        <input type="text" name="adresse" x-model="display" placeholder="Rechercher..."
                            autocomplete="off" :disabled="!editing" x-ref="input" @input="touch(); search()"
                            :class="{ 'is-invalid': error }">

                        <input type="hidden" name="ville" x-model="city">
                        <input type="hidden" name="code_postal" x-model="zip">

                        <button type="button" class="action-btn edit-trigger" @click="enable()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="dropdown-results" x-show="results.length > 0">
                        <template x-for="item in results" :key="item.properties.id">
                            <div class="dropdown-item" @mousedown.prevent="" @click="select(item)">
                                <div class="font-bold" x-text="item.properties.label"></div>
                                <div class="text-sm text-gray-500">
                                    <span x-text="item.properties.postcode"></span>
                                    <span x-text="item.properties.city"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                    @error('adresse')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                @if ($isEntreprise)
                    <div class="separator"></div>
                    <div class="field-group" x-data="inputField()">
                        <label class="font-bold">Numéro SIRET</label>
                        <div class="input-wrapper">
                            <input type="text" name="siret" value="{{ old('siret', $entreprise->numsiret ?? '') }}"
                                :disabled="!editing" x-ref="input" @input="touch()">
                            <button type="button" class="action-btn edit-trigger" @click="enable()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('siret')
                            <div class="text-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group" x-data="inputField()">
                        <label class="font-bold">Secteur</label>
                        <div class="input-wrapper">
                            <select name="secteur" :disabled="!editing" x-ref="input" @change="touch()">
                                @foreach ($secteurs as $s)
                                    <option value="{{ $s->nom_secteur }}"
                                        {{ ($entreprise->idsecteur ?? '') == $s->idsecteur ? 'selected' : '' }}>
                                        {{ $s->nom_secteur }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="action-btn edit-trigger" @click="enable()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="separator"></div>
                @endif

                <div x-data="passwordManager()" class="field-group">
                    <div class="password-section-header">
                        <span class="password-section-title">Sécurité</span>
                        <button type="button" class="password-edit-btn" x-show="!editing" @click="enable()">
                            Modifier le mot de passe
                        </button>
                    </div>

                    <div class="password-container" :class="{ 'is-editing': editing }">

                        <div class="text-error mt-sm" x-show="errorType === 'length'">
                            Le mot de passe doit contenir au moins 8 caractères.
                        </div>
                        <div class="text-error mt-sm" x-show="errorType === 'match'">
                            Les mots de passe ne correspondent pas.
                        </div>

                        <div class="row-group">
                            <div class="field-group">
                                <label class="font-bold">Nouveau mot de passe</label>
                                <div class="input-wrapper">
                                    <input type="password" name="password" x-model="p1" placeholder="********"
                                        :disabled="!editing" x-ref="p1input" @input="check();"
                                        :class="{ 'is-invalid': errorType === 'length' }">
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="font-bold">Confirmation</label>
                                <div class="input-wrapper">
                                    <input type="password" name="password_confirmation" x-model="p2"
                                        placeholder="********" :disabled="!editing" @input="check();"
                                        :class="{ 'is-invalid': errorType === 'match' }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($isParticulier && $user->particulier->piece_identite === null)
                    <div class="upload-container">
                        <label>Transmettre votre pièce d'identité sous forme de PDF.</label>

                        <div class="button-group">
                            <button type="button" id="customSelectBtnPDF" class="btn btn-select">
                                Sélectionner un fichier
                            </button>
                        </div>

                        <span id="fileChosenPDF" class="fileChosen">Aucun fichier sélectionné</span>

                        <input type="file" name="file" id="realFileInputPDF" accept=".pdf" hidden>
                    </div>
                @endif
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card security-card">
                                <div class="security-header">
                                    <h5 class="mb-0" style="font-weight: 700;">Sécurité du compte</h5>
                                </div>
                                <div class="security-body">
                                    <div class="row align-items-center">

                                        <div class="col-md-8">
                                            <div>
                                                <h5 style="font-weight: bold; margin-bottom: 8px;">Authentification à deux
                                                    facteurs (2FA)</h5>
                                                <p class="text-muted mb-0" style="line-height: 1.6;">
                                                    Protégez votre compte contre le piratage. Une fois activée, un code
                                                    temporaire sera requis à chaque connexion.
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="col-md-4 text-right d-flex flex-column align-items-end justify-content-center mt-4 mt-md-0">
                                            @if (auth()->user()->google2fa_secret)
                                                <div class="status-active mb-2">
                                                    Protection Active
                                                </div>

                                                <button type="submit" form="form-desactiver-2fa" class="btn-danger-link"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir désactiver la double authentification ?')">
                                                    Désactiver
                                                </button>
                                            @else
                                                <div style="text-align: right;">
                                                    <span class="d-block text-muted small mb-2"
                                                        style="margin-right: 1rem;">
                                                        Recommandé
                                                    </span>
                                                    <a href="{{ route('2fa.enable') }}" class="btn-action-orange">
                                                        Configurer la 2FA
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('suppression_compte') }}" class="btn-danger"
                    onclick="return confirm('ATTENTION\n\nCette action est IRRÉVERSIBLE.\n\nLa suppression de vos données personnelles entraînera :\n- La suppression définitive de votre compte\n- L’effacement de toutes vos données associées\n- L’impossibilité de récupérer ces informations ultérieurement\n\nSouhaitez-vous vraiment continuer ?');">
                    Demander la suppression de mes données personnelles
                </a>

                <div class="row-group flex-between-center mt-md">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-800">Annuler</a>

                    <button type="submit" class="submit-btn-custom" :disabled="!isGlobalDirty || globalErrors"
                        :style="(!isGlobalDirty || globalErrors) ? 'opacity: 0.5; cursor: not-allowed' :
                        'opacity: 1; cursor: pointer'">
                        Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script type="text/html" id="icon-pencil">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
    </script>

    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('formManager', () => ({
                isGlobalDirty: false,
                globalErrors: false,

                init() {
                    this.$el.addEventListener('field-touched', () => {
                        this.isGlobalDirty = true;
                    });
                    this.$el.addEventListener('field-error', (e) => {
                        this.globalErrors = e.detail;
                    });
                },

                submitForm() {
                    if (this.globalErrors) return;

                    this.$el.querySelectorAll(':disabled').forEach(el => {
                        el.disabled = false;
                    });

                    this.$el.submit();
                }
            }));

            Alpine.data('inputField', () => ({
                editing: false,
                error: null,

                enable() {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.input.focus());
                },

                touch() {
                    this.$dispatch('field-touched');
                },

                validateEmail(val) {
                    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
                    this.error = valid ? null : 'Email invalide';
                    this.$dispatch('field-error', !valid);
                },

                validatePhone(val) {
                    const valid = /^0[0-9]{9}$/.test(val);
                    this.error = valid ? null : 'Doit comporter 10 caractères et commencer par un 0';
                    this.$dispatch('field-error', !valid);
                }
            }));

            Alpine.data('addressField', (initAddr, initCity, initZip) => ({
                editing: false,
                display: initAddr,
                city: initCity,
                zip: initZip,
                results: [],
                error: null,

                enable() {
                    this.editing = true;

                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.$refs.input.focus();
                            this.$refs.input.select();
                        }, 50);
                    });
                },

                touch() {
                    this.$dispatch('field-touched');
                },

                async search() {
                    if (this.display.length < 3) {
                        this.results = [];
                        return;
                    }
                    try {
                        let res = await fetch(
                            `https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(this.display)}&limit=5&autocomplete=1`
                            );
                        let data = await res.json();
                        this.results = data.features;
                    } catch (e) {
                        console.error(e);
                    }
                },

                select(item) {
                    this.display = item.properties.label;
                    this.city = item.properties.city;
                    this.zip = item.properties.postcode;
                    this.results = [];
                    this.touch();
                },

                closeDropdown() {
                    this.results = [];
                }
            }));

            Alpine.data('passwordManager', () => ({
                editing: false,
                p1: '',
                p2: '',
                errorType: null,

                enable() {
                    this.editing = true;
                    this.$nextTick(() => this.$refs.p1input.focus());
                },

                check() {
                    this.$dispatch('field-touched');

                    if (this.p1.length > 0) {
                        if (this.p1.length < 8) {
                            this.errorType = 'length';
                            this.$dispatch('field-error', true);
                            return;
                        }

                        if (this.p1 !== this.p2) {
                            this.errorType = 'match';
                            this.$dispatch('field-error', true);
                            return;
                        }

                        this.errorType = null;
                        this.$dispatch('field-error', false);
                    } else {
                        this.errorType = null;
                        this.$dispatch('field-error', false);
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const imagePreview = document.getElementById('imagePreview');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                }
            });
        });


        const realFileBtn = document.getElementById("fileInput");
        const customBtn = document.getElementById("customSelectBtn");
        const customTxt = document.getElementById("fileChosen");

        if (customBtn && realFileBtn) {
            customBtn.addEventListener("click", function(e) {
                e.preventDefault();
                realFileBtn.click();
            });

            realFileBtn.addEventListener("change", function() {
                if (realFileBtn.files.length > 0) {
                    customTxt.textContent = realFileBtn.files[0].name;
                } else {
                    customTxt.textContent = "Aucune image sélectionnée";
                }
            });
        }

        const realFileBtnPDF = document.getElementById("realFileInputPDF");
        const customBtnPDF = document.getElementById("customSelectBtnPDF");
        const customTxtPDF = document.getElementById("fileChosenPDF");

        if (customBtnPDF && realFileBtnPDF) {
            customBtnPDF.addEventListener("click", function(e) {
                e.preventDefault();
                realFileBtnPDF.click();
            });

            realFileBtnPDF.addEventListener("change", function() {
                if (realFileBtnPDF.files.length > 0) {
                    customTxtPDF.textContent = realFileBtnPDF.files[0].name;
                    realFileBtnPDF.dispatchEvent(new CustomEvent('field-touched', {
                        bubbles: true
                    }));
                } else {
                    customTxtPDF.textContent = "Aucun fichier sélectionné";
                }
            });
        }
    </script>
    <style>
        .security-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background-color: white;
            transition: transform 0.2s;
            overflow: hidden;
        }

        .security-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .security-header {
            background: linear-gradient(to right, #fff5ec, #ffffff);
            padding: 20px 30px;
            border-bottom: 1px solid #ffe6d4;
            color: #ff6e14;
        }

        .security-body {
            padding: 30px;
        }

        .status-active {
            background-color: #e6f9ed;
            color: #28a745;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            display: inline-block;
            font-size: 0.95em;
        }

        .btn-action-orange {
            background-color: transparent;
            border: 2px solid #ff6e14;
            color: #ff6e14;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-action-orange:hover {
            background-color: #ff6e14;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(255, 110, 20, 0.2);
        }

        .btn-danger-link {
            color: #dc3545;
            background: none;
            border: 1px solid #f1f1f1;
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.85em;
            margin-top: 10px;
            cursor: pointer;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-danger-link:hover {
            background-color: #fff5f5;
            border-color: #dc3545;
        }
    </style>
    @if (auth()->user()->google2fa_secret)
        <form id="form-desactiver-2fa" action="{{ route('2fa.disable') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endif
@endsection
