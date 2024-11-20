<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmPassord;
use Illuminate\Support\Str;
use App\Models\Utilisateur;

class AuthentificationController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'nom'=>'required',
            'email'=>'required|email|unique:utilisateurs,email', 
            'emplacementId'=>'required'
        ]);
         // Récupérer le dernier matricule
        $lastUser = Utilisateur::orderBy('matricule', 'desc')->first();
        $lastMatricule = $lastUser ? $lastUser->matricule : 'U000'; // Défaut à U000 si aucun utilisateur
        // Extraire le numéro et incrémenter
        $lastNumber = intval(substr($lastMatricule, 1)); // Extraire la partie numérique
        $newNumber = $lastNumber + 1; // Incrémenter
        // Créer le nouveau matricule au format UXXX
        $newMatricule = 'U' . str_pad($newNumber, 3, '0', STR_PAD_LEFT); // Format UXXX
        $mdp='passer';
        $user = new Utilisateur();
        $user->nom = $request->nom;
        $user->matricule = $newMatricule;
        $user->email = $request->email;
        $user->mdp = Hash::make($mdp);
        $user->role = 'vendeur';
        $user->emplacement_id = $request->emplacementId;
        $user->token = Str::random(60);
        $user->save();
        $changeMdp = env('FRONTEND_URL') . '/update-password/' . $user->token; // Lien vers Angular
        // Envoyer l'e-mail
        Mail::to($user->email)->send(new ConfirmPassord($user, $changeMdp, $newMatricule));
        return response()->json(['message' => 'Inscription réussie', 'user' => $user]);
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'nullable|string',
            'matricule' => 'nullable|string',
            'mdp' => 'required|string',          
        ]); 
        $user = Utilisateur::where('email', $request->email)
                ->orWhere('matricule', $request->matricule)
                ->first();
                if ($user) {
                    if ($user->etat === 1) { // 1 pour bloqué
                        return response()->json(['error' => 'Votre compte est bloqué.'], 403);
                    }
                    if (Hash::check($request->mdp, $user->mdp)) {
                        return response()->json(['user' => $user]);
                    }
                }

        return response()->json(['error' => 'Identifiants invalides'], 401);
    }

    public function confirmPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'mdp' => 'required',
            'newMdp' => 'required',
        ]);
        $user = Utilisateur::where('token', $request->token)->first();
        if ($user && Hash::check($request->mdp, $user->mdp)) {
            $user->mdp = Hash::make($request->newMdp);
            $user->token = null;
            $user->save();
            return response()->json(['message' => 'Mot de passe réinitialisé avec succès']);
        }
        return response()->json(['message' => 'Mot de passe actuel incorrect ou utilisateur non trouvé'], 404);
    }
}
