<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Carbon\Carbon;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = Utilisateur::where('GOOGLE_ID', $googleUser->id)
                               ->orWhere('MAIL', $googleUser->email)
                               ->first();

            if ($user) {
                if (!$user->GOOGLE_ID) {
                    $user->GOOGLE_ID = $googleUser->id;
                    $user->save();
                }

                Auth::login($user);
                return redirect('/accueil'); 
            } else {
    
                $fullName = explode(' ', $googleUser->name, 2);
                $prenom = $fullName[0];
                $nom = isset($fullName[1]) ? $fullName[1] : ''; 

                $newUser = Utilisateur::create([
                    'NOM_UTILISATEUR'     => $nom,
                    'PRENOM_UTILISATEUR'  => $prenom,
                    'MAIL'                => $googleUser->email,
                    'GOOGLE_ID'           => $googleUser->id,
                    'DATE_CREATION'       => Carbon::now(),
                    'IDVILLE'             => null, 
                    'MOT_DE_PASSE'        => null, 
                    
                ]);

                Auth::login($newUser);
                return redirect('/profil/edit')->with('message', 'Compte créé ! Veuillez compléter votre ville et téléphone.');
            }
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Erreur de connexion Google');
        }
    }
}