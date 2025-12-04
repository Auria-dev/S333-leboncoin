<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;
    protected $table = 'critere';
    protected $primaryKey = 'idcritere';
    public $timestamps = false;

    protected $fillable = [
        'idville',
        'date_recherche',
        'date_debut_recherche',
        'date_fin_recherche',
        'mot_clef'
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'recherche', 'idcritere', 'idutilisateur');
    }
}
