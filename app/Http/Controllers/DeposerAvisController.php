
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avis;
use App\Models\Reservation; 
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{

    public function create($idReservation)
    {
        $reservation = Reservation::findOrFail($idReservation);

        if ($reservation->IDLOCATAIRE !== Auth::user()->IDUTILISATEUR) {
            return redirect('/profile')->with('error', 'Action non autorisée.');
        }

        if ($reservation->IDAVIS !== null) {
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

        $avis = Avis::create([
            'IDUTILISATEUR' => Auth::user()->IDUTILISATEUR,
            'IDRESERVATION' => $idReservation,
            'NOTE' => $request->input('note'),
            'COMMENTAIRE' => $request->input('commentaire'),
            'DATE_DEPOT' => Carbon::now()
        ]);

        $reservation->update(['IDAVIS' => $avis->IDAVIS]);

        return redirect('/profile')->with('success', 'Merci pour votre avis !');
    }
}