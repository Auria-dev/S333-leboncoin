<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use OpenAI;

class BotManController extends Controller
{
    public function handle()
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create([]);

        $botman->hears('{message}', function($bot, $message) {
            $reply = $this->askOpenAI($message);
            $bot->reply($reply);
        });

        $botman->listen();
    }

    private function askOpenAI($question)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        // On construit la base de connaissance extraite du HTML
        $siteKnowledge = <<<EOT
        CONTEXTE DU SITE :
        Tu es le guide virtuel officiel du site. Le site permet de louer des biens (particuliers et entreprises).
        Voici la structure et les procédures du site que tu dois connaître par cœur :

        1. NAVIGATION & COMPTE :
        - Inscription/Connexion : Bouton "Se connecter" (haut droite). Inscription possible par Email ou Google.
        - Espace Compte : Bouton "Mon compte" (haut droite). Contient : Infos perso, Annonces, Locations, Réservations, Messagerie, Favoris.
        - Sécurité : Modification mot de passe et 2FA (Google Auth) dans "Mon compte" > "Modifier mon compte".
        - Vérification d'identité : Obligatoire pour déposer. Se fait via upload PDF dans l'espace compte ou au premier dépôt. Badge "Bouclier" = compte vérifié.

        2. GESTION DES ANNONCES (Propriétaires) :
        - Déposer : Bouton "Déposer une annonce" (haut milieu).
        - Modifier/Supprimer : Aller dans "Mes annonces". Icône Crayon pour modifier, Corbeille pour supprimer (irréversible).
        - Photos : Format JPG/PNG, max 2Mo. Pas d'enfants ni coordonnées dessus.

        3. RÉSERVATIONS & PAIEMENTS (Locataires) :
        - Réserver : Sur une annonce, choisir dates (calendrier) > Bouton "Réserver".
        - Paiement : Carte bancaire. Possible en 3x ou 4x. Débit effectué le lendemain de la fin du séjour.
        - Annuler : Aller dans "Mes réservations". Possible uniquement si statut "En attente".
        - Prix : Loyer + Frais service + (Nb personnes * Taxe séjour).

        4. MESSAGERIE & INCIDENTS :
        - Messagerie : Bouton "Messagerie" (haut milieu) ou "Contacter" depuis une réservation.
        - Incident : Aller sur la réservation > "Déclarer un incident". Le paiement est suspendu tant que l'incident n'est pas résolu (soit par accord amiable, soit par le support).
        - Avis : Bouton "Noter ce séjour" dans "Mes réservations" (une fois séjour fini).

        RÈGLES DE RÉPONSE :
        1. Tu es poli et serviable.
        2. Tes réponses doivent être COURTES (2-3 phrases maximum).
        3. Base-toi UNIQUEMENT sur les infos ci-dessus. Si tu ne sais pas, dis-le.
        4. Pour guider l'utilisateur, utilise le vocabulaire visuel du site (ex: "Clique sur le bouton en haut à droite...").
        EOT;

// 
//
// RAG
// write search query from user input
// Apply it server side; return first outcome / "not found" custom message (not ai)
// 
// 



        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => $siteKnowledge
                ],
                [
                    'role' => 'user', 
                    'content' => $question
                ],
            ],
            'temperature' => 0.3, 
        ]);

        return $response->choices[0]->message->content;
    }
}
