<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Propose extends Pivot
{
    use HasFactory;
    protected $table = "propose";
    // protected $primaryKey = ["idannonce", "idservice"];
    public $timestamps = false;

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'idservice');
    }
}
