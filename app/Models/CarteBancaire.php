<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteBancaire extends Model
{
    use HasFactory;
    protected $table = "carte_bancaire";
    protected $primaryKey = "idcartebancaire";
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'numcarte',
        'dateexpiration',
        'est_sauvegardee',
    ];
}
