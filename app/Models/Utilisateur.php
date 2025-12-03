<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false; 

    protected $fillable = [
        'idville',
        'nom_utilisateur',
        'prenom_utilisateur',
        'mot_de_passe',
        'telephone',
        'mail',
        'adresse_utilisateur',
        'date_creation'
    ];

    public function getAuthPassword() {
        return $this->mot_de_passe;
    }

    
    public function ville() {
        return $this->belongsTo(Ville::class, "idville");
    }

    public function annonce() {
        return $this->hasMany(Annonce::class, "idproprietaire");
    }

    public function reservation() {
        return $this->hasMany(Reservation::class, "idlocataire");
    }

    public function particulier() {
        return $this->hasOne(Particulier::class, 'idparticulier', 'idutilisateur');
    }

    public function entreprise() {
        return $this->hasOne(Entreprise::class, 'identreprise', 'idutilisateur');
    }

    public function getTypeCompte() {
        if ($this->particulier) {
            return 'particulier';
        }
        
        if ($this->entreprise) {
            return 'entreprise';
        }

        return null;
    }

    public function getTypeParticulier() {
        if ($this->entreprise) {
            return 'Entreprise';
        }

        if ($this->particulier) {
            switch ($this->particulier->code_particulier) {
                case 0: return 'Locataire';
                case 1: return 'Propriétaire';
                case 2: return 'Locataire & Propriétaire';
                default: return 'Particulier';
            }
        }

        return 'Utilisateur standard';
    }

    public function isEntreprise() {
        return $this->getTypeCompte() === 'entreprise';
    }

    public function isParticulier() {
        return $this->getTypeCompte() === 'particulier';
    }
}