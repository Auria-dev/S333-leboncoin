<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    
    protected $table = "departement";
    protected $primaryKey = "iddepartement";
    public $timestamps = false;

    public function region() {
        return $this->belongsTo(Region::class, "idregion");
    }
    
    public function villes() {
        return $this->hasMany(Ville::class, "idville");
    }
}
