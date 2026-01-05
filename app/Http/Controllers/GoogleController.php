<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str; 
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
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = Utilisateur::where('google_id', $googleUser->id)
                             ->orWhere('mail', $googleUser->email) 
                             ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }

                Auth::login($user);
                return redirect('/'); 
            } else {
                $fullName = explode(' ', $googleUser->name, 2);
                $prenom = $fullName[0];
                $nom = isset($fullName[1]) ? $fullName[1] : 'Inconnu'; 
                $newUser = Utilisateur::create([
                    'nom_utilisateur'    => $nom,      
                    'prenom_utilisateur' => $prenom,   
                    'mail'               => $googleUser->email, 
                    'google_id'          => $googleUser->id,    
                    'date_creation'      => Carbon::now(),
                    'idville'            => null, 
                    'telephone'          => null, 
                    'mot_de_passe'       => Hash::make(Str::random(16)), 
                    'photo_profil'       => '/images/photo-profil.jpg', 
                ]);

                DB::table('particulier')->insert([
                    'idparticulier'    => $newUser->idutilisateur, 
                    'code_particulier' => 0, 
                    'piece_identite'   => null
                ]);

                Auth::login($newUser);
                return redirect()->route('view_modifier_compte')->with('success', 'Compte créé avec Google ! Bienvenue.');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}