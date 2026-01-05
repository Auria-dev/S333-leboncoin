<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Utilisateur;
use App\Models\Incident;
use App\Models\Reservation;
use App\Models\Paiement;

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
                    Reservation::where("idreservation", $incident->reservation->idreservation)->update(["statut_reservation" => $value]);
                    Paiement::where("idreservation", $incident->reservation->idreservation)->update(["statut_paiement" => 'Annulé']);
                    Incident::where('idincident', $idincident)->update(["statut_incident" => 'remboursé']);
                }
            }
            return redirect()->back()->with('success', 'Remboursement effectué !');
        }
        return redirect()->back()->with('info', 'Aucun incident modifié.');
    }
}
