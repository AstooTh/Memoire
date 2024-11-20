<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;
    protected $fillable = [
        'dateVente',
        'produit_id',
        'qte',
        'montant',
        'nomClient',
        'emplacement_id',
        'utilisateur_id'
    ];
}
