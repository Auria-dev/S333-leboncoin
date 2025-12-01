<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecteurActivite extends Model
{
    use HasFactory;

    protected $table = "secteur_activite";
    protected $primaryKey = "idsecteur";
    public $timestamps = false;
}
