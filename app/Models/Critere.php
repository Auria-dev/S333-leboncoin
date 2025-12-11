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
		'date_recherche',
		'nb_voyageurs',
		'prix_min',
		'prix_max',
		'mot_clef',
		'date_debut_recherche',
		'date_fin_recherche',
    'type_hebergement'
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'recherche', 'idcritere', 'idutilisateur');
    }
}
