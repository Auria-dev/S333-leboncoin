<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use App\Models\Utilisateur;

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
        return view("creation-compte");
    }

    function store(Request $req) {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:utilisateur,mail',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Utilisateur::create([
            'nom_utilisateur' => $req->name,
            'mail' => $req->email,
            'idville' => 1,
            'mdp' => Hash::make($req->password),
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
