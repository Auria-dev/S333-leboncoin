<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\Utilisateur;
use App\Models\Avis;


class ProprietaireController extends Controller
{
    public function view($id) {

        $proprietaire = Utilisateur::findOrFail($id);
        $annonces = $proprietaire->annonce;
        $sommeGlobaleNotes = 0;
        $sommeGlobaleAvis = 0;
        $moyenneGlobale = 0;
        foreach ($annonces as $annonce) {
            $res = $annonce->moyenneAvisParAnnonce();
            $sommeGlobaleNotes += $res['sommeNotes'];
            $sommeGlobaleAvis += $res['nbAvis'];
        }
        if($sommeGlobaleAvis > 0) {
            $moyenneGlobale = $sommeGlobaleNotes / $sommeGlobaleAvis;
        }
        return view ("detail-proprietaire", [ 'proprietaire' /* c'est jeankevin! */=>$proprietaire, 'moyenneAvis'=> round($moyenneGlobale, 2), 'nbAvis'=>$sommeGlobaleAvis]); 
    }
}
