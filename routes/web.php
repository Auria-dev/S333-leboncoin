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
use App\Http\Controllers\ServiceImmoController;
use App\Http\Controllers\ServiceComptableController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MsgController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\Google2FAController;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use App\Http\Controllers\BotManController;


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
Route::get('/modifier_compte', [App\Http\Controllers\CompteController::class, 'view_modifier'])->name('view_modifier_compte');
Route::put('/modifier_compte/update', [CompteController::class, 'modifier'])->middleware('auth');
Route::post('/modifier_compte/upload', [CompteController::class, 'upload'])->middleware('auth');

Route::get('/demander_reservation/{id}', [AnnonceController::class, 'view_reserver'])->middleware('auth');
Route::post('/confirmer_reservation', [AnnonceController::class, 'reserver'])->middleware('auth');
    
Route::get('/creer_annonce', [AnnonceController::class, 'afficher_form'])->middleware('auth');
Route::post('/ajouter_annonce', [AnnonceController::class, 'ajouter_annonce'])->middleware('auth');

Route::get('/ajouter_paiement', [CompteController::class, 'afficher_ajout_paiement'])->middleware('auth');
Route::post('/ajouter_paiement', [CompteController::class, 'ajouter_paiement'])->middleware('auth');
Route::post('/modifier_paiement', [CompteController::class, 'modifier_paiement'])->middleware('auth');
Route::get('/payer/{id}', [CompteController::class, 'afficher_paiement'])->middleware('auth');

Route::get('/admin/ajout_equipements', [AnnonceController::class, 'afficher_ajout_equipements'])->middleware('auth')->name('admin/ajout_equipements');
Route::get('/admin/ajout_typehebergement', [AnnonceController::class, 'afficher_ajout_typehebergement'])->middleware('auth')->name('admin/ajout_typehebergement');
Route::post('admin/typehebergement/store', [AnnonceController::class, 'store_typehebergement'])->middleware('auth');
Route::post('admin/annonce/update-type', [AnnonceController::class,'update_annonce_type'])->middleware('auth');

Route::get('/admin/gerer_annonce', [AnnonceController::class, 'view_gerer_annonce'])->middleware('auth')->name('admin/gerer_annonce');
Route::get('/afficher_attente', [AnnonceController::class, 'afficher_annonce_attente'])->middleware('auth');
Route::post('/enregistrer_statut_annonce', [AnnonceController::class, 'save_statut_annonce'])->middleware('auth');
Route::post('/admin/equipement/store', [AnnonceController::class, 'store_equipement']);
Route::post('/admin/equipement/link', [AnnonceController::class, 'lier_equipement_annonce']);

Route::get('/messagerie/{id?}', [MsgController::class, 'afficher_messagerie'])->middleware('auth')->name('messagerie');
Route::post('/messagerie/envoyer', [MsgController::class, 'envoyer_message'])->middleware('auth')->name('messagerie.envoyer');
Route::post('/contact/creer', [MsgController::class, 'creer_contact'])->middleware('auth')->name('contact.create');


Route::get('/reservation/{id}', [ReservationController::class, 'view_modifier'])->middleware('auth');
Route::put('/reservation/update/{id}', [ReservationController::class, 'modifier_reservation'])->middleware('auth');
Route::post('/reservation/cancel/{id}', [ReservationController::class, 'annuler_reservation'])->middleware('auth');
Route::post('/reservation/accept/{id}', [ReservationController::class, 'accepter_reservation'])->middleware('auth');
Route::post('/reservation/refuse/{id}', [ReservationController::class, 'refuser_reservation'])->middleware('auth');
Route::get('/reservation/declare/{id}', [ReservationController::class, 'declarer_incident'])->middleware('auth');
Route::post('/reservation/save_incident', [ReservationController::class, 'save_incident'])->middleware('auth');
Route::post('/reservation/clore_incident', [ReservationController::class, 'clore_incident'])->middleware('auth');
Route::post('/reservation/justifier_incident', [ReservationController::class, 'justifier_incident'])->middleware('auth');

