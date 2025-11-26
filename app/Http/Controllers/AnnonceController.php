<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\TypeHebergement;

class AnnonceController extends Controller
{
    public function view($id) {
      return view ("detail-annonce", ['annonce'=>Annonce::findOrFail($id) ]);
    }
}
