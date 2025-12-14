<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avis;

class ServiceImmoController extends Controller
{
    public function indexAvis()
    {
        $avisEnAttente = Avis::where('statut_avis', 'en_attente')
            ->with(['utilisateur', 'reservation.annonce'])
            ->orderBy('date_depot', 'asc')
            ->get();

        return view('validation-avis', compact('avisEnAttente'));
    }

    public function updateStatutAvis(Request $request, $id)
    {
        $avis = Avis::findOrFail($id);
        
        $action = $request->input('action'); 

        if ($action === 'valider') {
            $avis->update(['statut_avis' => 'valide']);
            $message = "L'avis a été validé et est maintenant en ligne.";
        } else {
            $avis->update(['statut_avis' => 'refuse']);
            $message = "L'avis a été refusé.";
        }

        return redirect()->back()->with('success', $message);
    }
}