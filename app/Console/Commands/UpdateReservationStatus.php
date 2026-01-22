<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateReservationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-reservation-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates reservation statuses based on start and end dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        $expiredCount = Reservation::query()
            ->where('statut_reservation', 'en attente')
            ->whereDate('date_debut_resa', '<=', $today)
            ->update(['statut_reservation' => 'refusée']);
            
        $startedCount = Reservation::query()
            ->where('statut_reservation', 'validée')
            ->whereDate('date_debut_resa', '<=', $today)
            ->update(['statut_reservation' => 'en cours']);

        $completedCount = Reservation::query()
            ->where('statut_reservation', 'en cours')
            ->whereDate('date_fin_resa', '<', $today)
            ->update(['statut_reservation' => 'complétée']);

        $this->info("Refused: $expiredCount | Started: $startedCount | Completed: $completedCount");
    }
}
