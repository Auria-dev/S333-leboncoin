<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    use HasFactory;
    protected $table = "administrateur";
    protected $primaryKey = "idadmin";
    public $timestamps = false;

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, "idutilisateur");
    }

    public function typeAdmin() {
        return $this->belongsTo(TypeAdministrateur::class, "idtypeadmin");
    }
}
