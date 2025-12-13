<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
 
class DeposerAvisController extends Controller
{

    public function create($idReservation)
    {
        $reservation = Reservation::findOrFail($idReservation);
 
        if ($reservation->idlocataire !== Auth::user()->idutilisateur) {
            return redirect('/profile')->with('error', 'Action non autorisée.');
        }

        if (!$reservation->estTerminee()) {
            return redirect('/profile')->with('error', 'Attendez la fin du séjour pour donner votre avis.');
        }
 
        if ($reservation->idavis !== null) {
            return redirect('/profile')->with('error', 'Vous avez déjà noté ce séjour.');
        }
 
        return view('deposer-avis', compact('reservation'));
    }
 
    public function store(Request $request, $idReservation)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|max:1000',
        ]);
 
        $reservation = Reservation::findOrFail($idReservation);
 
        if ($reservation->idlocataire !== Auth::user()->idutilisateur || !$reservation->estTerminee()) {
            abort(403);
        }
 
        $avis = Avis::create([
            'idutilisateur' => Auth::id(),
            'idreservation' => $idReservation,
            'note' => $request->input('note'),
            'commentaire' => $request->input('commentaire'),
            'date_depot' => Carbon::now(),
            
        ]);
 
        $reservation->update(['idavis' => $avis->idavis]);
 
        return redirect('/profile')->with('success', 'Avis envoyé ! Il sera publié après validation.');
    }
}