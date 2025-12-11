<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Particulier extends Model
{
    use HasFactory;
    protected $table = "particulier";
    protected $primaryKey = "idparticulier";
    public $timestamps = false;

    protected $fillable = [
        'piece_identite'
    ];
    
    public function Utilisateur() {
        return $this->belongsTo(Utilisateur::class, "idparticulier");
    }
}
