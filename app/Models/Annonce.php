<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Annonce;

class Annonce extends Model
{
    use HasFactory;
    protected $table = "annonce";
    protected $primaryKey = "idannonce";
    public $timestamps = false;

    public function ville() {
        return $this->belongsTo(Ville::class, "idville");
    }

    public function type_hebergement() {
        return $this->belongsTo(TypeHebergement::class, "idtypehebergement");
    }

    public function photo() {
        return $this->hasMany(Photo::class, "idannonce");
    }

    public function calendrier() {
        return $this->hasMany(Calendrier::class, "idannonce");
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, "idproprietaire");
    }

    public function reservation() {
        return $this->hasMany(Reservation::class, "idannonce");
    }

    // public function similaire() {
    //     return $this->hasMany(AnnonceSimilaire::class, "idannonce");
    // }

    public function equipement() {
        return $this->belongsToMany(Equipement::class, 
        "equipe", 
        "idannonce", 
        "idequipement")->using(Equipe::class);
    }

    public function service() {
        return $this->belongsToMany(Service::class, 
        "propose", 
        "idannonce", 
        "idservice")->using(Propose::class);
    }

    public function moyenneAvisParAnnonce() {
        $reservations = $this->reservation;
        if($reservations->isEmpty()) {
            return ['moyenne'=>0, 'nbAvis'=>0];
        }
        $sommeNotes = 0;
        $nbAvis = 0;
        $moyenne = 0;
        foreach($reservations as $resa) {
            if($resa->avis && isset($resa->avis->note)) {
                $sommeNotes += $resa->avis->note;
                $nbAvis++;
            }
        }
        if($nbAvis > 0) {
            $moyenne = $sommeNotes / $nbAvis;
        }
        
        return ['moyenne'=>$moyenne, 'nbAvis'=>$nbAvis, 'sommeNotes'=>$sommeNotes];
    }
}
