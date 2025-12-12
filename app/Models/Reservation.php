<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = "reservation";
    protected $primaryKey = "idreservation";
    public $timestamps = false;

    protected $fillable = [
        "date_debut_resa",
        "date_fin_resa",
        "idlocataire",
        "idannonce",
        "idavis",
        "nb_adultes",
        "nb_enfants",
        "nb_bebes",
        "nb_animaux",
        "statut_reservation"
    ];

    public function avis() {
        return $this->belongsTo(Avis::class, "idavis");
    }

    public function incident() {
        return $this->hasOne(Incident::class, "idreservation");
    }

    public function particulier() {
        return $this->belongsTo(Particulier::class, "idlocataire");
    }

    public function annonce() {
        return $this->belongsTo(Annonce::class, "idannonce");
    }

    // Statut reservation values:
    // 'en attente' => paid for, waiting for approval
    // 'validée' => approved, awaiting stay
    // 'en cours' => ongoing stay
    // 'complétée' => user has completed their stay

    // 'annulée' => canceled by user
    // 'refusée' => request denied
}
