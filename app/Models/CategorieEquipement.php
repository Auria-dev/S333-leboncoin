<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategorieEquipement;

class CategorieEquipement extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "categorie_equipement";
    protected $primaryKey = "idcategorie";
    public $timestamps = false;
}
