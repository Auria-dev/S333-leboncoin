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
        'nom_utilisateur',
        'prenom_utilisateur',
        'mail',
        'mdp',
        'idville',
        'telephone',
        'adresse_utilisateur'
    ];

    public function getAuthPassword() {
        return $this->mdp;
    }
}