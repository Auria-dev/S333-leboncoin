<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Avis;

class Avis extends Model
{
    use HasFactory;
    protected $table = "avis";
    protected $primaryKey = "idavis";
    public $timestamps = false;
}

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'IDRESERVATION', 'IDRESERVATION');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'IDUTILISATEUR', 'IDUTILISATEUR');
    }