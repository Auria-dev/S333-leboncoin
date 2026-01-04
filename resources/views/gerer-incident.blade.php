@extends('layout')

@section('title', 'Gérer les remboursements')

@section('content')
<form id="form-statut-incident" method="POST" action="{{ url('enregistrer_remboursement_incident') }}">
    @csrf
    <table>
        <thead>
            <tr>
                <th>Locataire</th>
                <th>Incident</th>
                <th>Propriétaire</th>
                <th>Réponse</th>
                <th>Remboursement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidents as $incident)
            <tr>
                <td>
                    <p>{{ $incident->reservation->particulier->Utilisateur->prenom_utilisateur }} {{ $incident->reservation->particulier->Utilisateur->nom_utilisateur }}</p>
                </td>
                <td>
                    <p> {{$incident->description_incident}}
                </td>
                <td>
                    <p>{{ $incident->reservation->annonce->utilisateur->prenom_utilisateur }} {{ $incident->reservation->annonce->utilisateur->nom_utilisateur }}</p>
                </td>
                <td>
                    @if($incident->reponse_incident === null)
                        <p>Aucune réponse</p>
                    @else($annonce->code_verif === "acceptée")
                        <p>{{$incident->reponse_incident}}</p>
                    @endif
                </td>
                <td>
                    <div class="form-group" style="margin-top: 10px">
                        <div>
                            <label class="pill-label">
                                <input type="checkbox" 
                                    name="incidents[{{ $incident->idincident }}]"
                                    value="remboursée">
                                <span class="pill-content">
                                    <span class="icon-wrapper">
                                        <span class="checkmark-draw"></span>
                                    </span>
                                    <span>Rembourser</span>
                                </span>
                            </label>
                        </div>
                    </div>                                           
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-md" style="position: fixed; top: 50px; left: 10px;">
    <button type="submit" id="btn-enregistrer" class="submit-btn"
        style="
            width: 40px;           
            height: 40px;          
            display: flex;         
            justify-content: center; 
            align-items: center;     
            padding: 0;            
        ">
        <svg width="20px" height="20px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(-152.000000, -515.000000)" fill="currentColor"> 
                <path d="M171,525 C171.552,525 172,524.553 172,524 L172,520 C172,519.447 171.552,519 171,519 C170.448,519 170,519.447 170,520 L170,524 C170,524.553 170.448,525 171,525 Z M182,543 C182,544.104 181.104,545 180,545 L156,545 C154.896,545 154,544.104 154,543 L154,519 C154,517.896 154.896,517 156,517 L158,517 L158,527 C158,528.104 158.896,529 160,529 L176,529 C177.104,529 178,528.104 178,527 L178,517 L180,517 C181.104,517 182,517.896 182,519 L182,543 L182,543 Z M160,517 L176,517 L176,526 C176,526.553 175.552,527 175,527 L161,527 C160.448,527 160,526.553 160,526 L160,517 L160,517 Z M180,515 L156,515 C153.791,515 152,516.791 152,519 L152,543 C152,545.209 153.791,547 156,547 L180,547 C182.209,547 184,545.209 184,543 L184,519 C184,516.791 182.209,515 180,515 Z"></path>
            </g>
        </svg>
    </button>
</div>
</form>
@endsection