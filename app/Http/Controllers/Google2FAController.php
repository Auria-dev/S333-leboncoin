<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

class Google2FAController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa:user:id')) {
            return redirect('/');
        }
        
        return view('2fa_verifier');
    }

    public function verify(Request $request)
    {
        $userId = session()->get('2fa:user:id');
        $user = Utilisateur::where('idutilisateur', $userId)->first();

        if (!$user) {
            return redirect('/login');
        }

        $google2fa = new Google2FA();
        
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            Auth::login($user);
            session()->forget('2fa:user:id');
            return redirect('/'); 
        }
        return back()->with('error', 'Code incorrect. Veuillez réessayer.');
    }
}