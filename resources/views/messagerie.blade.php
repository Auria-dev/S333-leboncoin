@extends('layout')

@section('title', 'Messagerie')

@section('content')
@php
    use Carbon\Carbon;
    $user = Auth::user();
@endphp
    <div id="messagerie">
        <section id="msg_left">
            <h1>Contacts</h1>
            <div id="contacts_list">
                @forelse($contacts as $contact)
                    @php
                        $otherUser = $contact->other_user;
                        $isActive = $selectedContact && $selectedContact->id === $contact->id;
                    @endphp

                    <a href="{{ route('messagerie', ['id' => $contact->id]) }}" style="text-decoration: none; color: inherit;">
                        <article class="contact {{ $isActive ? 'selected' : '' }}">
                            <p>{{ $otherUser->nom_utilisateur . ' ' . $otherUser->prenom_utilisateur ?? 'Inconnu' }}</p>
                            
                            <span>Cliquez pour voir la conversation</span>
                        </article>
                    </a>
                @empty
                    <p style="padding: 20px;">Aucun contact trouvé.</p>
                @endforelse
            </div>
        </section>
        <section id="msg_right">
            <div id="msg_history">
                @if($selectedContact)
                    @forelse($messages as $msg)
                        <article class="msg">
                            <span class="msg_header">
                                <img src="{{ $msg->expediteur->photo_profil ?? asset('images/photo-profil.jpg') }}" alt="Avatar" style="width:30px; height:30px; border-radius:50%; object-fit:cover; margin-right:5px;" />
                                <div>
                                    <p class="msg_author"> {{ $msg->expediteur->nom_utilisateur . ' ' . $msg->expediteur->prenom_utilisateur }} </p>
                                    <p class="msg_timestamp"> {{ Carbon::parse($msg->date_envoi)->locale('fr')->isoFormat('HH:mm DD/MM/YYYY') }} </p>
                                </div>
                            </span>
                            <p class="msg_content">{{ $msg->contenu }}</p>
                        </article>
                    @empty
                        <p style="text-align: center; margin-top: 20px;">
                            Aucun message. Dites bonjour !
                        </p>
                    @endforelse
                @else
                    <p style="text-align: center; margin-top: 50px;">
                        Sélectionnez un contact pour commencer.
                    </p>
                @endif
            </div>
            <div id="msg_input">
                @if($selectedContact)
                    <form id="msg_form" action="{{ route('messagerie.envoyer') }}" method="POST">
                        @csrf
                        <input type="hidden" name="contact_id" value="{{ $selectedContact->id }}">
                        
                        <input type="text" name="contenu" placeholder="Écrire un message..." required autocomplete="off" />
                        <button type="submit">Envoyer</button>
                    </form>
                @endif
            </div>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var msgHistory = document.getElementById("msg_history");
            if (msgHistory) {
                msgHistory.scrollTop = msgHistory.scrollHeight;
            }
        });
    </script>

    <style>
        header { display: none; }
        footer { margin-top: 2rem; }
        section { border-radius: 1rem; }
        #messagerie { display: flex; gap: 1rem; height: 80vh; }
        #msg_left {
            width: 20%; padding: 1rem; display: flex; flex-direction: column;
            gap: 1rem; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #msg_right {
            width: 80%; flex-direction: column; display: flex;
            justify-content: space-between; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        #msg_history {
            overflow-y: scroll; padding: 1rem; display: flex;
            flex-direction: column; gap: 1rem;
        }

        #msg_input {}
        #msg_form { display: flex; gap: 0.5rem; padding: 1rem; }
        #msg_form input {
            flex: 1; padding: 0.75rem; border: none; border-radius: 0.5rem;
            background-color: #fff3eeff;
        }
        #msg_form button {
            padding: 0.5rem 1rem; background-color: var(--primary);
            color: white; border: none; border-radius: 0.5rem; cursor: pointer;
        }
        .msg { display: flex; flex-direction: column; }
        .msg_header { display: flex; gap: 0.5rem; }
        .msg_content {
            opacity: 0.9; margin-left: 0.5rem; font-weight: 100 !important;
        }
        .msg_timestamp {
            font-size: 0.8rem; color: gray; align-self: flex-end;
        }
        #contacts_list {
            overflow-y: scroll; display: flex; flex-direction: column;
            flex: 1; gap: 0.5rem;
        }
        #contacts_list a { display: block; }
        .contact {
            display: flex; flex-direction: column; gap: 0.25rem; padding: 0.5rem;
            border-radius: 0.5rem; border-bottom: 1px solid var(--primary-light);
            cursor: pointer;
        }
        .contact p { margin: 0; font-weight: bold; }
        .contact span { font-size: 0.9rem; color: gray; opacity: 0.8; }
        .contact:hover { background-color: var(--primary-light); }
        .contact.selected { background-color: #ffeae4ff; }
    </style>
@endsection