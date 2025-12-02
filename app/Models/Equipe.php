<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Equipe extends Pivot
{
    use HasFactory;
    protected $table = "equipe";
    // protected $primaryKey = ["idannonce", "idequipement"];
    public $timestamps = false;

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce');
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'idequipement');
    }
}
