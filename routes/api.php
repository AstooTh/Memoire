<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\EmplacementController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\TransactionController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthentificationController::class, 'register']);
Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/confirmMdp', [AuthentificationController::class, 'confirmPassword']);

Route::post('/ajoutVente', [VenteController::class, 'addVente']);
Route::get('/ventes/{userId}/{date}', [VenteController::class, 'getVentes']);

Route::get('/fournisseurs', [FournisseurController::class, 'index']);
Route::post('/addFournisseur', [FournisseurController::class, 'addFournisseur']);
Route::get('/fournisseur/{id}', [FournisseurController::class, 'getFournisseur']);
Route::put('/fournisseur/{id}', [FournisseurController::class, 'updateFournisseur']);
Route::delete('/deleteFournisseur/{id}', [FournisseurController::class, 'deleteFournisseur']);

Route::get('/categories', [CategorieController::class, 'index']);
Route::post('/addCategorie', [CategorieController::class, 'addCategorie']);
Route::get('/categorie/{id}', [CategorieController::class, 'getCategorie']);
Route::put('/categorie/{id}', [CategorieController::class, 'updateCategorie']);
Route::delete('/deleteCategorie/{id}', [CategorieController::class, 'deleteCategorie']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/stock-sites', [DashboardController::class, 'getStockParSite']);
Route::get('/dashboard/ventes', [DashboardController::class, 'getVentesParSite']);

Route::get('/users', [UtilisateurController::class, 'index']);
Route::put('/utilisateur/{id}', [UtilisateurController::class, 'update']);
Route::get('/user/{id}', [UtilisateurController::class, 'getUser']);
Route::put('/update/{id}', [UtilisateurController::class, 'updateUser']);
Route::delete('/delete/{id}', [UtilisateurController::class, 'deleteUser']);
Route::post('/verify-password/{userId}', [UtilisateurController::class, 'verifyPassword']);
Route::put('/update-profile/{userId}', [UtilisateurController::class, 'updateProfile']);

Route::get('/emplacements', [EmplacementController::class, 'index']);
Route::post('/addEmplacement', [EmplacementController::class, 'addEmplacement']);
Route::get('/emplacement/{id}', [EmplacementController::class, 'getEmplacement']);
Route::put('/emplacement/{id}', [EmplacementController::class, 'updateEmplacement']);
Route::delete('/deleteEmplacement/{id}', [EmplacementController::class, 'deleteEmplacement']);

Route::get('/produits', [ProduitController::class, 'index']);
Route::post('/addProduit', [ProduitController::class, 'addProduit']);
Route::get('/produit/{id}', [ProduitController::class, 'getProduit']);
Route::get('/produits/emplacement/{id}', [ProduitController::class, 'getProduitByEmplacementId']);
Route::put('/updateProduit/{id}', [ProduitController::class, 'updateProduit']);
Route::delete('/deleteProduit/{id}', [ProduitController::class, 'deleteProduit']);

Route::get('/commandes', [CommandeController::class, 'index']);
Route::post('/addCommande', [CommandeController::class, 'addCommande']);
Route::post('/validerCommande/{id}', [CommandeController::class, 'validerCommande']);
Route::post('/annulerCommande/{id}', [CommandeController::class, 'annulerCommande']);
Route::get('/commande/{id}', [CommandeController::class, 'getCommande']);
Route::put('/updateCommande/{id}', [CommandeController::class, 'updateCommande']);

Route::post('/addTransaction', [TransactionController::class, 'addTransaction']);