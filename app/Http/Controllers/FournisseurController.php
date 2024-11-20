<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;

class FournisseurController extends Controller
{
    public function index(){
        $fournisseurs = Fournisseur::all();
        return response()->json($fournisseurs);
    }
    public function updateFournisseur(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
    
    // Valider les données entrantes
    $request->validate([
        'nom' => 'required',
        'adresse' => 'required',
        'contact' => 'required'
        // Ajoutez d'autres validations ici
    ]);
    // Mettre à jour les informations de l'utilisateur
    $fournisseur->nom = $request->nom;
    $fournisseur->adresse = $request->adresse;
    $fournisseur->contact = $request->contact;
    $fournisseur->update();
    return response()->json($fournisseur); // Retourner l'utilisateur mis à jour
    }

    public function getFournisseur(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json($fournisseur); // Retourner l'utilisateur mis à jour
    }

   public function deleteFournisseur($id)
   {
    $fournisseur = Fournisseur::find($id);
       if (! $fournisseur) {
           return response()->json(['message' => 'Fournisseur non trouvé'], 404);
       }
       // Supprimer l'utilisateur
       $fournisseur->delete();
       return response()->json(['message' => 'Fournisseur supprimé avec succès'], 200);
   }

   public function addFournisseur(Request $request){
      $request->validate([
          'nom'=>'required',
          'adresse'=>'required',
          'contact'=>'required'
      ]);
       
      $fournisseur = new Fournisseur();
      $fournisseur->nom = $request->nom;
      $fournisseur->adresse = $request->adresse;
      $fournisseur->contact = $request->contact;
      $fournisseur->save();
      return response()->json(['message' => 'Ajout réussie', 'fournisseur' => $fournisseur]);
  }
}
