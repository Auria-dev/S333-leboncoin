<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;
    protected $table = "calendrier";
    protected $primaryKey = ["iddate", "idannonce"];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddate',
        'idannonce',
        'idutilisateur',
        'code_dispo',
    ];

    public function date() {
        return $this->belongsTo(Date::class, "iddate");
    }

    public function annonce() {
        return $this->belongsTo(Annonce::class, "idannonce");
    }


    //
    //
    // https://stackoverflow.com/questions/36332005/laravel-model-with-two-primary-keys-update
    //
    //
    
    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
