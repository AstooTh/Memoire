<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Emplacement;

class EmplacementController extends Controller
{
   public function index(){
        $emplacements = Emplacement::all();
        return response()->json($emplacements);
   }

   public function updateEmplacement(Request $request, $id)
    {
      $emplacement = Emplacement::findOrFail($id);
    
    // Valider les données entrantes
    $request->validate([
        'nom' => 'required',
        'description' => 'required',
        // Ajoutez d'autres validations ici
    ]);
    // Mettre à jour les informations de l'utilisateur
    $emplacement->nom = $request->nom;
    $emplacement->description = $request->description;
    $emplacement->update();
    return response()->json( $emplacement); // Retourner l'utilisateur mis à jour
    }

    public function getEmplacement(Request $request, $id)
    {
        $emplacement = Emplacement::findOrFail($id);
        return response()->json($emplacement); // Retourner l'utilisateur mis à jour
    }

   public function deleteEmplacement($id)
   {
       $emplacement = Emplacement::find($id);
       if (! $emplacement) {
           return response()->json(['message' => 'Emplacement non trouvé'], 404);
       }
       // Supprimer l'utilisateur
       $emplacement->delete();
       return response()->json(['message' => 'Emplacement supprimé avec succès'], 200);
   }

   public function addEmplacement(Request $request){
      $request->validate([
          'nom'=>'required',
          'description'=>'required'
      ]);
       
      $emplacement = new Emplacement();
      $emplacement->nom = $request->nom;
      $emplacement->description = $request->description;
      $emplacement->save();
      return response()->json(['message' => 'Ajout réussie', 'emplacement' => $emplacement]);
  }

}
