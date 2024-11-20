<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Produit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenteController extends Controller
{
    public function addVente(Request $request){
        Log::info('Données reçues : ', $request->all());
        $request->validate([
            'clientName' => 'required|string',
            'date' => 'required|date',
            'produits' => 'required|array',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.qte' => 'required|integer|min:1',
            'userId' => 'required|exists:utilisateurs,id',
            'emplacementId' => 'required|exists:emplacements,id',
        ]);
        foreach ($request->produits as $produit) {
            Vente::create([
                'nomClient' => $request->clientName,
                'dateVente' => $request->date,
                'produit_id' => $produit['id'],
                'qte' => $produit['qte'],
                'montant' => $produit['prix'] * $produit['qte'], 
                'emplacement_id' => $request->emplacementId,
                'utilisateur_id' => $request->userId,
            ]);
            $produitEntity = Produit::find($produit['id']);
            $produitEntity->qte -= $produit['qte'];
            $produitEntity->save();
        }
    
        return response()->json(['message' => 'Vente enregistrée avec succès']);
    }

    public function getVentes($userId, $date){
    $ventes = DB::table('ventes')
    ->join('produits', 'ventes.produit_id', '=', 'produits.id')
    ->select('ventes.*', 'produits.nomProduit as produitNom')
    ->where('ventes.utilisateur_id', $userId)
    ->whereDate('ventes.dateVente', $date)
    ->get();
    return response()->json($ventes);
}
}
