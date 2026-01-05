<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Incident;

class IncidentController extends Controller
{

    public function view_gerer_incidents()
    {

        $user = Auth::user();
        if (!$user->administrateur) { 
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette page.');
        }

        $incidents = Incident::orderBy('date_signalement', 'desc')->get();
        
        return view('gerer-incident', compact('incidents'));
    }

    public function afficher_incidents_attente(Request $request)
    {
        $query = Incident::query();

        if ($request->filled('statut') && $request->statut == 'en attente') {
            $query->whereNotIn('statut_incident', ['sans suite', 'au contentieux']);
        }

        $incidents = $query->orderBy('date_signalement', 'desc')->get();

        return view('gerer-incident', compact('incidents'));
    }

    public function enregistrer_statut_incident(Request $request)
    {
        $statuts = $request->statuts;

        if($statuts) {
            foreach ($statuts as $idIncident => $nouveauStatut) {
                if(!empty($nouveauStatut)) {
                    Incident::where("idincident", $idIncident)
                            ->update(["statut_incident" => $nouveauStatut]);
                }
            }
            return redirect()->back()->with('success', 'Les statuts des incidents ont été mis à jour.');
        }
        return redirect()->back()->with('info', 'Aucun statut modifié.');
    }
}
