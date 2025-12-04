<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recherche extends Model
{
    use HasFactory;

    protected $table = "recherche";
    protected $primaryKey = "idutilisateur"; // super legit way of not making laravel kill itself when inserting values. may this NOT come back to bite me later on.
    public $timestamps = false;

    protected $fillable = ['idutilisateur', 'idcritere', 'titre_recherche'];
}
