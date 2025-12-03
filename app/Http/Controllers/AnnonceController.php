<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Favoris;

class AnnonceController extends Controller
{
  public function view($id) {
    $iduser = auth()->user()->idutilisateur;

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $id)
            ->exists();

    return view ("detail-annonce", [
      'annonce'=>Annonce::findOrFail($id), 
      'isFav' => $exists 
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
    return view ("detail-annonce", [
      'annonce'=>Annonce::findOrFail($idannonce), 
      'isFav' => $exists 
    ]);
  }
}
