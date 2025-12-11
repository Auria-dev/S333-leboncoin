<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePaiement extends Model
{
    use HasFactory;
    protected $table = "type_paiement";
    protected $primaryKey = "idtypepaiement";
    public $timestamps = false;
}
