<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;
    protected $table = "calendrier";
    protected $primaryKey = ["iddate", "idannonce"];
    public $timestamps = false;

    public function date() {
        return $this->belongsTo(Date::class, "iddate");
    }

    public function annonce() {
        return $this->belongsTo(Annonce::class, "idannonce");
    }
}
