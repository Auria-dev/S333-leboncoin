<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
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
            'date_creation' => now()
        ]);

        Auth::login($user);

        $user = auth()->user();
        
        if ($req->typeCompte == 'particulier') {
            DB::table('particulier')->insert(
                array(
                    'idparticulier'    => $user->idutilisateur, 
                    'code_particulier' => 0 // 0 by default, user is a locataire until they upload something      
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

    }
}
