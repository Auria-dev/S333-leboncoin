<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Date;
use App\Models\Calendrier;
use App\Models\Critere;
use App\Models\Recherche;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class RechercheController extends Controller {
	function index() {
		return view("form-recherche");
	}

	function results(Request  $request) {
		// Doc : Eloquent
		$annonces = Annonce::with('ville')->where('code_verif', '=', 'acceptée')->get();
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

		if ($request->filled('nbVoyageurs')) {
			$nbVoyageurs = (int) $request->get('nbVoyageurs');
			
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($nbVoyageurs) {
				return $annonce->nb_personnes_max >= $nbVoyageurs;
			});
		}

		if ($request->filled('nbBebes')) {
			$nbBebes = (int) $request->get('nbBebes');
			
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($nbBebes) {
				return $annonce->nb_bebe_max >= $nbBebes;
			});
		}

		if ($request->filled('prixMin')) {
			$prixMin = (float) $request->get('prixMin');
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($prixMin) {
				return $annonce->prix_nuit >= $prixMin;
			});
		}

		if ($request->filled('prixMax')) {
			$prixMax = (float) $request->get('prixMax');
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($prixMax) {
				return $annonce->prix_nuit <= $prixMax;
			});
		}

		if ($request->filled('nbChambres')) {
			$nbChambres = (int) $request->get('nbChambres');
			$filteredAnnonces = $filteredAnnonces->filter(function(Annonce $annonce) use ($nbChambres) {
				return $annonce->nombre_chambre >= $nbChambres;
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
			'types' => $types,
			'search' => $request->get('search'),
			'nbVoyageurs' => $request->get('nbVoyageurs'),
			'prixMin' => $request->get('prixMin'),
			'prixMax' => $request->get('prixMax'),
			'datedebut' => $request->get('datedebut'),
			'datefin' => $request->get('datefin'),
			'filtreTypeHebergement' => $request->get('filtreTypeHebergement'),
		]);
	}

	public function sauvegarderRecherche(Request $request) {

		if (Auth::check()) {
    		$iduser = auth()->user()->idutilisateur;
		  
		  	$dataToInsert = [
			  'date_recherche'       => now(),
			  'nb_voyageurs'         => $request->input('nbVoyageurs'),
			  'prix_min'             => $request->input('prixMin'),
			  'prix_max'             => $request->input('prixMax'),
			  'mot_clef'             => $request->input('search'),
			  'date_debut_recherche' => $request->input('datedebut'),
			  'date_fin_recherche'   => $request->input('datefin'),
			  'type_hebergement'    => $request->input('filtreTypeHebergement'),
			];
			
			$critere = Critere::create($dataToInsert);
			
			$idcritere = $critere->idcritere;

			$rechercheData = [
			  'idutilisateur' => $iduser,
			  'idcritere'     => $idcritere,
			  'titre_recherche' => $request->input('nom_sauvegarde'),
			];

			Recherche::create($rechercheData);
		}
		

		return redirect()->back();

	}

	public function destroy($idcritere) {
        if (Auth::check()) {
            $iduser = auth()->user()->idutilisateur;
            $deleted = Recherche::where('idutilisateur', $iduser)
                                ->where('idcritere', $idcritere)
                                ->delete();
            if ($deleted) {
                Critere::where('idcritere', $idcritere)->delete();
            }
        }
        return redirect()->back();
    }
}
