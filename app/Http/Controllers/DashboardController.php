<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class DashboardController extends Controller
{
    public function view() {
        $user = auth()->user();
        
        return view ("dashboard-utilisateur", [
            "utilisateur" => $user,
        ]);
    }
}
