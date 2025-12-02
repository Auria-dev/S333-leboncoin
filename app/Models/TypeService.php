<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeService;

class TypeService extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "type_service";
    protected $primaryKey = "idtypeservice";
    public $timestamps = false;
}
