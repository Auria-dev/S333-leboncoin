<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use App\Models\Utilisateur;
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
            'nom' => 'required',
            'prenom' => 'required',
            'password' => 'required|confirmed|min:8',
            'telephone' => 'required|unique:utilisateur,telephone',
            'email' => 'required|email|unique:utilisateur,mail',
            'adresse' => 'required',
        ]);

        $user = Utilisateur::create([
            'idville' => 1, // TODO: find out the city 
            'nom_utilisateur' => $req->nom,
            'prenom_utilisateur' => $req->prenom,
            'mot_de_passe' => Hash::make($req->password),
            'telephone' => $req->telephone,
            'mail' => $req->email,
            'adresse_utilisateur' => $req->adresse,
            'date_creation' => date('Y-m-d H:i:s')
        ]);

        Auth::login($user);
        
        return redirect(RouteServiceProvider::HOME);
    }

    function destroy(Request $request) { 
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect('/');
    }
}
