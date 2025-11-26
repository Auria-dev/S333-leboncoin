<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $table = "date";
    protected $primaryKey = "iddate";
    public $timestamps = false;

    public function calendrier() {
        return $this->hasMany(Calendrier::class, "iddate");
    }
}
