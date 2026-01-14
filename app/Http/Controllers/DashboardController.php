<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
 use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

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

        Mail::raw('test email from smtp2go', function ($message) {
            $message->to('wovif33296@atinjo.com')
                    ->subject('smtp2go test');
        });
 
        return view('dashboard-utilisateur', [
            'utilisateur' => $user
        ]);
    }
}

