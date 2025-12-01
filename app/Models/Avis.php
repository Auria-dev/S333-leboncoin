<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Avis;

class Avis extends Model
{
    use HasFactory;
    protected $table = "avis";
    protected $primaryKey = "idavis";
    public $timestamps = false;
}
