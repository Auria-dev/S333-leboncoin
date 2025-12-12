<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VerifierProfilController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DeposerAvisController;


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

Route::get('/resultats', [RechercheController::class, 'results'])->name('resultats');
Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce');

Route::get('/proprio/{id}', [ProprietaireController::class, 'view']);

Route::get('/login', [CompteController::class, 'login'])->name('login');
Route::post('/login', [CompteController::class, 'authenticate']);

Route::get('/register', [CompteController::class, 'create']);
Route::post('/register', [CompteController::class, 'store']);

Route::post('/logout', [CompteController::class, 'destroy']);

Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');

Route::get('/profile', [DashboardController::class, 'view'])->middleware('auth')->name('profile');

Route::get('/ajouter_fav/{id}', [AnnonceController::class, 'addFav'])->middleware('auth');

Route::post('/sauvegarder_recherche', [RechercheController::class, 'sauvegarderRecherche'])->middleware('auth');
Route::delete('/recherche/{id}', [RechercheController::class, 'destroy'])->name('recherche.destroy')->middleware('auth');

Route::get('/modifier_compte', [CompteController::class, 'view_modifier'])->middleware('auth');
Route::put('/modifier_compte/update', [CompteController::class, 'modifier'])->middleware('auth');
Route::post('/modifier_compte/upload', [CompteController::class, 'upload'])->middleware('auth');

Route::get('/demander_reservation/{id}', [AnnonceController::class, 'view_reserver'])->middleware('auth');
Route::post('/confirmer_reservation', [AnnonceController::class, 'reserver'])->middleware('auth');

// pour yoyo&ninie
Route::get('/creer_annonce', [AnnonceController::class, 'afficher_form'])->middleware('auth');
Route::post('/ajouter_annonce', [AnnonceController::class, 'ajouter_annonce'])->middleware('auth');

Route::get('/reservation/{id}/avis', [AvisController::class, 'create'])->name('avis.create');
Route::post('/reservation/{id}/avis', [AvisController::class, 'store'])->name('avis.store');

Route::post('/verifier_profil', [VerifierProfilController::class, 'verifier_profil'])->middleware('auth');

Route::get('/ajouter_paiement', [CompteController::class, 'afficher_ajout_paiement'])->middleware('auth');
Route::post('/ajouter_paiement', [CompteController::class, 'ajouter_paiement'])->middleware('auth');
Route::post('/modifier_paiement', [CompteController::class, 'modifier_paiement'])->middleware('auth');
Route::get('/payer/{id}', [CompteController::class, 'afficher_paiement'])->middleware('auth');


Route::get('/reservation/{id}', [ReservationController::class, 'view_modifier'])->middleware('auth');
Route::put('/reservation/update/{id}', [ReservationController::class, 'modifier_reservation'])->middleware('auth');
Route::post('/reservation/cancel/{id}', [ReservationController::class, 'annuler_reservation'])->middleware('auth');
Route::post('/reservation/accept/{id}', [ReservationController::class, 'accepter_reservation'])->middleware('auth');
Route::post('/reservation/refuse/{id}', [ReservationController::class, 'refuser_reservation'])->middleware('auth');
Route::get('/reservation/declare/{id}', [ReservationController::class, 'declarer_incident'])->middleware('auth');
Route::post('/reservation/save_incident', [ReservationController::class, 'save_incident'])->middleware('auth');
Route::post('/reservation/clore_incident', [ReservationController::class, 'clore_incident'])->middleware('auth');

Route::get('/reservation/{id}/avis', [AvisController::class, 'create'])->name('avis.create');
Route::post('/reservation/{id}/avis', [AvisController::class, 'store'])->name('avis.store');
Route::get('/verification/telephone', [AnnonceController::class, 'afficherFormVerification']) ->middleware('auth')->name('form.verification.telephone');
Route::post('/verification/telephone', [AnnonceController::class, 'traiterVerification'])->middleware('auth')->name('traiter.verification.telephone');

