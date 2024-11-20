<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;
use App\Models\Emplacement;

class UtilisateurController extends Controller
{
    public function index(){
        $users = Utilisateur::where('role', '!=', 'gestionnaire')->get();
        foreach ($users as $user) {
            $emplacement = Emplacement::find($user->emplacement_id); // Changez emplacement_id selon votre colonne
        $user->emplacement_name = $emplacement ? $emplacement->nom : 'Inconnu'; // Ajouter le nom de l'emplacement
        }
        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        // Valider les données entrantes si nécessaire
        $request->validate([
            'etat' => 'required|integer|in:0,1', // Assurez-vous que l'état est un entier 0 ou 1
        ]);

        // Mettre à jour l'état de l'utilisateur
        $user->etat = $request->etat;
        $user->save();
        return response()->json($user); // Retourner l'utilisateur mis à jour
    }

    public function updateUser(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
    
    // Valider les données entrantes
    $request->validate([
        'nom' => 'required',
        'email' => 'required|email|unique:utilisateurs,email,' . $id,
        // Ajoutez d'autres validations ici
    ]);
    // Mettre à jour les informations de l'utilisateur
    $user->nom = $request->nom;
    $user->email = $request->email;
    $user->emplacement_id = $request->emplacement_id; // État de l'utilisateur
    // Mettre à jour d'autres champs ici si nécessaire
    $user->update();
    return response()->json($user); // Retourner l'utilisateur mis à jour
    }

    public function getUser(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        return response()->json($user); // Retourner l'utilisateur mis à jour
    }

    public function deleteUser($id)
{
    $user = Utilisateur::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }
    // Supprimer l'utilisateur
    $user->delete();
    return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
}

public function verifyPassword(Request $request, $userId)
{
    $user = Utilisateur::find($userId);
    if (Hash::check($request->password, $user->mdp)) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 400);
}

public function updateProfile(Request $request, $userId)
{
    $user = Utilisateur::find($userId);
    $user->nom = $request->nom;
    $user->email = $request->email;
    $user->save();

    return response()->json($user);
}
}
