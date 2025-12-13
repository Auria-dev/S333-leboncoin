<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import nécessaire pour Auth::user()
 
class DashboardController extends Controller
{
    public function view()
    {
        // 1. Récupérer l'utilisateur connecté
        $user = Auth::user();
 
        /**
         * 2. Pré-chargement des données (Eager Loading)
         * C'est ici qu'on récupère tout ce dont le dashboard a besoin d'un coup.
         * Cela évite les erreurs dans la vue quand on essaie d'accéder à l'avis ou à la ville.
         */
        $user->load([
            // --- Partie LOCATAIRE (Mes réservations) ---
            // On charge la réservation AVEC l'avis lié (pour vérifier le statut) et l'annonce
            'reservation.avis',           
            'reservation.annonce.ville',
            // --- Partie PROPRIÉTAIRE (Mes annonces) ---
            'annonce.photo',
            'annonce.type_hebergement',
            'annonce.ville',
 
            // --- Partie GESTION (Demandes reçues) ---
            // Note: Nécessite la relation 'demandesReservations' dans User.php
            'demandesReservations.annonce',
            'demandesReservations.particulier.utilisateur',
 
            // --- Partie FAVORIS & RECHERCHES ---
            'favoris.photo',
            'favoris.ville',
            'recherche'
        ]);
 
        // 3. On envoie la variable $utilisateur (chargée) à la vue
        return view('dashboard-utilisateur', [
            'utilisateur' => $user
        ]);
    }
}

