<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Photo;
use App\Models\Equipement;
use App\Models\Service;
use App\Services\GeoapifyService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

// Imports Vonage
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

    // =========================================================
    // PARTIE 1 : DASHBOARD ADMIN (ACCESSIBLE A TOUT LE MONDE)
    // =========================================================

    public function adminDashboard()
    {
        // CORRECTION ICI : On remplace 'created_at' par 'date_publication'
        // (Ou par 'idannonce' si tu préfères trier par ID)
        
        $annonces = Annonce::with('utilisateur')->orderBy('date_publication', 'desc')->get();
        
        return view('admin.dashboard', ['annonces' => $annonces]);
    }

    public function toggleGarantie($idannonce)
    {
        // On ne vérifie plus l'email.
        
        $annonce = Annonce::with('utilisateur')->findOrFail($idannonce);

        // On garde quand même la logique métier : 
        // Impossible de garantir si le téléphone n'est pas vérifié
        if (!$annonce->utilisateur->telephone_verifie) {
            return redirect()->back()->with('error', 'Impossible de garantir : L\'utilisateur n\'a pas vérifié son téléphone !');
        }

        $annonce->est_garantie = !$annonce->est_garantie;
        $annonce->save();

        $status = $annonce->est_garantie ? "garantie ✅" : "retirée ❌";
        return redirect()->back()->with('success', "Annonce $status");
    }

    // =========================================================
    // PARTIE 2 : AJOUT D'ANNONCE
    // =========================================================

    public function afficher_form()
    {
        $types = TypeHebergement::all();
        $equipements = Equipement::all();
        $services = Service::all();
        return view("ajouter-annonce", ['types' => $types, 'equipements' => $equipements, 'services' => $services]);
    }

    public function ajouter_annonce(Request $req)
    {
        $user = Auth::user();
        
        $req->validate(['titre' => 'required', 'ville' => 'required', 'prix_nuit' => 'required']);

        $codeville = Ville::where('nom_ville', $req->ville)->first();
        $type_heb = TypeHebergement::where('nom_type_hebergement', $req->DepotTypeHebergement)->first();
        $adresseComplete = $req->depot_adresse . ', ' . $req->ville . ', France';
        $coordonnees = $this->geoService->geocode($adresseComplete);

        $annonce = Annonce::create([
            'idtypehebergement' => $type_heb->idtypehebergement ?? 1,
            'idproprietaire' => $user->idutilisateur,
            'idville' => $codeville->idville ?? 1,
            'titre_annonce' => $req->titre,
            'prix_nuit' => $req->prix_nuit,
            'nb_nuit_min' => $req->nb_nuits ?? 1,
            'nb_bebe_max' => $req->nb_bebes ?? 0,
            'nb_personnes_max' => $req->nb_pers ?? 1,
            'nb_animaux_max' => $req->nb_animaux ?? 0,
            'adresse_annonce' => $req->depot_adresse ?? '',
            'description_annonce' => $req->desc ?? '',
            'date_publication' => now(),
            'heure_arrivee' => $req->heure_arr ?? '14:00', 
            'heure_depart' => $req->heure_dep ?? '10:00',
            'nombre_chambre' => $req->nb_chambres ?? 1,
            'longitude' => $coordonnees['lon'] ?? null,
            'latitude' => $coordonnees['lat'] ?? null,
            'est_garantie' => false
        ]);
        
        Photo::create(['idannonce' => $annonce->idannonce, 'nomphoto' => "/images/photo-annonce.jpg"]);
        
         DB::table('calendrier')->insertUsing(
            ['iddate', 'idannonce', 'idutilisateur', 'code_dispo'],
            DB::table('date as d')->crossJoin('annonce as a')->where('a.idannonce', $annonce->idannonce)
                ->select('d.iddate', 'a.idannonce', DB::raw('NULL'), DB::raw('TRUE'))
        );

        if (!$user->telephone_verifie) {
            if (empty($user->telephone)) {
                return redirect('/telephone')->with('warning', 'Annonce créée ! Vérifiez votre téléphone.');
            }
            return $this->lancerProcessusVerification($user->telephone);
        }

        return redirect('/profile')->with('success', 'Annonce publiée !');
    }

    // --- LOGIQUE VONAGE ---
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

            $client->sms()->send(new SMS($numeroClean, "Vonage APIs", "Code : " . $code));
            return redirect('/telephone')->with('success', 'Annonce créée ! Vérifiez vos SMS.');

        } catch (\Exception $e) {
            return redirect('/telephone')->with('error', 'Erreur SMS : ' . $e->getMessage());
        }
    }

    // --- ROUTINES ---
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
            return redirect('/profile')->with('success', 'Compte vérifié !');
        }
        return redirect()->back()->with('error', 'Code incorrect.');
    }

    public function view($id) { return view("detail-annonce", ['annonce' => Annonce::findOrFail($id), 'isFav'=>false, 'dispoJson'=>'{}']); }
    public function addFav($id) { return back(); }
    public function view_reserver(Request $req, $idannonce) { return view("reserver-annonce"); }
    public function reserver(Request $req) { return redirect()->back(); }
    public function showReviews($id) { return view('tous-les-avis'); }
}