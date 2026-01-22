<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeHebergement;

class AccueilController extends Controller
{
    public function index()
    {
        $annoncesRecentes = Annonce::with('ville', 'type_hebergement')
                                   ->take(10)
                                   ->get();
        $types = TypeHebergement::all();

        return view('home', compact('annoncesRecentes', 'types'));
    }
}
