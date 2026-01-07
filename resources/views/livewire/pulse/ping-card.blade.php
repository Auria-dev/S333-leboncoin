<div wire:poll.5s 
     class="shadow-lg rounded-lg p-6 h-full w-full" 
     style="background-color: #1f2937; color: white;">
    
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold" style="color: white;">Ping </h2>
        <div wire:loading class="text-xs text-yellow-400">
            Actualisation...
        </div>
    </div>

    <div class="text-center mb-2" style="color: #d1d5db;"> <span>Temps de réponse</span>
    </div>

    <div class="text-4xl text-center font-mono font-bold mb-4">
        @if($pingTime == 'Erreur' || $pingTime == 'N/A')
            <span style="color: #ef4444;">Erreur</span> @else
            <span style="color: #22c55e;">{{ $pingTime }}</span> 
        @endif
    </div>

    <div class="text-center text-xs text-gray-400">
        Vers IP : {{ $host }}
    </div>

</div>