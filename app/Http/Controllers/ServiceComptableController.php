<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Utilisateur;
use App\Models\Incident;
use App\Models\Reservation;
use App\Models\Paiement;
use App\Models\Reversement;

class ServiceComptableController extends Controller
{
    public function view_gerer_incident()
    {
        $user = Auth::user();
        if (!$user->administrateur) 
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette page.');

        $incidents = Incident::orderBy('date_signalement', 'desc')->where('statut_incident', 'clos')->get();
        return view('gerer-incident-remboursement', ['incidents' => $incidents]);
    }

    public function save_remboursement_incident(Request $req) 
    {
        $incidents = $req->incidents;
        
        if($incidents) {
            foreach ($incidents as $idincident => $value) {
                $incident = Incident::where('idincident', $idincident)->first();
                if(!empty($value)) {
                    Reservation::where("idreservation", $incident->idreservation)->update(["statut_reservation" => $value]);
                    Paiement::where("idreservation", $incident->idreservation)->update(["statut_paiement" => 'Remboursé']);
                    Incident::where('idincident', $idincident)->update(["statut_incident" => 'remboursé']);

                    $paiements = $incident->reservation->paiement;
                    $montant_reverse = 0;
                    foreach ($paiements as $paiement) {
                        $montant_reverse += $paiement->montant_paiement;
                    }

                    Reversement::create([
                        'idreservation' => $incident->idreservation,
                        'montant_reverse' => $montant_reverse,
                        'date_reverse' => now(),
                        'idbeneficiaire' => $incident->reservation->idlocataire,
                        'statut_reverse' => 'en cours',
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Remboursement effectué !');
        }
        return redirect()->back()->with('info', 'Aucun incident modifié.');
    }
}
