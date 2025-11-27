<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompteController extends Controller
{
    function login() {
        return view("login");
    }

    function create() {
        return view("creation-compte");
    }
}
