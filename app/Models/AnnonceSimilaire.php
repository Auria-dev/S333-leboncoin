<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnnonceSimilaire;

class AnnonceSimilaire extends Model
{
    use HasFactory;
    protected $table = "annonce_similaire";
    // protected $primaryKey = ["idannonce", "idsimilaire"];
    public $timestamps = false;
}
