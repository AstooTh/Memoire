<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Fournisseur;

class CommandeController extends Controller
{
    public function index(){
        $commandes = Commande::where('status', 'En attente')->get();

    // Récupérer les informations des produits et des fournisseurs
    foreach ($commandes as $commande) {
        $produit = Produit::find($commande->produit_id);
        $fournisseur = Fournisseur::find($commande->fournisseur_id);

        $commande->produit_nom = $produit ? $produit->nomProduit : null; // Ajoute le nom du produit
        $commande->fournisseur_nom = $fournisseur ? $fournisseur->nom : null; // Ajoute le nom du fournisseur
    }

    return response()->json($commandes);
    }

    public function getCommande(Request $request, $id)
    {
        $commandes = Commande::findOrFail($id);
            $produit = Produit::find($commandes->produit_id);
            $fournisseur = Fournisseur::find($commandes->fournisseur_id);
    
            $commandes->produit_nom = $produit ? $produit->nomProduit : null; // Ajoute le nom du produit
            $commandes->fournisseur_nom = $fournisseur ? $fournisseur->nom : null; // Ajoute le nom du fournisseur
        return response()->json($commandes); // Retourner l'utilisateur mis à jour
    }

    public function updateCommande(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
    
        // Valider les données entrantes
        $request->validate([
            'fournisseur_id'=>'required|exists:fournisseurs,id',
            'produit_id'=>'required|exists:produits,id',
            'qte'=>'required'
        ]);
        $commande->date = now();
        $commande->fournisseur_id = $request->fournisseur_id;
        $commande->produit_id = $request->produit_id;
        $commande->qte = $request->qte;
        $commande->update();
        return response()->json($commande);     // Retourner l'utilisateur mis à jour
    }

   public function deleteCommande($id)
   {
    $commande = Commande::find($id);
       if (! $commande) {
           return response()->json(['message' => 'Commande non trouvé'], 404);
       }
       // Supprimer l'utilisateur
       $commande->delete();
       return response()->json(['message' => 'Commande supprimé avec succès'], 200);
   }

   public function addCommande(Request $request){
      $request->validate([
          'fournisseur_id'=>'required',
          'produit_id'=>'required',
          'qte'=>'required'
      ]);
      $commande = new Commande();
      $commande->date = now();
      $commande->fournisseur_id = $request->fournisseur_id;
      $commande->produit_id = $request->produit_id;
      $commande->status = "En attente";
      $commande->qte = $request->qte;
      $commande->save();
      return response()->json(['message' => 'Ajout réussie', 'commande' => $commande]);
  }

  public function validerCommande($id)
{
    $commande = Commande::findOrFail($id);
    $produit = Produit::findOrFail($commande->produit_id);
    $produit->qte += $commande->qte;
    $produit->update();
    $commande->status = "Validée"; // Assurez-vous que vous avez une colonne pour l'état
    $commande->update();
    return response()->json(['message' => 'Commande validée avec succès.']);
}

public function annulerCommande($id)
{
    $commande = Commande::findOrFail($id);
    $commande->status = "Annulée"; // Assurez-vous que vous avez une colonne pour l'état
    $commande->update();
    return response()->json(['message' => 'Commande annulée avec succès.']);
}
}
