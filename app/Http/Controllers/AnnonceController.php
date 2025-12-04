<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Favoris;

class AnnonceController extends Controller
{
  public function view($id) {
    $iduser = -1;

    if (Auth::check()) {
      $iduser = auth()->user()->idutilisateur;
    }

    $exists = Favoris::where('idutilisateur', '=', $iduser)
            ->where('idannonce', '=', $id)
            ->exists();

    return view ("detail-annonce", [
      'annonce'=>Annonce::findOrFail($id), 
      'isFav' => $exists // TODO : faire un attribut dans annonce à la place
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
