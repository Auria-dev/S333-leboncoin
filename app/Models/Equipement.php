<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipement;

class Equipement extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "equipement";
    protected $primaryKey = "idequipement";
    public $timestamps = false;

    // public function categorieEquipement() {
    //     return $this->belongsTo(CategorieEquipement::class, "idcategorie");
    // }

    public function annonce() {
        return $this->belongsToMany(Annonce::class, 
        "equipe", 
        "idequipement",
        "idannonce")->using(Equipe::class);
    }

    protected $fillable = [
        "idcategorie",
        "nom_equipement"
    ];
}
