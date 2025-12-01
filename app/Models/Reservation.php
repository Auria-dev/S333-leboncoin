<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

class Reservation extends Model
{
    use HasFactory;
    protected $table = "reservation";
    protected $primaryKey = "idreservation";
    public $timestamps = false;

    public function avis() {
        return $this->belongsTo(Avis::class, "idavis");
    }
}
