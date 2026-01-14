<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Utilisateur;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\Favoris;
use App\Models\Calendrier;
use App\Models\Particulier;
use App\Models\TypeHebergement;
use App\Models\Photo;
use App\Models\Equipement;
use App\Models\AnnonceSimilaire;
use App\Models\CategorieEquipement;
use App\Models\Service;
use App\Models\Reservation;
use App\Services\GeoapifyService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use GuzzleHttp\Client as GuzzleClient;

class AnnonceController extends Controller
{
    protected $geoService;

    public function __construct(GeoapifyService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function afficher_form()
    {
        $types = TypeHebergement::all();
        $equipements = Equipement::all();
        $services = Service::all();
        return view("ajouter-annonce", ['types' => $types, 'equipements' => $equipements, 'services' => $services]);
    }

    public function ajouter_annonce(Request $req)
    {
        if (Auth::check()) {
            $user = Utilisateur::find(auth()->id());
            $iduser = $user->idutilisateur;
        } else {
            return redirect('login');
        }

        $typeCompte = $user->getTypeParticulier();
        if ($typeCompte == 'Locataire') {
            Particulier::where('idparticulier', $iduser)->update(['code_particulier' => 2]);
        }

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
            'file.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $codeville = Ville::where('nom_ville', $req->ville)->first();
        $type_heb = TypeHebergement::where('nom_type_hebergement', $req->DepotTypeHebergement)->first();

        $adresseComplete = $req->depot_adresse . ', ' . $req->ville . ', France';
        $coordonnees = $this->geoService->geocode($adresseComplete);
        $latitude = $coordonnees ? $coordonnees['lat'] : null;
        $longitude = $coordonnees ? $coordonnees['lon'] : null;

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

                $img = $manager->read($file);
                $img->scaleDown(width: 1000, height: 1000);
                $img->toJpeg(90)->save($imgDestination . '/' . $fileName);

                Photo::create([
                    'idannonce' => $annonce->idannonce,
                    'nomphoto' => $fileNameDB,
                    'legende' => null,
                ]);
                $num_photo++;
            }
        } else {
            Photo::create([
                'idannonce' => $annonce->idannonce,
                'nomphoto' => "/images/photo-annonce.jpg",
                'legende' => null,
            ]);
        }

        DB::table('calendrier')->insertUsing(
            ['iddate', 'idannonce', 'idutilisateur', 'code_dispo'],
            DB::table('date as d')
                ->crossJoin('annonce as a')
                ->where('a.idannonce', $annonce->idannonce)
                ->select(
                    'd.iddate',
                    'a.idannonce',
                    DB::raw('NULL as idutilisateur'),
                    DB::raw('TRUE as code_dispo')
                )
        );

        $nomsEquipements = $req->DepotEquipement;
        if (!empty($nomsEquipements) && is_array($nomsEquipements)) {
            $idsEquipements = Equipement::whereIn('nom_equipement', $nomsEquipements)->pluck('idequipement')->toArray();
            $annonce->equipement()->sync($idsEquipements);
        }

        $nomsServices = $req->DepotService;
        if (!empty($nomsServices) && is_array($nomsServices)) {
            $idsServices = Service::whereIn('nom_service', $nomsServices)->pluck('idservice')->toArray();
            $annonce->service()->sync($idsServices);
        }

        $similaires = Annonce::whereHas('ville.departement', function ($query) use ($annonce) {
            $query->where('iddepartement', $annonce->ville->departement->iddepartement);
        })
        ->where('idtypehebergement', $annonce->idtypehebergement)
        ->where('nb_personnes_max', '>=', $annonce->nb_personnes_max)
        ->where('idannonce', '!=', $annonce->idannonce)
        ->get();

        foreach ($similaires as $s) {
            AnnonceSimilaire::create([
                'idannonce' => $annonce->idannonce,
                'idsimilaire' => $s->idannonce,
            ]);
        }

