<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LocationController extends Controller {
    public function search(Request $request) {
        $query = $request->get('query');

        $cities = DB::table('ville')
            ->select('nom_ville as name', DB::raw("'Ville' as type"))
            ->where('nom_ville', 'ILIKE', "{$query}%");

        $departments = DB::table('departement')
            ->select('nom_departement as name', DB::raw("'Département' as type"))
            ->where('nom_departement', 'ILIKE', "{$query}%");

        $regions = DB::table('region')
            ->select('nom_region as name', DB::raw("'Région' as type"))
            ->where('nom_region', 'ILIKE', "{$query}%");

        $results = $regions
            ->union($departments)
            ->union($cities)
            ->limit(5)
            ->get();

        return response()->json($results);
    }
}
