<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    
    public function afficher()
    {
        return view('stats_bi');
    }

    public function verifier(Request $request)
    {
        
        $email_attendu = 'muneretjarod@gmail.com';
        $mdp_attendu   = '12345678';

        if ($request->email == $email_attendu && $request->password == $mdp_attendu) { 
            return redirect('/stat')->with('acces_autorise', true);
            
        } else {
            return back()->with('error', 'Identifiants incorrects !');
        }
    }
}