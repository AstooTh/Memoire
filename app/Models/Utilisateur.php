<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'matricule',
        'email',
        'mdp',
        'role',
        'etat',
        'emplacement_id',
        'token'
        
    ];
}
