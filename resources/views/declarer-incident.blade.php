@extends('layout')

@section('title', 'Déclarer un incident')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    
    <h2 id="titre-incident">Veuillez décrire l'incident en détails</h2>
    <div class="center-container">
        <form class="form-ajouter add-annonce-form" method="POST" action="{{ url('reservation/save_incident') }}">
            @csrf  
                <textarea id="desc_incident" name="desc_incident" placeholder="Que s'est-il passé ?" rows="20" required></textarea>
                
            <div class="lightbox-actions">
                <input type="hidden" name="idresa" value="{{  $reservation->idreservation }}">
                <button type="button" class="other-btn" id="btn-cancel-first-ad">Annuler</button>               
                <button type="submit" class="submit-btn">Déclarer</button>
            </div>
        </form>
    </div>  
@endsection
@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {

            const btnCancel = document.getElementById('btn-cancel-first-ad');
            if (btnCancel) {
                btnCancel.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = '/profile';
                });
            }
        });
    </script>
@endpush

