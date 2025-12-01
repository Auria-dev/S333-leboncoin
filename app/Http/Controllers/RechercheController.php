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
		// Doc : Eloquent
		$annonces = Annonce::with('ville')->get();
		$filteredAnnonces = $annonces->filter(function(Annonce $a) use ($request) {
			$ville = levenshtein(
				strtolower($a->ville->nom_ville),
				strtolower($request->get("search"))
			) < 3;

			$dep = levenshtein(
				strtolower($a->ville->departement->nom_departement),
				strtolower($request->get("search"))
			) < 3;

			$region = levenshtein(
				strtolower($a->ville->departement->region->nom_region),
				strtolower($request->get("search"))
			) < 3;

			return $ville || $dep || $region;
		});

		$search = $request->get("search");
		$datedebut = $request->get("datedebut");
		$datefin = $request->get("datefin");

		if ($datedebut && $datefin) {
			$debut = \Carbon\Carbon::parse($datedebut);
        	$fin = \Carbon\Carbon::parse($datefin);
			$jours_requis = $debut->diffInDays($fin) + 1;
			$annonces_possibles = Calendrier::where('code_dispo', true)
				->whereHas('date', function($q) use ($datedebut, $datefin) {
					$q->whereBetween('date', [$datedebut, $datefin]);
				})->select('idannonce')
				->groupBy('idannonce')
				->havingRaw('COUNT(*) = ?', [$jours_requis]) // havingRaw = raw SQL having clause
				->pluck('idannonce')
				->toArray();
				$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $a) use ($annonces_possibles) {
					return in_array($a->idannonce, $annonces_possibles);
				});
		} else if ($datedebut || $datefin) {
			$cal_libre = Calendrier::whereHas('date', function($q) use ($datedebut, $datefin) {
				if ($datedebut) $q->where('date', '>=', $datedebut);
				if ($datefin)   $q->where('date', '<=', $datefin);
			})->where('code_dispo', true)
			->pluck('idannonce')
			->toArray();
			
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
			'annonces' => $filteredAnnonces,
			'types' => $types
		]);
	}
}
