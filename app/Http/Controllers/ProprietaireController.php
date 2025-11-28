<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Annonce;
use App\Models\Proprietaire;

class ProprietaireController extends Controller
{
    public function view($id) {
        return view ("detail-proprietaire", [ 'proprietaire' /* c'est jeankevin! */ =>Proprietaire::findOrFail($id) ]); 
    }
}