        if (!$user->telephoneverifie) {
            
            $code = rand(1000, 9999);
            session(['code_sms_temporaire' => $code]);

            $numero = $user->telephone;
            if (substr($numero, 0, 1) == '0') {
                $numero = '33' . substr($numero, 1);
            }

                
            if (!$user->telephone_verifie) {
                if (empty($user->telephone)) {
                    return redirect('/telephone')->with('warning', 'Annonce créée ! Vérifiez votre téléphone.');
                }
                return $this->lancerProcessusVerification($user->telephone);
            }

            return redirect('/profile')->with('success', 'Annonce publiée avec succès !');
        }
    }

    private function lancerProcessusVerification($numero) {
        $user = Auth::user();
        $numeroClean = str_replace([' ', '.', '-', '/'], '', $numero);
        if (substr($numeroClean, 0, 1) == '0') $numeroClean = '+33' . substr($numeroClean, 1);
        elseif (substr($numeroClean, 0, 2) == '33') $numeroClean = '+' . $numeroClean;

        $code = rand(100000, 999999);
        $user->phone_verification_code = $code;
        $user->telephone = $numero; 
        $user->save();

        try {
            $basic  = new Basic('37c460b8', '5FfA4%^7Y47KJ5Tsebid');
            $client = new Client($basic);
            $client->setHttpClient(new GuzzleClient(['verify' => storage_path('cacert.pem')]));

            $client->sms()->send(new SMS($numeroClean, "Lemauvaiscoin", "Votre code de vérification : " . $code));
            return redirect('/telephone')->with('success', 'Annonce créée ! Un code SMS vous a été envoyé.');

        } catch (\Exception $e) {
            return redirect('/telephone')->with('error', 'Erreur SMS : ' . $e->getMessage());
        }
    }

    public function afficherFormVerification() {
        if (Auth::user()->telephone_verifie) return redirect('/profile')->with('success', 'Déjà vérifié.');
        return view('telephone', ['user' => Auth::user()]);
    }

    public function envoyerCodeSms(Request $request) {
        $request->validate(['telephone' => 'required']);
        return $this->lancerProcessusVerification($request->telephone);
    }

    public function traiterVerification(Request $request) {
        $request->validate(['code' => 'required']);
        $user = Auth::user();

        if ($request->code == $user->phone_verification_code) {
            $user->telephone_verifie = true;
            $user->phone_verification_code = null;
            $user->save();
            return redirect('/profile')->with('success', 'Téléphone vérifié !');
        }
        return redirect()->back()->with('error', 'Code incorrect.');
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

        
        $annonce = Annonce::with([
            'photo',           
            'ville',           
            'utilisateur',    
            'type_Hebergement',  
            'avisValides.utilisateur' 
        ])->findOrFail($id);

        return view("detail-annonce", [
            'annonceAsArray' => collect([Annonce::findOrFail($id)]),
            'annonce' => $annonce,
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
            Favoris::create(['idutilisateur' => $iduser, 'idannonce' => $idannonce]);
        } else {
            Favoris::where('idutilisateur', '=', $iduser)
                ->where('idannonce', '=', $idannonce)
                ->delete();
        }

        return redirect()->route('annonce', ['id' => $idannonce, 'isFav' => !$exists]);
    }
    
    public function view_reserver(Request $req, $idannonce) {
        $annonce = Annonce::findOrFail($idannonce);
        if (!$annonce || !$req->start_date || !$req->end_date) {
            return redirect()->back()->withErrors(['error' => 'Annonce or dates not found.']);
        }
        return view("reserver-annonce", [
            'annonce' => $annonce,
            'idannonce' => $idannonce,
            'date_debut_resa' => $req->start_date,
            'date_fin_resa' => $req->end_date
        ]);
    }

    public function reserver(Request $req) {
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
                $req->merge(['numcarte' => str_replace(' ', '', $req->input('numcarte'))]);
                $req->validate([
                    'numcarte' => 'required|numeric|digits_between:15,16',
                    'dateexpiration' => 'required|string|size:5',
                    'titulairecarte' => 'required|string',
                    'cvv' => 'required|numeric'

                ]);
        
                $cleanNum = $req->numcarte;
                $parts = explode('/', $req->dateexpiration);
                $expireDate = Carbon::createFromDate(date("Y")[0] . date("Y")[1] . $parts[1], $parts[0], 1)->toDateString();
                $isSaved = $req->has('est_sauvegardee') ? true : false;

                $idCarteUtilisee = DB::table('carte_bancaire')->insertGetId([
                    'idutilisateur' => $user->idutilisateur,
                    'titulairecarte' => $req->titulairecarte,
                    'numcarte' => encrypt($cleanNum),
                    'dateexpiration' => $expireDate,
                    'est_sauvegardee' => $isSaved
                ], 'idcartebancaire');

            } else {
                $card = DB::table('carte_bancaire')
                    ->where('idcartebancaire', $req->carte_id)
                    ->where('idutilisateur', $user->idutilisateur)
                    ->first();

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
                ->with('success', 'Réservation envoyée !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur paiement: " . $e->getMessage()]);
        }
    }

    public function ajouter_indisponibilite(Request $req)
    {

        $validated = $req->validate([
            'id_annonce' => 'required|integer|exists:annonce,idannonce',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $bool = $req->boolean('action_fermeture');


        $annonce = Annonce::findOrFail($validated['id_annonce']);

        $updatedRows = DB::table('calendrier')
        ->join('date', 'calendrier.iddate', '=', 'date.iddate')
        ->where('calendrier.idannonce', $annonce->idannonce)
        ->where('date.date', '>=', $validated['start_date'])
        ->where('date.date', '<', $validated['end_date'])
        ->where('idutilisateur', null)
        ->update([
            'code_dispo'    => !$bool,
            'idutilisateur' => null
        ]);


        return redirect()->back()->with('success', 'Indisponibilités modifiés avec succès !');
    }

    public function showReviews($id)
    {
        $annonce = Annonce::with(['avisValides.utilisateur', 'ville'])->findOrFail($id);

        return view('tous-les-avis', compact('annonce'));
    }


    public function afficher_ajout_equipements() {
        $equipements = Equipement::all();
        $annonces = Annonce::all();
        
        return view('ajouter-equipements', [
            'equipements' => $equipements, 
            'annonces' => $annonces
        ]);
    }

    public function store_equipement(Request $request) {
        $request->validate([
            'nom_equipement' => 'required|unique:equipement,nom_equipement',
            'idcategorie' => 'required|integer'
        ]);

        $categorie_equipement = CategorieEquipement::Find($request->idcategorie);
        if (!$categorie_equipement->idcategorie) {
            return back()->withErrors(['error'=> 'Impossible de trouver la catégorie d\'équipement. Contacter un administrateur.']);
        }

        Equipement::create([
            'nom_equipement' => $request->nom_equipement,
            'idcategorie' => $categorie_equipement->idcategorie,
        ]);

        return redirect()->back()->with('success', 'Nouvel équipement créé avec succès !');
    }

    public function lier_equipement_annonce(Request $request) {
        $request->validate([
            'idannonce' => 'required|exists:annonce,idannonce',
            'idequipement' => 'required|exists:equipement,idequipement'
        ]);

        $annonce = Annonce::find($request->idannonce);
        $annonce->equipement()->syncWithoutDetaching([$request->idequipement]);
        return redirect()->back()->with('success', 'Équipement ajouté à l\'annonce !');
    }

    public function afficher_ajout_typehebergement() {
        $type_hebergements = TypeHebergement::all();
        $annonces = Annonce::all();
        return view('ajouter-typehebergement', [
            'type_hebergements' => $type_hebergements, 
            'annonces'=>$annonces
        ]);
    }

    public function store_typehebergement(Request $request) {
        $request->validate([
            'nom_type_hebergement' => 'required|unique:type_hebergement,nom_type_hebergement'
        ]);

        TypeHebergement::create([
            'nom_type_hebergement' => $request->nom_type_hebergement
        ]);

        return redirect()->back()->with('success', 'Nouveau type ajouté avec succès !');
    }

    public function update_annonce_type(Request $request) {
        $request->validate([
            'idannonce' => 'required|exists:annonce,idannonce',
            'idtypehebergement' => 'required|exists:type_hebergement,idtypehebergement'
        ]);

        $annonce = Annonce::find($request->idannonce);
        $annonce->idtypehebergement = $request->idtypehebergement;
        $annonce->save();

        return redirect()->back()->with('success', 'L’annonce a été mise à jour !');
    }

    public function view_gerer_annonce()
    {
        $user = Auth::user();
        if (!$user->administrateur) 
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette page.');

        $annonces = Annonce::orderBy('date_publication', 'desc')->get();
        return view('gerer-annonce', ['annonces' => $annonces]);
    }

    public function afficher_annonce_attente(Request $req)
    {
        $query = Annonce::query();

        if ($req->filled('statut')) {
            $query->where('code_verif', $req->statut);
        }

        $annonces = $query->get();

        return view('gerer-annonce', compact('annonces'));
    }

    public function save_statut_annonce(Request $req)
    {
        $statuts = $req->statuts;

        if($statuts) {
            foreach ($statuts as $idannonce => $value) {
                if(!empty($value)) {
                    Annonce::where("idannonce", $idannonce)->update(["code_verif" => $value]);
                }
            }
            return redirect()->back()->with('success', 'Statut des annonces modifié !');
        }
        return redirect()->back()->with('info', 'Aucun statut modifié.');
    }

    public function supprimer_annonce(Request $req)
    {
        $idannonce = $req->annonce_supp;

        
        $reservations = Reservation::where("idannonce", $idannonce)->get();
        $canDelete = true;
        
        foreach($reservations as $resa) {
            if($resa->date_debut_resa >= now() || $resa->date_fin_resa >= now()) {
                $canDelete = false;
            }
        }

        if ($canDelete) Annonce::where("idannonce", $idannonce)->update(["code_verif" => 'supprimée']);
        else return redirect()->back()->with('error', 'Impossible de supprimer l\'annonce, il existe des réservations à venir.');

        return redirect()->back()->with('success', 'Annonce supprimée !');
    }

    function view_modifier_annonce(Request $req) {
        $annonce = Annonce::where('idannonce', $req->annonce_modif)->first();
        $equipements = Equipement::all(); 
        $services = Service::all();
        $types = TypeHebergement::all();
        $ville = Ville::find($annonce->ville->idville);

        return view("modifier-annonce", [
            'annonce' => $annonce,
            'equipements' => $equipements,
            'services' => $services,
            'types' => $types,
            'ville' => $ville
        ]);  
    }

    function modifier_annonce(Request $req, $idannonce) {
        $annonce = Annonce::find($idannonce);
        $req->validate([
            'titre' => 'required|string|max:128',
            'adresse' => 'required|string',
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
            'file' => 'nullable|array',
            'file.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $codeville = Ville::where('nom_ville', $req->ville)->first();
        $type_heb = TypeHebergement::where('nom_type_hebergement', $req->DepotTypeHebergement)->first();

        $adresseComplete = $req->adresse . ', ' . $req->ville . ', France';
        $coordonnees = $this->geoService->geocode($adresseComplete);
        $latitude = $coordonnees ? $coordonnees['lat'] : null;
        $longitude = $coordonnees ? $coordonnees['lon'] : null;

        $annonce->update([
            'idtypehebergement' => $type_heb->idtypehebergement,
            'idville' => $codeville->idville,
            'titre_annonce' => $req->titre,
            'prix_nuit' => $req->prix_nuit,
            'nb_nuit_min' => $req->nb_nuits,
            'nb_bebe_max' => $req->nb_bebes,
            'nb_personnes_max' => $req->nb_pers,
            'nb_animaux_max' => $req->nb_animaux,
            'adresse_annonce' => $req->adresse,
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

                $img = $manager->read($file);
                $img->scaleDown(width: 1000, height: 1000);
                $img->toJpeg(90)->save($imgDestination . '/' . $fileName);

                Photo::create([
                    'idannonce' => $annonce->idannonce,
                    'nomphoto' => $fileNameDB,
                    'legende' => null,
                ]);
                $num_photo++;
            }
        } 

        $equipements = $req->DepotEquipement;
        $annonce->equipement()->sync($equipements);
        $services = $req->DepotService;        
        $annonce->service()->sync($services);

        $annonce->load('ville.departement');
        AnnonceSimilaire::where('idannonce', $idannonce)
            ->orWhere('idsimilaire', $idannonce)
            ->delete();

        $similaires = Annonce::whereHas('ville.departement', function ($query) use ($annonce) {
            $query->where('iddepartement', $annonce->ville->departement->iddepartement);
        })
        ->where('idtypehebergement', $annonce->idtypehebergement)
        ->where('nb_personnes_max', '>=', $annonce->nb_personnes_max)
        ->where('idannonce', '!=', $annonce->idannonce)
        ->get();

        foreach ($similaires as $s) {
            AnnonceSimilaire::create([
                'idannonce' => $annonce->idannonce,
                'idsimilaire' => $s->idannonce,
            ]);
        }

        return redirect('/profile')->with('success', 'Annonce modifiée avec succès !');
    }

}