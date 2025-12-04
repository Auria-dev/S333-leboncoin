<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Favoris;
use App\Models\Calendrier;

class AnnonceController extends Controller {
  public function view($id) {
    $iduser = -1;

    if (Auth::check()) {
        $iduser = auth()->user()->idutilisateur;
    }

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $id)
            ->exists();

    $disponibilites = Calendrier::where('idannonce', $id)
        ->whereHas('date', function($q) {
            $q->where('date', '>=', now()->toDateString());
        })
        ->with('date')
        ->get();

    $dispoMap = [];
    
    foreach($disponibilites as $cal) {
        if ($cal->date) {
            $dateString = \Carbon\Carbon::parse($cal->date->date)->format('Y-m-d');
            
            $dispoMap[$dateString] = [
                'dispo' => (bool)$cal->code_dispo, 
            ];
        }
    }
    
    return view ("detail-annonce", [
        'annonce'   => Annonce::findOrFail($id), 
        'isFav'     => $exists,
        'dispoJson' => json_encode($dispoMap) 
    ]);
  }

  public function addFav($idannonce) {
    $user = auth()->user();
    $iduser = $user->idutilisateur;

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $idannonce)
            ->exists();

    if (!$exists) {
      $res = Favoris::create([
        'idutilisateur' => $iduser,
        'idannonce' => $idannonce
      ]);
    } else {
      Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $idannonce)
            ->delete();
    }
    
    return redirect()->route('annonce', [
      'id'=>$idannonce, 
      'isFav' => $exists 
    ]);
  }
}
