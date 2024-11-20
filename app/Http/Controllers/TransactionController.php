<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Produit;
use Carbon\Carbon;


class TransactionController extends Controller
{
    public function addTransaction(Request $request){
        $request->validate([
            'produit_id'=>'required',
            'origine_id'=>'required',
            'destination_id'=>'required',
            'qte'=>'required'
        ]);
        $produit = Produit::find($request->produit_id);
        if ($produit->qte > $request->qte) {
        $transaction = new Transaction();
        $transaction->produit_id = $request->produit_id;
        $transaction->origine = $request->origine_id;
        $transaction->destination = $request->destination_id;
        $transaction->qte = $request->qte;
        $transaction->type = '';
        $transaction->date = Carbon::now();
        $transaction->save();
        $produit->qte -= $request->qte; // Soustraire la quantité
        $produit->update();
        return response()->json(['message' => 'Ajout réussie', 'transaction' => $transaction]);
        } else {
            // Si la quantité est insuffisante
            return response()->json([
                'error' => 'La quantité demandée est supérieure à la quantité disponible.',
            ], 400);
        }
        }
  
}

