<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MsgController extends Controller
{
    public function afficher_messagerie($id = null)
    {
        $userId = Auth::user()->idutilisateur;
        $contacts = Contact::where('demandeur_id', $userId)->orWhere('receveur_id', $userId)->with(['demandeur', 'receveur'])->get();
        $selectedContact = $id ? $contacts->find($id) : $contacts->first();

        $messages = [];
        if ($selectedContact) {
            if ($selectedContact->demandeur_id !== $userId && $selectedContact->receveur_id !== $userId) {
                abort(403);
            }
            $messages = $selectedContact->messages()->with('expediteur')->get();
        }

        return view("messagerie", compact('contacts', 'selectedContact', 'messages'));
    }

    public function envoyer_message(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contact,id',
            'contenu' => 'required|string'
        ]);

        $userId = Auth::user()->idutilisateur;
        $contact = Contact::findOrFail($request->contact_id);

        $destinataireId = ($contact->demandeur_id == $userId) ? $contact->receveur_id : $contact->demandeur_id;

        Message::create([
            'expediteur_id' => $userId,
            'destinataire_id' => $destinataireId,
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('messagerie', ['id' => $contact->id]);
    }

    public function creer_contact(Request $request)
    {
        $request->validate([
            'receveur_id' => 'required|exists:utilisateur,idutilisateur'
        ]);

        $myId = Auth::user()->idutilisateur;
        $targetId = $request->receveur_id;

        if ($myId == $targetId) {
            return back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
        }

        $existingContact = Contact::where(function($q) use ($myId, $targetId) {
            $q->where('demandeur_id', $myId)->where('receveur_id', $targetId);
        })->orWhere(function($q) use ($myId, $targetId) {
            $q->where('demandeur_id', $targetId)->where('receveur_id', $myId);
        })->first();

        if ($existingContact) {
            return redirect()->route('messagerie', ['id' => $existingContact->id]);
        }

        $newContact = Contact::create([
            'demandeur_id' => $myId,
            'receveur_id' => $targetId
        ]);

        return redirect()->route('messagerie', ['id' => $newContact->id]);
    }
}