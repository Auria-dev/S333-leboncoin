<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;



class RechercheController extends Controller {
	function index() {
		 return view("form-recherche");
	}

	function results(Request  $request) {
		// $request->get("search");

// 		 // Doc : Eloquent

		 $annonces = Annonce::all(); // Objet de type "Collection"
		 $filteredAnnonces = $annonces->filter(function (Annonce $annonce, int $key) {
		       global $request;
// 		       return strtolower($annonce->ville->nomville) == strtolower($request->get("search"));
		       return levenshtein(strtolower($annonce->ville->nomville), strtolower($request->get("search"))) < 3;
		 }); 

		 /*
		 $filteredAnnonces = Annonce::where('idville', 1)
		 	 ->orderBy('nomville')
			 ->limit(10)
			 ->get();
			 */

		 return view ("resultats-recherche", ['annonces'=>$filteredAnnonces ]);
	}
}
