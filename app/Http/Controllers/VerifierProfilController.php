<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;
use App\Models\Annonce;
use App\Models\Particulier;

class VerifierProfilController extends Controller
{
    public function verifier_profil(Request $req) {
        $user = Auth::user();
        $id = $user->idutilisateur;
        $isParticulier = DB::table('particulier')->where('idparticulier', $id)->exists();

        if($isParticulier) {
            if($req->hasFile('file')) {  
                $file = $req->file('file');  
                $filePath = $file->getClientOriginalName();
                $fileName = '/CNI/cni_utilisateur_' . $filePath . '.pdf';
                $file->move(public_path('CNI'), $fileName);
                $url = asset('CNI/'. $fileName);

                DB::table('particulier')
                    ->where('idparticulier', $id)
                    ->update(['piece_identite' => $fileName]);
            }
        }
        return redirect('/creer_annonce')->with('success', 'Profil complété ! Vous pouvez maintenant déposer votre annonce.');
    }


}
