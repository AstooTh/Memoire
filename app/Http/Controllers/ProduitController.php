<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produit;

class ProduitController extends Controller
{
    public function index(){
        $produits = Produit::all();
        return response()->json($produits);
    }

    public function updateProduit(Request $request, $id)
    {
      $produit = Produit::findOrFail($id);
    
    // Valider les données entrantes
    $request->validate([
        'nomProduit' => 'required',
        'description' => 'required',
        'prix' => 'required',
        'qte' => 'required',
        'qteCritique' => 'required',
        'categorie_id' => 'required',
        // Ajoutez d'autres validations ici
    ]);
    // Mettre à jour les informations de l'utilisateur
    $produit->nomProduit = $request->nomProduit;
    $produit->description = $request->description;
    $produit->prix = $request->prix;
    $produit->qte = $request->qte;
    $produit->qteCritique = $request->qteCritique;
    $produit->categorie_id = $request->categorie_id;
    $produit->update();
    return response()->json( $produit); // Retourner l'utilisateur mis à jour
    }

    public function getProduit(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        return response()->json($produit); // Retourner l'utilisateur mise à jour
    }

   public function deleteProduit($id)
   {
    $produit = Produit::find($id);
       if (! $produit) {
           return response()->json(['message' => 'Produit non trouvé'], 404);
       }
       // Supprimer l'utilisateur
       $produit->delete();
       return response()->json(['message' => 'Produit supprimé avec succès'], 200);
   }

   public function addProduit(Request $request){
      $request->validate([
          'nom'=>'required',
          'description'=>'required',
          'prix'=>'required',
          'qte'=>'required',
          'qteCritique'=>'required'
      ]);

       // Récupérer le dernier matricule
       $lastProduit = Produit::orderBy('code', 'desc')->first();
       $lastCode = $lastProduit ? $lastProduit->code : 'P000'; // Défaut à U000 si aucun utilisateur
       // Extraire le numéro et incrémenter
       $lastNumber = intval(substr($lastCode, 1)); // Extraire la partie numérique
       $newNumber = $lastNumber + 1; // Incrémenter
       // Créer le nouveau matricule au format UXXX
       $newCode = 'P' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // Format UXXX
       
      $produit = new Produit();
      $produit->nomProduit = $request->nom;
      $produit->description = $request->description;
      $produit->prix = $request->prix;
      $produit->code = $newCode;
      $produit->qte = $request->qte;
      $produit->qteCritique = $request->qteCritique;
      $produit->categorie_id = $request->categorie_id;
      $produit->emplacement_id = $request->emplacementId;
      $produit->save();
      return response()->json(['message' => 'Ajout réussie', 'produit' => $produit]);
  }

  public function getProduitByEmplacementId($emplacementId)
    {
        $produits = Produit::where('emplacement_id', $emplacementId)->get();
        // Retourner les produits sous forme de JSON
        return response()->json($produits); // Retourner l'utilisateur mis à jour
    }

}
