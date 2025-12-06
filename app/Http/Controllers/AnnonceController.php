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
use App\Models\Photo;
use App\Models\Equipement;
use App\Models\Equipe;
use App\Models\Service;
use App\Models\Propose;

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
    $equipements = Equipement::all();
    $services = Service::all();
    return view("ajouter-annonce",  ['types' => $types, 'equipements' => $equipements, 'services' => $services]);
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

    $service = Service::where('nom_service', $req->DepotService)->first();
  
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

    $num_photo = 1;
    if($req->hasFile('file')) {
      foreach($req->file('file') as $file)	{    
        $filePath = $file->getClientOriginalName();
        $fileName = '/images/photo_annonce_' . $user->idutilisateur . '_' . $filePath . '.jpg';
        // $dbFileName = "/images/" . $fileName;
        $file->move(public_path('images'), $fileName);
        $url = asset('images/'. $fileName);

        $photo = Photo::create([
          'idannonce' => $annonce->idannonce,
          'nomphoto' => $fileName,
          'legende' => null,
        ]);

        $num_photo++;
      }
    }
    else {
      $photo = Photo::create([
          'idannonce' => $annonce->idannonce,
          'nomphoto' => "/images/photo-annonce.jpg",
          'legende' => null,
        ]);
    }

    $nomsEquipements = $req->DepotEquipement;
    if (!empty($nomsEquipements) && is_array($nomsEquipements)) {
      $idsEquipements = Equipement::whereIn('nom_equipement', $nomsEquipements)
        ->pluck('idequipement')
        ->toArray();
      $annonce->equipement()->sync($idsEquipements);
    }

    $nomsServices = $req->DepotService;
    if (!empty($nomsServices) && is_array($nomsServices)) {
      $idsServices = Service::whereIn('nom_service', $nomsServices)
        ->pluck('idservice')
        ->toArray();
      $annonce->service()->sync($idsServices);
    }

    return redirect('/profile');
  }
}
