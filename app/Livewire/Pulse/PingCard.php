<?php

namespace App\Livewire\Pulse;

use Livewire\Component;
use Illuminate\Support\Facades\View;

class PingCard extends Component
{
    public $pingTime;
    public $host = '51.83.36.122'; 

    public function mount(): void
    {
        $this->pingTime = $this->getPingTime($this->host);
    }

    private function getPingTime($host): string
    {
        // Exécute la commande ping (commande système)
        // Sur Windows remplacez "-c 1" par "-n 1"
        $output = shell_exec("ping -n 1 " . $host); 

        // Analyse le résultat pour trouver le temps 
        
        if (preg_match('/temps=([\d\.]+)/', $output, $matches)) {
            return $matches[1] . ' ms';
        }

        return 'N/A';
    }

    public function render()
    {
        return view('livewire.pulse.ping-card');
    }
}
