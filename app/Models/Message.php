<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "message"; 
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'contenu',
        'date_envoi'
    ];

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id', 'idutilisateur');
    }

    public function receveur()
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id', 'idutilisateur');
    }
}