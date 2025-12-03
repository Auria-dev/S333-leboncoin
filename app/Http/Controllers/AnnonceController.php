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
    return view ("detail-annonce", ['annonce'=>Annonce::findOrFail($id) ]);
  }

  // public function addFav($idannonce) {
  //   $user = auth()->user();
  //   $iduser = $user->idutilisateur;

  //   Favoris::Create(...)
  // }
}
