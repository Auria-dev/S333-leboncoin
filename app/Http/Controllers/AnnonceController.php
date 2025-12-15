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

    public function adminDashboard()
    {
        $user = Auth::user();
        if (!$user->administrateur) 
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette page.');
        
        $annonces = Annonce::with('utilisateur')->orderBy('date_publication', 'desc')->get();
        return view('admin.dashboard', ['annonces' => $annonces]);
    }

    public function toggleGarantie($idannonce)
    {
        $annonce = Annonce::with('utilisateur')->findOrFail($idannonce);

        if (!$annonce->utilisateur->telephone_verifie) {
            return redirect()->back()->with('error', 'Impossible de garantir : L\'utilisateur n\'a pas vérifié son téléphone !');
        }

        $annonce->est_garantie = !$annonce->est_garantie;
        $annonce->save();

        $status = $annonce->est_garantie ? "garantie ✅" : "retirée ❌";
        return redirect()->back()->with('success', "Annonce $status");
    }

    // --- AJOUT ANNONCE ---
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

        if ($req->has('DepotEquipement')) {
            $annonce->equipement()->attach($req->DepotEquipement);
        }

        if ($req->has('DepotService')) {
            $annonce->service()->attach($req->DepotService);
        }
        
        if ($req->hasFile('file')) {
            foreach ($req->file('file') as $file) {
                Photo::create(['idannonce' => $annonce->idannonce, 'nomphoto' => "/images/photo-annonce.jpg"]); 
            }
        } else {
            Photo::create(['idannonce' => $annonce->idannonce, 'nomphoto' => "/images/photo-annonce.jpg"]);
        }
        
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

        return redirect('/profile')->with('success', 'Annonce publiée avec succès !');
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

            $client->sms()->send(new SMS($numeroClean, "Vonage APIs", "Code : " . $code));
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
        $annonce = Annonce::with([
            'photo', 'ville', 'utilisateur', 'type_Hebergement', 'avisValides.utilisateur', 
            'equipement', 'service' 
        ])->findOrFail($id);

        $iduser = Auth::check() ? Auth::user()->idutilisateur : -1;
        $exists = \App\Models\Favoris::where('idutilisateur', $iduser)->where('idannonce', $id)->exists();

        $disponibilites = \App\Models\Calendrier::where('idannonce', $id)
            ->whereHas('date', function ($q) { $q->where('date', '>=', now()->toDateString()); })
            ->with('date')->get();

        $dispoMap = [];
        foreach ($disponibilites as $cal) {
            if ($cal->date) {
                $dateString = \Carbon\Carbon::parse($cal->date->date)->format('Y-m-d');
                $dispoMap[$dateString] = ['dispo' => (bool) $cal->code_dispo];
            }
        }

        return view("detail-annonce", [
            'annonce' => $annonce,
            'isFav' => $exists,
            'dispoJson' => json_encode($dispoMap)
        ]);
    }

    public function addFav($idannonce) {
        $user = auth()->user();
        $exists = \App\Models\Favoris::where('idutilisateur', $user->idutilisateur)->where('idannonce', $idannonce)->exists();
        if (!$exists) \App\Models\Favoris::create(['idutilisateur' => $user->idutilisateur, 'idannonce' => $idannonce]);
        else \App\Models\Favoris::where('idutilisateur', $user->idutilisateur)->where('idannonce', $idannonce)->delete();
        return redirect()->back();
    }
    public function view_reserver(Request $req, $idannonce) { return view("reserver-annonce"); }
    public function reserver(Request $req) { return redirect()->back(); }
    public function showReviews($id) { return view('tous-les-avis'); }
}