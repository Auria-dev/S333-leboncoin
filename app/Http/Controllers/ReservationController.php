<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function view_modifier($idreservation)
    {
        $reservation = Reservation::findOrFail($idreservation);

        $user = auth()->user();

        if ($reservation->idlocataire != $user->idutilisateur && $reservation->annonce->idproprietaire != $user->idutilisateur) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        return view("voir-reservation", [
            "reservation" => $reservation,
        ]);
    }

    public function modifier_reservation(Request $request, $idreservation) {
        $reservation = Reservation::findOrFail($idreservation);

        // Validate the request data
        $validatedData = $request->validate([
            'nb_adultes' => 'required|integer|min:1',
            'nb_enfants' => 'required|integer|min:0',
            'nb_bebes' => 'required|integer|min:0',
            'nb_animaux' => 'required|integer|min:0'
        ]);

        // dd($reservation, $validatedData);
        $reservation->update($validatedData);
        
        return back()->with('success','Réservation mise à jour avec succès.');
    }

    public function annuler_reservation(Request $request, $idreservation) {
        // check the user is allowed to do this
        $reservation = Reservation::findOrFail($idreservation);
        if (auth()->user()->idutilisateur != $reservation->idlocataire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        $reservation->update(['statut_reservation' => 'annulée']);
        $paiement = paiement::findOrFail($request->paiement);
        $paiement->update(['statut_paiement' => 'annulé']);

         $datesToUpdate = DB::table('date')
         ->whereBetween('date', [$reservation->date_debut_resa, $reservation->date_fin_resa])
         ->pluck('iddate');

        return redirect()->route('profile')->with('success', 'Réservation annulée avec succès.');
    }

    public function accepter_reservation($idreservation) {
        $reservation = Reservation::findOrFail($idreservation);

        if (auth()->user()->idutilisateur != $reservation->annonce->idproprietaire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        $reservation->update(['statut_reservation' => 'validée']);

        $datesToUpdate = DB::table('date')
            ->whereBetween('date', [$reservation->date_debut_resa, $reservation->date_fin_resa])
            ->pluck('iddate');
        
        $datesToUpdate->each(function ($date) use ($reservation) {
            DB::table('calendrier')
                ->where('iddate', $date)
                ->where('idannonce', $reservation->idannonce)
                ->update([
                    'code_dispo' => false,
                    'idutilisateur' => $reservation->idlocataire
                ]);
        });

        return back()->with('success','Réservation acceptée avec succès.');
    }

    public function refuser_reservation($idreservation) {
        $reservation = Reservation::findOrFail($idreservation);
        
        if (auth()->user()->idutilisateur != $reservation->annonce->idproprietaire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        $reservation->update(['statut_reservation'=> 'refusée']);
        return back()->with('success','Réservation refusée avec succès.');
    }

    public function declarer_incident($idreservation) {
        $reservation = Reservation::findOrFail($idreservation);
        return view("declarer-incident", [
            'reservation' => $reservation,
        ]); 
    }

    public function save_incident(Request $req) {
        $req->validate(['desc_incident' => 'required|string']);
        $id = $req->idresa;
        $incident = Incident::create([
            'idreservation' => $id,
            'description_incident' => $req->desc_incident,
            'date_signalement' => now(),
            'reponse_incident' => null,
            'statut_incident' => "déclaré",
        ]);

        
        return redirect()->route('profile')->with('success','Incident déclaré, nous vous recontacterons.');
    }
}
