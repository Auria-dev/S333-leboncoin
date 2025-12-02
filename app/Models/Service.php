<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;

class Service extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "service";
    protected $primaryKey = "idservice";
    public $timestamps = false;

    public function annonce() {
        return $this->belongsToMany(Annonce::class, 
        "service", 
        "idservice",
        "idannonce")->using(Propose::class);
    }

    // public function typeService() {
    //     return $this->belongsTo(TypeService::class, "idtypeservice");
    // }
}
