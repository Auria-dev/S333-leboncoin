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
        return $this->mdp;
    }

    
    public function ville() {
        return $this->belongsTo(Ville::class, "idville");
    }

    public function annonce() {
        return $this->hasMany(Annonce::class, "idproprietaire");
    }
}