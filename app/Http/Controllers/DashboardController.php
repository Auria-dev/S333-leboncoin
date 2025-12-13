<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
 
class DashboardController extends Controller
{
    public function view()
    {

        $user = Auth::user();
 

        $user->load([
            'reservation.avis',           
            'reservation.annonce.ville',
            'annonce.photo',
            'annonce.type_hebergement',
            'annonce.ville',
            'demandesReservations.annonce',
            'demandesReservations.particulier.utilisateur',
            'favoris.photo',
            'favoris.ville',
            'recherche'
        ]);
 
        return view('dashboard-utilisateur', [
            'utilisateur' => $user
        ]);
    }
}

