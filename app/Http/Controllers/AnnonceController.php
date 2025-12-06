<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Favoris;
use App\Models\Calendrier;
use App\Models\Particulier;

use App\Services\GeoapifyService;

class AnnonceController extends Controller {


  protected $geoService;

  public function __construct(GeoapifyService $geoService)
  {
      $this->geoService = $geoService;
  }


  public function view($id) {
    $iduser = -1;

    if (Auth::check()) {
        $iduser = auth()->user()->idutilisateur;
    }

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $id)
            ->exists();

    $disponibilites = Calendrier::where('idannonce', $id)
        ->whereHas('date', function($q) {
            $q->where('date', '>=', now()->toDateString());
        })
        ->with('date')
        ->get();

    $dispoMap = [];
    
    foreach($disponibilites as $cal) {
        if ($cal->date) {
            $dateString = \Carbon\Carbon::parse($cal->date->date)->format('Y-m-d');
            
            $dispoMap[$dateString] = [
                'dispo' => (bool)$cal->code_dispo, 
            ];
        }
    }
    
    return view ("detail-annonce", [
        'annonce'   => Annonce::findOrFail($id), 
        'isFav'     => $exists,
        'dispoJson' => json_encode($dispoMap) 
    ]);
  }

  public function addFav($idannonce) {
    $user = auth()->user();
    $iduser = $user->idutilisateur;

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $idannonce)
            ->exists();

    if (!$exists) {
      $res = Favoris::create([
        'idutilisateur' => $iduser,
        'idannonce' => $idannonce
      ]);
    } else {
      Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $idannonce)
            ->delete();
    }
    
    return redirect()->route('annonce', [
      'id'=>$idannonce, 
      'isFav' => $exists 
    ]);
  }

  function afficher_form() {
    $types = TypeHebergement::all();
    return view("ajouter-annonce",  ['types' => $types]);
  }

  function ajouter_annonce(Request $req) {

    if (Auth::check()) {
      $user = auth()->user();
      $iduser = auth()->user()->idutilisateur;
    }
    $typeCompte = $user->getTypeParticulier(); 
    if($typeCompte == 'Locataire') {
      Particulier::where('idparticulier', $iduser)->update(['code_particulier' => 2]);
    }
    // OPTIONNEL, vérif que il n'a aucune réservation, et puis fait le juste passer en "Propietaire"


    $req->validate([
            'titre' => 'required|string|max:128',
            'depot_adresse' => 'required|string',   
            'ville' => 'required|string',
            'DepotTypeHebergement' => 'required',
            'prix_nuit' => 'required|numeric|min:0.01',
            'nb_nuits' => 'required|integer|min:1',
            'nb_pers' => 'required|integer|min:1',
            'nb_bebes' => 'nullable|integer|min:0', 
            'nb_animaux' => 'nullable|integer|min:0',
            'nb_chambres' => 'required|integer|min:1',
            'heure_arr' => 'required|date_format:H:i',
            'heure_dep' => 'required|date_format:H:i',
            'desc' => 'required|string|max:2000',
    ]);

    $codeville = Ville::where('nom_ville', $req->ville)->first();
    $type_heb = TypeHebergement::where('nom_type_hebergement', $req->DepotTypeHebergement)->first();


    $adresseComplete = $req->depot_adresse . ', ' . $req->ville . ', France'; 
        
    $coordonnees = $this->geoService->geocode($adresseComplete);

    $latitude = $coordonnees ? $coordonnees['lat'] : null;
    $longitude = $coordonnees ? $coordonnees['lon'] : null;
  
    // this says
    $annonce = Annonce::create([
        'idtypehebergement' => $type_heb->idtypehebergement,
        'idproprietaire' => $iduser,
        'idville' => $codeville->idville,
        'titre_annonce' => $req->titre,
        'prix_nuit' => $req->prix_nuit,
        'nb_nuit_min' => $req->nb_nuits,
        'nb_bebe_max' => $req->nb_bebes,
        'nb_personnes_max' => $req->nb_pers,
        'nb_animaux_max' => $req->nb_animaux,
        'adresse_annonce' => $req->depot_adresse,
        'description_annonce' => $req->desc,
        'date_publication' => now(),
        'heure_arrivee' => $req->heure_arr,
        'heure_depart' => $req->heure_dep,
        'nombre_chambre' => $req->nb_chambres,
        'longitude' => $longitude,
        'latitude' => $latitude,
    ]);

    return redirect(RouteServiceProvider::HOME);
  }
}
