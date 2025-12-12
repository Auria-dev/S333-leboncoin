<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $table = "paiement";
    protected $primaryKey = "idpaiement";
    public $timestamps = false;

    protected $fillable = [
        'idreservation',
        'idcartebancaire',
        'montant_paiement',
        'date_paiement',
        'statut_paiement',
        'ref_transaction'
    ];

    function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }

    function cartebancaire()
    {
        return $this->belongsTo(CarteBancaire::class, 'idcartebancaire');
    }
}
