<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/recherche', [RechercheController::class, 'index']);

Route::post('/resultats', [RechercheController::class, 'results']);
Route::get('/annonce/{id}', [AnnonceController::class, 'view']);

Route::get('/proprio/{id}', [ProprietaireController::class, 'view']);

Route::get('/login', [CompteController::class, 'login'])->name('login');
Route::post('/login', [CompteController::class, 'authenticate']);

Route::get('/register', [CompteController::class, 'create']);
Route::post('/register', [CompteController::class, 'store']);

Route::post('/logout', [CompteController::class, 'destroy']);

Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');

Route::get('/profile', [DashboardController::class, 'view'])->middleware('auth');
Route::get('/modifier_compte', [CompteController::class, 'view_modifier'])->middleware('auth');
Route::put('/modifier_compte/update', [CompteController::class, 'modifier'])->middleware('auth');