Route::get('/admin/gerer_incident', [ServiceComptableController::class, 'view_gerer_incident'])->middleware('auth');
Route::post('/enregistrer_remboursement_incident', [ServiceComptableController::class, 'save_remboursement_incident'])->middleware('auth');


Route::get('/admin/gerer_incidents', [IncidentController::class, 'view_gerer_incidents'])->middleware('auth')->name('admin/gerer_incidents');
Route::get('/admin/incidents_attente', [IncidentController::class, 'afficher_incidents_attente'])->middleware('auth')->name('admin/incidents_attente');
Route::post('/enregistrer_statut_incident', [IncidentController::class, 'enregistrer_statut_incident'])->middleware('auth');

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);





// TODO : Revoir toutes ces routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
//Route::post('/admin/garantie/{id}', [AnnonceController::class, 'toggleGarantie'])->middleware('auth')->name('admin.toggle.garantie');
Route::middleware(['auth'])->group(function () {
    Route::get('/reservation/{id}/avis', [DeposerAvisController::class, 'create'])->name('avis.create');
    Route::post('/reservation/{id}/avis', [DeposerAvisController::class, 'store'])->name('avis.store');
});
Route::post('/verifier_profil', [VerifierProfilController::class, 'verifier_profil'])->middleware('auth');
Route::get('/messagerie/{id?}', [MsgController::class, 'afficher_messagerie'])->middleware('auth')->name('messagerie');
Route::post('/messagerie/envoyer', [MsgController::class, 'envoyer_message'])->middleware('auth')->name('messagerie.envoyer');
Route::post('/contact/creer', [MsgController::class, 'creer_contact'])->middleware('auth')->name('contact.create');

Route::get('/annonce/{id}/avis', [AnnonceController::class, 'showReviews'])->name('annonce.avis');
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/avis', function () {
        if (auth()->user()->idutilisateur != 52) { // i hate whoever did this.
            abort(403, 'Accès réservé au Service Immobilier.');
        }
        return app(ServiceImmoController::class)->indexAvis();
    })->name('admin.avis.index');


    Route::post('/admin/avis/{id}', function ($id) {
        if (auth()->user()->idutilisateur != 52) {
            abort(403, 'Accès réservé au Service Immobilier.');
        }
        return app()->call([app(ServiceImmoController::class), 'updateStatutAvis'], ['id' => $id]);
    })->name('admin.avis.update');

});
Route::get('/telephone', [AnnonceController::class, 'afficherFormVerification'])->name('form.verification');
Route::post('/envoyer-sms', [AnnonceController::class, 'envoyerCodeSms'])->name('action.envoyer.sms');
Route::post('/verifier-code', [AnnonceController::class, 'traiterVerification'])->name('action.verifier.code');
Route::get('/verification/telephone', [AnnonceController::class, 'afficherFormVerification']) ->middleware('auth')->name('form.verification.telephone');
Route::post('/verification/telephone', [AnnonceController::class, 'traiterVerification'])->middleware('auth')->name('traiter.verification.telephone');
Route::get('/verification/telephone', [AnnonceController::class, 'afficherFormVerification'])
    ->middleware('auth')
    ->name('form.verification.telephone');
Route::post('/verification/telephone', [AnnonceController::class, 'traiterVerification'])
    ->middleware('auth')
    ->name('traiter.verification.telephone');

Route::view('/stat', 'stats_bi');

Route::middleware(['auth'])->group(function () {
    Route::get('/compte/2fa/activer', [CompteController::class, 'activationdoublefacteur'])->name('2fa.enable');
    Route::post('/compte/2fa/confirmer', [CompteController::class, 'confirmerdoublefacteurs'])->name('2fa.confirm');
    Route::post('/compte/2fa/desactiver', [CompteController::class, 'desactiverdoublefacteur'])->name('2fa.disable');
});
Route::get('/login/2fa', [Google2FAController::class, 'index'])->name('2fa.index');
Route::post('/login/2fa', [Google2FAController::class, 'verify'])->name('2fa.verify');
Route::get('/login/2fa', [Google2FAController::class, 'index'])->name('2fa.index');
Route::post('/login/2fa', [Google2FAController::class, 'verify'])->name('2fa.verify');
