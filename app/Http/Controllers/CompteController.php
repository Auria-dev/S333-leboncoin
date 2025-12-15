<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use App\Models\Utilisateur;
use App\Models\Ville;
use App\Models\SecteurActivite;

// TODO (auria): Voir comment login avec Google 
// https://kinsta.com/blog/laravel-authentication/#laravel-socialite

class CompteController extends Controller {
    function login() {
        return view("login");
    }

    function authenticate(Request $req) {
        $credentials = $req->validate([
            'email' => ['required', 'email'],
            'mot_de_passe' => ['required'],
        ]);

        if (Auth::attempt([
            'mail' => $credentials['email'],
            'password' => $credentials['mot_de_passe']
        ])) {
            $req->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas.',
        ])->onlyInput('email');
    }

    function create() {
        $secteurs = SecteurActivite::All();
        return view("creation-compte", ['secteurs' => $secteurs]);
    }

    function store(Request $req) {
        $req->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email|unique:utilisateur,mail',
            'telephone' => 'required|digits:10|unique:utilisateur,telephone', // TODO (auria): better phone number handling (remove spaces before checking, so on)
            'adresse' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $codeville = Ville::where('nom_ville', $req->ville)->first();
        

        $user = Utilisateur::create([
            'idville' => $codeville->idville,
            'nom_utilisateur' => $req->nom,
            'prenom_utilisateur' => $req->prenom,
            'mot_de_passe' => Hash::make($req->password),
            'telephone' => $req->telephone,
            'mail' => $req->email,
            'adresse_utilisateur' => $req->adresse,
            'date_creation' => now(),
            'photo_profil' => "/images/photo-profil.jpg",
        ]);

        Auth::login($user);

        $user = auth()->user();
        
        $fileName = null;
        if($req->hasFile('file')) {  
            $file = $req->file('file');  
            $fileName = '/CNI/cni_utilisateur_' . $user->idutilisateur . '.pdf';
            $file->move(public_path('CNI'), $fileName);
            $url = asset('CNI/'. $fileName);
        }
        
        if ($req->typeCompte == 'particulier') {
            DB::table('particulier')->insert(
                array(
                    'idparticulier'    => $user->idutilisateur, 
                    'code_particulier' => 0, // 0 by default, user is a locataire until they upload something 
                    'piece_identite' => $fileName,   
                )
            );
        } else {
            $secteur = SecteurActivite::where('nom_secteur', $req->secteur)->first();

            DB::table('entreprise')->insert(
                array(
                    'identreprise' => $user->idutilisateur, 
                    'numsiret'     => $req->siret,
                    'idsecteur'    => $secteur->idsecteur
                )
            );
        }

        return redirect(RouteServiceProvider::HOME);
    }

    function destroy(Request $request) { 
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect('/');
    }

    function view_modifier() {
        $user = Auth::user();
        
        $ville = Ville::find($user->idville); 
        
        $entreprise = DB::table('entreprise')->where('identreprise', $user->idutilisateur)->first();
        $isEntreprise = $entreprise !== null;
        
        $secteurs = SecteurActivite::all();

        return view("modifier-compte", [
            'user' => $user,
            'ville' => $ville,
            'entreprise' => $entreprise,
            'isEntreprise' => $isEntreprise,
            'secteurs' => $secteurs
        ]);   
    }

    function modifier(Request $req) {
        $user = Auth::user();
        $id = $user->idutilisateur;

        $rules = [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => "required|email|unique:utilisateur,mail,$id,idutilisateur", 
            'telephone' => "required|digits:10|unique:utilisateur,telephone,$id,idutilisateur",
            'adresse' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed', 
            'ville' => 'required|string',
            'code_postal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ];

        $isParticulier = DB::table('particulier')->where('idparticulier', $id)->exists();
        $isEntreprise = DB::table('entreprise')->where('identreprise', $id)->exists();
        if ($isEntreprise) {
            $rules['siret'] = 'required|string|max:14';
            $rules['secteur'] = 'required|string';
        }
        
        $messages = [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => "L'adresse email n'est pas valide.",
            'unique' => "Ce :attribute est déjà utilisé par un autre compte.",
            'digits' => "Le :attribute doit contenir exactement :digits chiffres.",
            'min' => "Le :attribute doit contenir au moins :min caractères.",
            'confirmed' => "La confirmation du mot de passe ne correspond pas.",
            'mimes' => "Le fichier doit être au format :values.",
            'max' => "Le champ :attribute ne doit pas dépasser :max caractères/Ko.",
        ];
        $attributes = [
            'email' => 'adresse email',
            'telephone' => 'numéro de téléphone',
            'password' => 'mot de passe',
            'siret' => 'numéro SIRET',
        ];

        $req->validate($rules, $messages, $attributes);

        $ville = Ville::firstOrCreate([
                'nom_ville' => $req->ville, 
                'code_postal' => $req->code_postal
            ]);

        $userData = [
            'nom_utilisateur' => $req->nom,
            'prenom_utilisateur' => $req->prenom,
            'mail' => $req->email,
            'telephone' => $req->telephone,
            'adresse_utilisateur' => $req->adresse,
            'idville' => $ville->idville,
        ];

        if ($req->filled('password')) $userData['mot_de_passe'] = Hash::make($req->password);

        DB::table('utilisateur')
            ->where('idutilisateur', $id)
            ->update($userData);

        if ($isEntreprise) {
            $secteurObj = SecteurActivite::where('nom_secteur', $req->secteur)->first();
            
            if ($secteurObj) {
                DB::table('entreprise')
                    ->where('identreprise', $id)
                    ->update([
                        'numsiret' => $req->siret,
                        'idsecteur' => $secteurObj->idsecteur
                    ]);
            }
        }

        if($isParticulier) {
            if($req->hasFile('file')) { 
                $manager = new ImageManager(new Driver); 
                $file = $req->file('file');  
                $fileName = '/CNI/cni_utilisateur_' . $user->idutilisateur . '.pdf';
                $file->move(public_path('CNI'), $fileName);
                $url = asset('CNI/'. $fileName);

                DB::table('particulier')
                    ->where('idparticulier', $id)
                    ->update(['piece_identite' => $fileName]);
            }
        }

        return back()->with('success', 'Compte mis à jour avec succès.');
    }

    function upload(Request $req) {
        $user = Auth::user();
        $req->validate(['file' => 'image|mimes:jpg,png,jpeg|max:2048']);

        if($req->hasFile('file')) { 
            $manager = new ImageManager(new Driver()); 
            $file = $req->file('file');  
	    
            $fileName = 'photo_utilisateur_' . $user->idutilisateur . '.jpg';
            $fileNameDB = '/images/photo_utilisateur_' . $user->idutilisateur . '.jpg';
            $url = asset('images/'. $fileName);
            $imgDestination = public_path('images');

            $img = $manager->read($file);
            $img->scaleDown(width: 500, height: 500);
            $img->toJpeg(90)->save($imgDestination . '/' . $fileName);
            $img->save($imgDestination . '/' . $fileName);

            DB::table('utilisateur')->where('idutilisateur', $user->idutilisateur)->update([
                'photo_profil' => $fileNameDB
            ]);
        } 

        return back()->with('success', 'Mis à jour de la photo de profil avec succès.');
    }

    public function afficher_ajout_paiement() {
        return view('ajouter-paiement');
    }

    public function ajouter_paiement() {

    }

    public function modifier_paiement() {

    }

    public function afficher_paiement($idreservation) {
        $reservation = Reservation::findOrFail($idreservation);
        if (auth()->user()->idutilisateur != $reservation->idlocataire) {
            return redirect()->route('profile')->with('error', "Vous n'avez pas accès à cette page.");
        }

        return view('gerer-paiement',
         [
            'reservation'=> $reservation
         ]
        );
    }
}