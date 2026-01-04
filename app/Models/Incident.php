<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;
    protected $table = "incident";
    protected $primaryKey = "idincident";
    public $timestamps = false;

    protected $fillable = [
        'idreservation',
        'description_incident',
        'date_signalement',
        'reponse_incident',
        'statut_incident',
    ];

    public function reservation() {
        return $this->hasOne(Reservation::class, 'idreservation', 'idreservation');
    }
}
