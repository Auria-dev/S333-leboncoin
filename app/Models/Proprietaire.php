<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    use HasFactory;
    protected $table = "proprietaire";
    protected $primaryKey = "idproprietaire";
    public $timestamps = false;

    public function ville() {
        return $this->belongsTo(Ville::class, "idville");
    }
}
