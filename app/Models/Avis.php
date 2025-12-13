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

    protected $fillable = [
        'idutilisateur',
        'idreservation',
        'note',
        'commentaire',
        'date_depot',
        'reponse_avis',
        'statut_avis'
        
        
    ];


    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'IDRESERVATION', 'IDRESERVATION');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idutilisateur', 'idutilisateur');
    }
}