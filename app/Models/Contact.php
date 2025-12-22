<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 

class Contact extends Model
{
    use HasFactory;

    protected $table = "contact";
    public $timestamps = false;

    protected $fillable = [
        'demandeur_id',
        'receveur_id'
    ];

    public function demandeur() 
    {
        return $this->belongsTo(Utilisateur::class, 'demandeur_id', 'idutilisateur');
    }

    public function receveur()
    {
        return $this->belongsTo(Utilisateur::class, 'receveur_id', 'idutilisateur');
    }

    public function getOtherUserAttribute()
    {
        $myId = Auth::user()->idutilisateur;
        return $this->demandeur_id == $myId ? $this->receveur : $this->demandeur;
    }

    public function messages()
    {
        return Message::where(function($query) {
             $query->where('expediteur_id', $this->demandeur_id)->where('destinataire_id', $this->receveur_id);
        })->orWhere(function($query) {
             $query->where('expediteur_id', $this->receveur_id)->where('destinataire_id', $this->demandeur_id);
        })->orderBy('date_envoi', 'asc');
    }
}