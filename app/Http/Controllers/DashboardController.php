<?php
 
namespace App\Http\Controllers;
 
use App\Models\Incident;
 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
 use App\Mail\VerifyEmail;
 use Illuminate\Support\Facades\Mail;
 
 use App\Models\TypeHebergement;

class DashboardController extends Controller
{
    public function view()
    {

        $user = Auth::user();
 

        $user->load([
            'reservation.avis',           
            'reservation.annonce.ville',
            'annonce.photo',
            'annonce.type_hebergement',
            'annonce.ville',
            'demandesReservations.annonce',
            'demandesReservations.particulier.utilisateur',
            'favoris.photo',
            'favoris.ville',
            'recherche'
        ]);

        Mail::raw('test email from smtp2go', function ($message) {
            $message->to('wovif33296@atinjo.com')
                    ->subject('smtp2go test');
        });
 
        return view('dashboard-utilisateur', [
            'utilisateur' => $user
        ]);
    }

    public function newDashboard() {
        return view('dashboard');
    }

    public function filterLayout() {
        return view('dashboard/filter-layout');
    }

    public function mesAnnonces(Request $request) {
        $query = auth()->user()->annonce()->with(['type_hebergement', 'ville', 'photo']);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';

            $q->where(function($sub) use ($term)
            {
                $sub->where('titre_annonce', 'like', $term)
                    ->orWhere('adresse_annonce', 'like', $term)
                    ->orWhereHas('ville', function($v) use ($term)
                    {
                        $v->where('nom_ville', 'like', $term);
                    });
            });
        });

        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->whereHas('type_hebergement', function($t) use ($request) {
                $t->where('nom_type_hebergement', $request->type);
            });
        });

        $query->when($request->filled('nbVoyageurs'), fn($q) => $q->where('nb_personnes_max', '>=', $request->nbVoyageurs));
        $query->when($request->filled('nbBebes'),     fn($q) => $q->where('nb_bebe_max', '>=', $request->nbBebes));

        $query->when($request->filled('prixMin'), fn($q) => $q->where('prix_nuit', '>=', $request->prixMin));
        $query->when($request->filled('prixMax'), fn($q) => $q->where('prix_nuit', '<=', $request->prixMax));

        $annonces = $query->paginate(10)->withQueryString();

        return view('dashboard.mes-annonces', [
            'types' => TypeHebergement::all(),
            'annonces' => $annonces
        ]);
    }

    public function mesFavoris(Request $request) {
        $query = auth()->user()->favoris()->with(['type_hebergement', 'ville', 'photo']);
        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->where(function($sub) use ($term) {
                $sub->where('titre_annonce', 'like', $term)
                    ->orWhere('adresse_annonce', 'like', $term)
                    ->orWhereHas('ville', function($v) use ($term) {
                        $v->where('nom_ville', 'like', $term);
                    });
            });
        });

        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->whereHas('type_hebergement', function($t) use ($request) {
                $t->where('nom_type_hebergement', $request->type);
            });
        });

        $query->when($request->filled('prixMin'), fn($q) => $q->where('prix_nuit', '>=', $request->prixMin));
        $query->when($request->filled('prixMax'), fn($q) => $q->where('prix_nuit', '<=', $request->prixMax));

        $annonces = $query->paginate(10)->withQueryString();

        return view('dashboard.mes-favoris', [
            'types' => TypeHebergement::all(),
            'annonces' => $annonces
        ]);
    }

    public function mesVoyages(Request $request) {
        $query = auth()->user()->reservation()->with(['annonce.photo', 'annonce.ville', 'avis']);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->whereHas('annonce', function($sub) use ($term) {
                $sub->where('titre_annonce', 'like', $term)
                    ->orWhere('adresse_annonce', 'like', $term)
                    ->orWhereHas('ville', function($v) use ($term) {
                        $v->where('nom_ville', 'like', $term);
                    });
            });
        });

        $query->when($request->filled('datedebut'), fn($q) => $q->where('date_debut_resa', '>=', $request->datedebut));
        $query->when($request->filled('datefin'), fn($q) => $q->where('date_fin_resa', '<=', $request->datefin));

        $query->when($request->filled('statut'), function ($q) use ($request) {
            $s = $request->statut;
            if($s === 'accepted') {
                $q->where(function($w) { $w->where('statut_reservation', 'like', '%valid%')->orWhere('statut_reservation', 'like', '%accept%'); });
            } elseif($s === 'pending') {
                $q->where('statut_reservation', 'like', '%attent%');
            } elseif($s === 'cancelled') {
                $q->where(function($w) { $w->where('statut_reservation', 'like', '%refus%')->orWhere('statut_reservation', 'like', '%annul%'); });
            }
        });

        $reservations = $query->latest('date_debut_resa')->paginate(10)->withQueryString();

        return view('dashboard.mes-voyages', [
            'reservations' => $reservations
        ]);
    }

    public function mesDemandes(Request $request) {
        
        $query = auth()->user()->demandesReservations()->with(['annonce.photo', 'annonce.ville', 'particulier.utilisateur']);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->where(function($sub) use ($term) {
                $sub->whereHas('annonce', fn($a) => $a->where('titre_annonce', 'like', $term))
                ->orWhereHas('particulier.utilisateur', fn($u) => 
                    $u->where('nom_utilisateur', 'like', $term)
                    ->orWhere('prenom_utilisateur', 'like', $term)
                );
            });
        });

        $query->when($request->filled('datedebut'), fn($q) => $q->where('date_debut_resa', '>=', $request->datedebut));
        $query->when($request->filled('datefin'), fn($q) => $q->where('date_fin_resa', '<=', $request->datefin));

        $query->when($request->filled('statut'), function ($q) use ($request) {
            $s = $request->statut;
            if($s === 'accepted') {
                $q->where(function($w) { $w->where('statut_reservation', 'like', '%valid%')->orWhere('statut_reservation', 'like', '%accept%'); });
            } elseif($s === 'pending') {
                $q->where('statut_reservation', 'like', '%attent%');
            } elseif($s === 'cancelled') {
                $q->where(function($w) { $w->where('statut_reservation', 'like', '%refus%')->orWhere('statut_reservation', 'like', '%annul%'); });
            }
        });

        $demandes = $query->paginate(10)->withQueryString();

        return view('dashboard.mes-demandes', [
            'demandes' => $demandes
        ]);
    }

    public function mesRecherches(Request $request) {
        $query = auth()->user()->recherche()->withPivot('titre_recherche'); 

        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->where(function($sub) use ($term) {
                $sub->where('mot_clef', 'like', $term)
                    ->orWherePivot('titre_recherche', 'like', $term);
            });
        });

        $query->when($request->filled('type'), fn($q) => $q->where('type_hebergement', $request->type));
        $recherches = $query->paginate(10)->withQueryString();

        return view('dashboard.mes-recherches', [
            'recherches' => $recherches,
            'types' => TypeHebergement::all()
        ]);
    }

    public function centreDAide(Request $request) {
        
        $tenantQuery = auth()->user()->reservation()
            ->has('incident')
            ->with(['incident', 'annonce.photo', 'annonce.ville']);

        $tenantQuery->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->whereHas('annonce', fn($a) => $a->where('titre_annonce', 'like', $term));
        });
        
        $tenantQuery->when($request->filled('statut'), function ($q) use ($request) {
            $q->whereHas('incident', fn($i) => $i->where('statut_incident', $request->statut));
        });

        $ownerQuery = auth()->user()->demandesReservations()
            ->has('incident')
            ->with(['incident', 'annonce.photo', 'particulier.utilisateur']);

        $ownerQuery->when($request->filled('search'), function ($q) use ($request) {
            $term = '%' . $request->search . '%';
            $q->whereHas('annonce', fn($a) => $a->where('titre_annonce', 'like', $term));
        });

        $ownerQuery->when($request->filled('statut'), function ($q) use ($request) {
            $q->whereHas('incident', fn($i) => $i->where('statut_incident', $request->statut));
        });

        $myIncidents = $tenantQuery->latest('date_debut_resa')
            ->paginate(3, ['*'], 'my_page')
            ->withQueryString();

        $propertyIncidents = $ownerQuery->latest('date_debut_resa')
            ->paginate(3, ['*'], 'prop_page')
            ->withQueryString();

        return view('dashboard/centre-d-aide', [
            'myIncidents' => $myIncidents,
            'propertyIncidents' => $propertyIncidents
        ]);
    }
}

