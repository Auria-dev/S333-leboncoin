<?php

namespace App\Http\Controllers;

use App\Models\TypePaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Providers\RouteServiceProvider;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Favoris;
use App\Models\Calendrier;
use App\Models\Particulier;
use App\Models\Photo;
use App\Models\Equipement;
use App\Models\Service;
use App\Models\AnnonceSimilaire;
use Carbon\Carbon;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;


use App\Services\GeoapifyService;

class AnnonceController extends Controller
{

    protected $geoService;

    public function __construct(GeoapifyService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function view($id)
    {
        $iduser = -1;

        if (Auth::check()) {
            $iduser = auth()->user()->idutilisateur;
        }

        $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $id)
            ->exists();

        $disponibilites = Calendrier::where('idannonce', $id)
            ->whereHas('date', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            })
            ->with('date')
            ->get();

        $dispoMap = [];

        foreach ($disponibilites as $cal) {
            if ($cal->date) {
                $dateString = \Carbon\Carbon::parse($cal->date->date)->format('Y-m-d');

                $dispoMap[$dateString] = [
                    'dispo' => (bool) $cal->code_dispo,
                ];
            }
        }

        return view("detail-annonce", [
            'annonce' => Annonce::findOrFail($id),
            'annonceAsArray' => collect([Annonce::findOrFail($id)]),
            'isFav' => $exists,
            'dispoJson' => json_encode($dispoMap)
        ]);
    }

    public function addFav($idannonce)
    {
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
            'id' => $idannonce,
            'isFav' => $exists
        ]);
    }

    function afficher_form()
    {
        $types = TypeHebergement::all();
        $equipements = Equipement::all();
        $services = Service::all();
        return view("ajouter-annonce", ['types' => $types, 'equipements' => $equipements, 'services' => $services]);
    }

    function ajouter_annonce(Request $req)
    {

        if (Auth::check()) {
            $user = auth()->user();
            $iduser = auth()->user()->idutilisateur;
        }

        $typeCompte = $user->getTypeParticulier();
        if ($typeCompte == 'Locataire') {
            Particulier::where('idparticulier', $iduser)->update(['code_particulier' => 2]);
        }
        DB::beginTransaction();
        
        try {

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
                'file' => 'required|array',
                'file*' => 'image|mimes:jpg, png, jpeg|max:2048',
            ]);

            $codeville = Ville::where('nom_ville', $req->ville)->first();
            $type_heb = TypeHebergement::where('nom_type_hebergement', $req->DepotTypeHebergement)->first();
            
            
            $adresseComplete = $req->depot_adresse . ', ' . $req->ville . ', France';
            
            $coordonnees = $this->geoService->geocode($adresseComplete);
            
            $latitude = $coordonnees ? $coordonnees['lat'] : null;
            $longitude = $coordonnees ? $coordonnees['lon'] : null;
            
            $service = Service::where('nom_service', $req->DepotService)->first();
            
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
            
            $num_photo = Photo::where('idannonce', $annonce->idannonce)->count() + 1;
            if ($req->hasFile('file')) {
                $manager = new ImageManager(new Driver());
                foreach ($req->file('file') as $file) {
                    $fileName = 'photo_annonce_' . $annonce->idannonce . '_' . $num_photo . '.jpg';
                    $fileNameDB = '/images/photo_annonce_' . $annonce->idannonce . '_' . $num_photo . '.jpg';
                    $imgDestination = public_path('images');
                    $url = asset('images/' . $fileName);
                    
                    $img = $manager->read($file);
                    $img->scaleDown(width: 1000, height: 1000);
                    $img->toJpeg(90)->save($imgDestination . '/' . $fileName);
                    //$imgResized->move(public_path('images'), $fileName);
                    
                    $photo = Photo::create([
                        'idannonce' => $annonce->idannonce,
                        'nomphoto' => $fileNameDB,
                        'legende' => null,
                    ]);
                    
                    $num_photo += 1;
                }
            }
            else {
                $photo = Photo::create([
                    'idannonce' => $annonce->idannonce,
                    'nomphoto' => "/images/photo-annonce.jpg",
                    'legende' => null,
                ]);
            }
          
            $calendrier = DB::table('calendrier')->insertUsing(
                ['iddate', 'idannonce', 'idutilisateur', 'code_dispo'],
                DB::table('date as d')
               ->crossJoin('annonce as a')
                ->where('a.idannonce', $annonce->idannonce)
                ->select(
                    'd.iddate', 'a.idannonce',
                    DB::raw('NULL as idutilisateur'),
                    DB::raw('TRUE as code_dispo'))
            );
                
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
            
            $similaires = Annonce::whereHas('ville.departement', function ($query) use ($annonce) {
                $query->where('iddepartement', $annonce->ville->departement->iddepartement);
            })->where('idtypehebergement', $annonce->idtypehebergement)
              ->where('nb_personnes_max', '>=', $annonce->nb_personnes_max)
              ->where('idannonce', '!=', $annonce->idannonce)
              ->get();
                
            
            foreach ($similaires as $s) {
                $similaire = AnnonceSimilaire::create([
                    'idannonce' => $annonce->idannonce,
                    'idsimilaire' => $s->idannonce,
                ]);
            }
        } catch (Exception $e) {
            // TODO: find a way to log serverside any errors
            return redirect()->back()->withErrors('error', "Impossible de créer l'annonce");
        } 

        DB::commit();
        return redirect()->route('profile')->with('success', 'Annonce créée avec succès');
    }

    function view_reserver(Request $req, $idannonce) {
        $annonce = Annonce::findOrFail($idannonce);

        if (!$annonce || !$req->start_date || !$req->end_date) {
            return redirect()->back()->withErrors(['error' => "Impossible de trouver l'annonce ou les dates"]);
        }

        return view("reserver-annonce", [
            'annonce' => $annonce,
            'idannonce' => $idannonce,
            'date_debut_resa' => $req->start_date,
            'date_fin_resa' => $req->end_date
        ]);
    }

    function reserver(Request $req) {
       
        $req->validate([
            'idannonce' => 'required|integer|exists:annonce,idannonce',
            'date_debut_resa' => 'required|date',
            'date_fin_resa' => 'required|date|after:date_debut_resa',
            'idutilisateur' => 'required|integer|exists:utilisateur,idutilisateur',
            'telephone' => 'required|digits:10',
            'carte_id' => 'required',
        ]);

       
        $user = auth()->user();
        $idCarteUtilisee = null;

        try {
            DB::beginTransaction();

            if ($req->carte_id === 'new') {
                $req->merge([
                    'numcarte' => str_replace(' ', '', $req->input('numcarte'))
                ]);

                $req->validate([
                    'numcarte' => 'required|numeric|digits_between:15,16',
                    'dateexpiration' => 'required|string|size:5',
                    'titulairecarte' => 'required|string',
                    // 'cvv' => 'required' // We verify presence but DO NOT STORE
                ]);
       
                $cleanNum = $req->numcarte;
                $parts = explode('/', $req->dateexpiration);
                $expireDate = Carbon::createFromDate('20' . $parts[1], $parts[0], 1)->toDateString();
                $isSaved = $req->has('est_sauvegardee') ? true : false;

                $idCarteUtilisee = DB::table('carte_bancaire')->insertGetId([
                    'idutilisateur' => $user->idutilisateur,
                    'titulairecarte' => $req->titulairecarte,
                    'numcarte' => $cleanNum, // TODO: encrypt this
                    'dateexpiration' => $expireDate,
                    'est_sauvegardee' => $isSaved
                ], 'idcartebancaire');

            } else {

                $card = DB::table('carte_bancaire')
                    ->where('idcartebancaire', $req->carte_id)
                    ->where('idutilisateur', $user->idutilisateur)
                    ->first();

                // TODO: validate CVV here

                if (!$card) {
                    return back()->withErrors(['carte_id' => 'Carte invalide.']);
                }
                $idCarteUtilisee = $card->idcartebancaire;
            }

            $start = Carbon::parse($req->date_debut_resa);
            $end = Carbon::parse($req->date_fin_resa);
            $nb_nuits = $start->diffInDays($end);

            $idReservation = DB::table('reservation')->insertGetId([
                'idannonce' => $req->idannonce,
                'idlocataire' => $user->idutilisateur,
                'idtypepaiement' => $req->typepaiement,
                'statut_reservation' => 'en attente',
                'date_debut_resa' => $req->date_debut_resa,
                'date_fin_resa' => $req->date_fin_resa,
                'date_demande' => now(),
               
                'nb_nuits' => $nb_nuits,
                'montant_total' => $req->total,
                'frais_services' => $req->frais_service,
                'taxe_sejour' => $req->taxe_sejour,
               
                'nb_adultes' => $req->nb_adultes,
                'nb_enfants' => $req->nb_enfants,
                'nb_bebes' => $req->nb_bebes,
                'nb_animaux' => $req->nb_animaux,
               
                // 'telephone_contact' => $req->telephone
            ], 'idreservation');


            DB::table('paiement')->insert([
                'idreservation' => $idReservation,
                'idcartebancaire' => $idCarteUtilisee,
                'montant_paiement' => $req->total,
                'date_paiement' => now(),
                'statut_paiement' => 'Succès',
                'ref_transaction' => 'TXN-' . strtoupper(uniqid())
            ]);

            if (empty($user->telephone)) {
                DB::table('utilisateur')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['telephone' => $req->telephone]);
            }

            DB::commit();

            return redirect()->route('profile')
                ->with('success', 'Réservation envoyée ! Le paiement est autorisé et sera débité à la validation.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Une erreur est survenue lors du paiement: " . $e->getMessage()]);
        }
    }
}