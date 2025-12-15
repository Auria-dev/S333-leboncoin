<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAdministrateur extends Model
{
    use HasFactory;
    protected $table = "type_admnistrateur";
    protected $primaryKey = "idtypeadmin";
    public $timestamps = false;

}
