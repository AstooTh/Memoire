<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index(){
        $categories = Categorie::all();
        return response()->json($categories);
    }

    public function updateCategorie(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
    
    // Valider les données entrantes
    $request->validate([
        'nom' => 'required',
        // Ajoutez d'autres validations ici
    ]);
    // Mettre à jour les informations de l'utilisateur
    $categorie->nom = $request->nom;
    $categorie->update();
    return response()->json( $categorie); // Retourner l'utilisateur mis à jour
    }

    public function getCategorie(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        return response()->json($categorie); // Retourner l'utilisateur mis à jour
    }

   public function deleteCategorie($id)
   {
    $categorie = Categorie::find($id);
       if (! $categorie) {
           return response()->json(['message' => 'Categorie non trouvé'], 404);
       }
       // Supprimer l'utilisateur
       $categorie->delete();
       return response()->json(['message' => 'Categorie supprimé avec succès'], 200);
   }

   public function addCategorie(Request $request){
      $request->validate([
          'nom'=>'required',
      ]);
       
      $categorie = new Categorie();
      $categorie->nom = $request->nom;
      $categorie->save();
      return response()->json(['message' => 'Ajout réussie', 'categorie' => $categorie]);
  }
}
