<?php

namespace App\Livewire\Pulse;

use Livewire\Component;

class PingCard extends Component
{
    
    public $host = '51.83.36.122';
    public $pingTime = '---';

    // On supprime la fonction mount() qui ne s'exécute qu'une seule fois
    // et on met la logique directement dans render() pour que ça s'actualise
    public function render()
    {
        $this->pingTime = $this->getPingTime($this->host);
        return view('livewire.pulse.ping-card');
    }

    private function getPingTime($host): string
    {
        // Commande adaptée automatiquement pour Windows (-n) ou Linux/Mac (-c)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = "ping -n 1 " . $host;
        } else {
            $cmd = "ping -c 1 " . $host;
        }

        $output = shell_exec($cmd);

        // Regex améliorée pour capturer "temps=" (FR) ou "time=" (EN) ou "<1ms"
        // Exemple Windows FR : "Réponse de 8.8.8.8 : octets=32 temps=14 ms TTL=116"
        if (preg_match('/(time|temps)[=<]\s*([\d\.,]+)/i', $output, $matches)) {
            return $matches[2] . ' ms';
        }

        return 'Erreur'; // Si le ping échoue
    }
}