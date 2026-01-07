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

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => "Tu es le guide de [Nom du Site]. 
                    Règles : 
                    1. Ne cherche pas de maisons/apparts. 
                    2. Pour chercher : indique d'aller sur /recherche.
                    3. Pour publier : indique /deposer. 
                    4. Réponds toujours de manière très courte (2 phrases max)."
                ],
                ['role' => 'user', 'content' => $question],
            ],
        ]);

        return $response->choices[0]->message->content;
    }
}
