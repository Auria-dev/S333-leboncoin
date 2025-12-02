<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function view() {
        $user = auth()->user();
        return view ("dashboard-utilisateur", [
            "utilisateur" => $user
        ]);
    }
}
