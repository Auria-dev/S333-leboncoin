<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = "annonce";
    protected $primaryKey = "idannonce"; // Indispensable pour que Laravel retrouve l'annonce créée
    public $timestamps = false;

    protected $fillable = [
        'idtypehebergement',
        'idproprietaire',
        'idville',
        'titre_annonce',
        'prix_nuit',
        'nb_nuit_min',
        'nb_bebe_max',
        'nb_personnes_max',
        'nb_animaux_max',
        'adresse_annonce',
        'description_annonce',
        'date_publication',
        'heure_arrivee',
        'heure_depart',
        'nombre_chambre',
        'longitude',
        'latitude',
        'photo_profil',
        'est_garantie'
    ];

    // --- RELATIONS ---

    public function equipement() {
        return $this->belongsToMany(
            Equipement::class, 
            'equipe',        // Nom de TA table pivot
            'idannonce',     // Clé annonce
            'idequipement'   // Clé equipement
        );
    }

    public function service() {
        return $this->belongsToMany(
            Service::class, 
            'propose',       // Nom de TA table pivot
            'idannonce', 
            'idservice'
        );
    }

    // --- AUTRES RELATIONS (Inchangées) ---

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

    // Gestion des annonces similaires (Ta logique complexe)
    public function similaires_id_annonce() {
        return $this->belongsToMany(Annonce::class, "annonce_similaire", "idannonce", "idsimilaire");
    }

    public function similaires_id_similaire() {
        return $this->belongsToMany(Annonce::class, "annonce_similaire", "idsimilaire", "idannonce");
    }

    public function getSimilairesAttribute()
    {
        $similairesA = $this->similaires_id_annonce;
        $similairesS = $this->similaires_id_similaire;
        return $similairesA->merge($similairesS);
    }

    // Helper pour la moyenne des avis
    public function moyenneAvisParAnnonce() {
        $reservations = $this->reservation;
        $sommeNotes = 0;
        $nbAvis = 0;
        $moyenne = 0;

        if($reservations->isEmpty()) {
            return ['moyenne'=> $moyenne, 'nbAvis'=> $nbAvis, 'sommeNotes'=>$sommeNotes];
        }
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

    public function avisValides()
    {
        return $this->hasManyThrough(
            Avis::class, 
            Reservation::class,
            'idannonce',      
            'idreservation',  
            'idannonce',      
            'idreservation'   
        )
        ->where('statut_avis', 'valide') 
        ->orderBy('date_depot', 'desc');
    }
}