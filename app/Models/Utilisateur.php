<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


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
        'date_creation',
        'photo_profil'
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
        return $this->hasMany(Reservation::class, "idlocataire")
        ->orderByRaw("
            CASE statut_reservation
                WHEN 'en attente' THEN 1
                WHEN 'en cours' THEN 2
                WHEN 'validée' THEN 3
                WHEN 'complétée' THEN 4
                WHEN 'annulée' THEN 5
                WHEN 'refusée' THEN 6
                ELSE 7
            END ASC
        ")
        ->orderBy('date_demande', 'desc');
    }

    public function demandesReservations() {
        return $this->hasManyThrough(Reservation::class, Annonce::class, 'idproprietaire', 'idannonce', 'idutilisateur', 'idannonce')
        ->orderByRaw("
            CASE statut_reservation
                WHEN 'en attente' THEN 1
                WHEN 'en cours' THEN 2
                WHEN 'validée' THEN 3
                WHEN 'complétée' THEN 4
                WHEN 'annulée' THEN 5
                WHEN 'refusée' THEN 6
                ELSE 7
            END ASC
        ")
        ->orderBy('date_demande', 'desc');
    }

    public function recherche() {
        return $this->belongsToMany(Critere::class, 'recherche', 'idutilisateur', 'idcritere')->withPivot('titre_recherche');
    }

    public function favoris() {
        return $this->belongsToMany(Annonce::class, 'favoris', 'idutilisateur', 'idannonce');
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

    public function administrateur() {
        return $this->hasOne(Administrateur::class, 'idadmin');
    }

    public function getTypeParticulier() {
        if ($this->administrateur) {
            return $this->administrateur->typeAdmin->nom_type_admin;
        }
        
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

    public function displayName() {
        $nom = Str::upper($this->nom_utilisateur);
        $prenom = Str::ucfirst($this->prenom_utilisateur);
        $id = $this->idutilisateur;
       
        if ((DB::table('particulier')->where('idparticulier', $id)->exists() && $this->particulier->piece_identite != null && $this->particulier->Utilisateur->telephone_verifie) || (DB::table('entreprise')->where('identreprise', $id)->exists() && $this->entreprise->numsiret != null)) {
        
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>';
            return $nom . " " . $prenom . " " . $svg;
        }
        else {
            return $nom . " " . $prenom;
        }
    }
    
    public function cartesBancaires() {
        return $this->hasMany(CarteBancaire::class, 'idutilisateur')->where('est_sauvegardee', true);
    }
}