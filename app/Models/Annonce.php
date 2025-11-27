<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Annonce;

class Annonce extends Model
{
    use HasFactory;
    protected $table = "annonce";
    protected $primaryKey = "idannonce";
    public $timestamps = false;

    public function ville() {
        return $this->belongsTo(Ville::class, "idville");
    }

    public function type_hebergement() {
        return $this->belongsTo(TypeHebergement::class, "idtypehebergement");
    }

    public function photo() {
        return $this->hasMany(Photo::class, "idannonce");
    }

    public function calendrier() {
        return $this->hasMany(Calendrier::class, "idannonce");
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, "idutilisateur");
    }
}
