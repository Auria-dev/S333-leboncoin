<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reversement extends Model
{
    use HasFactory;
    protected $table = "reversement";
    protected $primaryKey = "idreversement";
    public $timestamps = false;

    protected $fillable = [
        'idreservation',
        'montant_reverse',
        'date_reversement',
        'iban_beneficiaire',
        'statut_virement'
    ];

    function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    function beneficiaire()
    {
        return $this->belongsTo(Utilisateur::class, 'iban_beneficiaire', 'iban');
    }
}
