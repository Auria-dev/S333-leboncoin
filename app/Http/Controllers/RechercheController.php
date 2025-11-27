<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Date;
use App\Models\Calendrier;

use Illuminate\Support\Facades\Schema;

class RechercheController extends Controller {
	function index() {
		return view("form-recherche");
	}

	function results(Request  $request) {
		// $request->get("search");

// 		 // Doc : Eloquent

// 		 $annonces = Annonce::all(); // Objet de type "Collection"
// 		 $filteredAnnonces = $annonces->filter(function (Annonce $annonce, int $key) {
// 		       global $request;
// // 		       return strtolower($annonce->ville->nomville) == strtolower($request->get("search"));
// 		       return levenshtein(strtolower($annonce->ville->nomville), strtolower($request->get("search"))) < 3;
// 		 }); 

		 /*
		 $filteredAnnonces = Annonce::where('idville', 1)
		 	 ->orderBy('nomville')
			 ->limit(10)
			 ->get();
		*/

		//  return view ("resultats-recherche", ['annonces'=>$filteredAnnonces ]);

		$annonces = Annonce::with('ville')->get();
		$filteredAnnonces = $annonces->filter(function(Annonce $a) use ($request) {
			return levenshtein(
				strtolower($a->ville->nomville),
				strtolower($request->get("search"))
			) < 3;
		});

		$search = $request->get("search");
		$datedebut = $request->get("datedebut");
		$datefin = $request->get("datefin");

		if ($datedebut || $datefin) {
			$cal_libre = Calendrier::whereHas('date', function($q) use ($datedebut, $datefin) {
				if ($datedebut) $q->where('date', '>=', $datedebut);
				if ($datefin)   $q->where('date', '<=', $datefin);
			})->where('code_dispo', true)->pluck('idannonce')->toArray();

			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $a) use ($cal_libre) {
				return in_array($a->idannonce, $cal_libre);
			});
		}

		$types = TypeHebergement::all();

		if ($request->filled('filtreTypeHebergement')) {
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($request) {
				return $annonce->type_hebergement->nom_type_hebergement === $request->get("filtreTypeHebergement");
			});
		}

		return view ("resultats-recherche", [
			'ville' => $request->get("search"),
			'typeSelectionner' => $request->get("filtreTypeHebergement"),
			'annonces' => $filteredAnnonces,
			'types' => $types
		]);
	}
}
