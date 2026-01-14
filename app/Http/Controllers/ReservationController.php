<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Incident;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Notifications\CustomNotification;

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

    public function modifier_reservation(Request $req, $idreservation) {
        $reservation = Reservation::findOrFail($idreservation);

        // 1. Basic Validation of Counts
        $req->validate([
            'nb_adultes' => 'required|integer|min:1',
            'nb_enfants' => 'required|integer|min:0',
            'nb_bebes'   => 'required|integer|min:0',
            'nb_animaux' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $taxRate = $reservation->annonce->ville->taxe_sejour;
            $newTax = ($req->nb_adultes + $req->nb_enfants) * $taxRate;

            $basePrice = $reservation->montant_total - $reservation->taxe_sejour;
            
            $newTotal = $basePrice + $newTax;
            $priceDifference = $newTotal - $reservation->montant_total;

            if ($priceDifference > 0.01) {
                
                $req->validate([
                    'carte_id' => 'required',
                ]);

                $user = auth()->user();
                $idCarteUtilisee = null;

                if ($req->carte_id === 'new') {
                    $req->merge(['numcarte' => str_replace(' ', '', $req->input('numcarte'))]);

                    $req->validate([
                        'titulairecarte' => 'required|string',
                        'numcarte'       => 'required|numeric|digits_between:15,16',
                        'dateexpiration' => 'required|string|size:5', // Format MM/YY
                        'cvv'            => 'required|numeric|digits_between:3,4'
                    ]);

                    $cleanNum = $req->numcarte;
                    
                    $parts = explode('/', $req->dateexpiration);
                    $expireDate = Carbon::createFromDate('20' . $parts[1], $parts[0], 1)->endOfMonth()->toDateString();
                    
                    $isSaved = $req->has('est_sauvegardee') ? true : false;

                    $idCarteUtilisee = DB::table('carte_bancaire')->insertGetId([
                        'idutilisateur'   => $user->idutilisateur,
                        'titulairecarte'  => $req->titulairecarte,
                        'numcarte'        => encrypt($cleanNum),
                        'dateexpiration'  => $expireDate,
                        'est_sauvegardee' => $isSaved
                    ], 'idcartebancaire');

                } 
                else {
                    $card = DB::table('carte_bancaire')
                        ->where('idcartebancaire', $req->carte_id)
                        ->where('idutilisateur', $user->idutilisateur)
                        ->first();

                    if (!$card) {
                        throw new \Exception('Carte bancaire invalide ou introuvable.');
                    }

                    $req->validate([
                        'cvv_verify_' . $req->carte_id => 'required|numeric'
                    ], [
                        'cvv_verify_' . $req->carte_id . '.required' => 'Le CVV est requis pour confirmer la carte.'
                    ]);

                    $idCarteUtilisee = $card->idcartebancaire;
                }

                DB::table('paiement')->insert([
                    'idreservation'    => $reservation->idreservation,
                    'idcartebancaire'  => $idCarteUtilisee,
                    'montant_paiement' => $priceDifference,
                    'date_paiement'    => now(),
                    'statut_paiement'  => 'Succès',
                    'ref_transaction'  => 'MAJ-' . strtoupper(uniqid())
                ]);
            }

            $reservation->nb_adultes    = $req->nb_adultes;
            $reservation->nb_enfants    = $req->nb_enfants;
            $reservation->nb_bebes      = $req->nb_bebes;
            $reservation->nb_animaux    = $req->nb_animaux;
            $reservation->taxe_sejour   = $newTax;
            $reservation->montant_total = $newTotal;
            
            $reservation->save();

            DB::commit();

            $reservation->annonce->proprietaire->notify(new CustomNotification(
                "La réservation #{$reservation->idreservation} de votre annonce a été mise à jour.",
                url('reservation/'.strval($reservation->idReservation))
            ));

            return back()->with('success', 'Réservation mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur de mise à jour: " . $e->getMessage()])->withInput();
        }
    }

    public function annuler_reservation(Request $request, $idreservation) {
        // check the user is allowed to do this
        $reservation = Reservation::findOrFail($idreservation);
        if (auth()->user()->idutilisateur != $reservation->idlocataire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }
        
        $reservation->update(['statut_reservation' => 'annulée']);
        $paiement = Paiement::where('idreservation', intval($idreservation))->firstOrFail();
        $paiement->update(['statut_paiement' => 'annulé']);

        $datesToUpdate = DB::table('date')->whereBetween('date', [$reservation->date_debut_resa, $reservation->date_fin_resa])->pluck('iddate');

        $reservation->annonce->utilisateur->notify(new CustomNotification(
            "La réservation #{$reservation->idreservation} de votre annonce a été annulée par le locataire.",
            url('reservation/'.strval($reservation->idReservation))
        ));

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

        $reservation->particulier->utilisateur->notify(new CustomNotification(
            "Votre réservation #{$reservation->idreservation} a été acceptée !",
            url('reservation/'.strval($reservation->idReservation))
        ));


        return back()->with('success','Réservation acceptée avec succès.');
    }

    public function refuser_reservation($idreservation) {
        $reservation = Reservation::findOrFail($idreservation);
        
        if (auth()->user()->idutilisateur != $reservation->annonce->idproprietaire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        $reservation->update(['statut_reservation'=> 'refusée']);

        $reservation->particulier->utilisateur->notify(new CustomNotification(
            "Votre réservation #{$reservation->idreservation} a été refusée.",
            url('reservation/'.strval($reservation->idReservation))
        ));

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

        $reservation = Reservation::findOrFailt($id);

        $reservation->annonce->proprietaire->notify(new CustomNotification(
            "Un incident a été déclaré pour la réservation #{$reservation->idreservation}.",
            url('reservation/'.strval($reservation->idReservation))
        ));
        
        return redirect()->route('profile')->with('success','Incident déclaré, nous vous recontacterons.');
    }

    public function clore_incident(Request $req) {
        $req->validate(['justif_incident' => 'nullable|string']);
        $id = $req->idincident;
        $incident = Incident::findOrFail($id);
        $incident->update(['statut_incident' => "clos"]);

        if($req->has('justif_incident') && $req->justif_incident != null) {
            $incident->update(['reponse_incident' => $req->justif_incident]);
        }

        $incident->reservation->particulier->utilisateur->notify(new CustomNotification(
            "Votre incident #{$incident->idincident} a été clos.",
            route('voir_reservation', $incident->idreservation)
        ));

        return redirect()->route('profile')->with('success','Incident clos.');
    }

    public function justifier_incident(Request $req) {
        $req->validate(['justif_incident' => 'required|string']);
        $id = $req->idincident;
        $incident = Incident::findOrFail($id);
        $incident->update(['reponse_incident' => $req->justif_incident]);

        $incident->reservation->particulier->utilisateur->notify(new CustomNotification(
            "Votre incident #{$incident->idincident} a été justifié.",
            route('voir_reservation', $incident->idreservation)
        ));

        return redirect()->route('profile')->with('success','Incident justifié.');
    }
}