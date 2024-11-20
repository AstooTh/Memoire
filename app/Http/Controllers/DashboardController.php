<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Emplacement;
use App\Models\Produit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            Log::info('Début de la méthode index');
    
            // Définir une plage de dates qui inclut la semaine dernière
            $startOfWeek = now()->subWeek()->startOfWeek(); // Début de la semaine dernière
            $endOfWeek = now()->subWeek()->endOfWeek(); // Fin de la semaine dernière
    
            // Total des ventes de la semaine dernière
            $somVentes = Vente::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('montant');
            Log::info('Somme des ventes : ' . $somVentes);
    
            // Nombre de ventes de la semaine dernière
            $nbVente = Vente::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            Log::info('Nombre de ventes : ' . $nbVente);
    
            // Nombre de vendeurs actifs (distincts) pour la semaine dernière
            $nbVendeur = Vente::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->distinct('utilisateur_id')
                ->count('utilisateur_id');
            Log::info('Nombre de vendeurs : ' . $nbVendeur);
    
            return response()->json([
                'somVentes' => $somVentes,
                'nbVente' => $nbVente,
                'nbVendeur' => $nbVendeur,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStockParSite()
    {
          // Récupérer tous les sites
    $emplacements = Emplacement::all();
    $stockParSite = [];

    // Parcourir les sites pour calculer la quantité totale et la valeur totale des stocks
    foreach ($emplacements as $emplacement) {
        // Récupérer les produits de ce site (en fonction de l'emplacement_id)
        $produits = Produit::where('emplacement_id', $emplacement->id)->get();

        $quantiteTotale = $produits->sum('qte');  // Somme des quantités des produits
        $valeurTotale = $produits->sum(function($produit) {
            return $produit->qte * $produit->prix;  // Calculer la valeur totale en fonction de la quantité et du prix
        });

        // Ajouter les données pour ce site
        $stockParSite[] = [
            'site' => $emplacement->nom,  // Nom du site
            'quantite' => $quantiteTotale,  // Quantité totale des produits pour ce site
            'valeur' => $valeurTotale   // Valeur totale du stock pour ce site
        ];
    }

    // Retourner les données au format JSON
    return response()->json(['stockParSite' => $stockParSite]);
    }

    public function getVentesParSite()
    {
    $dateDebut = Carbon::parse('2024-09-30');  // 30 derniers jours
    $dateFin = Carbon::now();

    // Récupérer les ventes pour la période donnée
    $ventes = Vente::whereBetween('dateVente', [$dateDebut, $dateFin])->get();  // On récupère seulement les ventes (pas de relations)

    // Organiser les données par site (emplacement_id) et date
    $ventesParSite = [];

    // Créer un tableau avec toutes les dates entre dateDebut et dateFin
    $dates = [];
    $currentDate = $dateDebut->copy();
    while ($currentDate->lte($dateFin)) {
        $dates[] = $currentDate->format('Y-m-d');
        $currentDate->addDay(); // Passer au jour suivant
    }
    Log::info($dateDebut);
    Log::info($dateFin);
    Log::info('Nombre de ventes récupérées : ' . $ventes->count());

    foreach ($ventes as $vente) {
        $produit = Produit::find($vente->produit_id);  // Trouver le produit par son ID
        $emplacement = Emplacement::find($vente->emplacement_id);  // Trouver l'emplacement (site) par son ID
        $siteNom = $emplacement ? $emplacement->nom : 'Inconnu';  // Nom du site
        $date = Carbon::parse($vente->dateVente)->format('Y-m-d');  // Formatage de la date

        if (!isset($ventesParSite[$siteNom])) {
            $ventesParSite[$siteNom] = [];
        }

        if (!isset($ventesParSite[$siteNom][$date])) {
            $ventesParSite[$siteNom][$date] = 0;
        }

        // Additionner les quantités vendues
        $ventesParSite[$siteNom][$date] += $vente->qte;
    }

    // Ajouter les dates manquantes (si aucun enregistrement de vente pour ces jours-là)
    foreach ($ventesParSite as $siteNom => $ventesParJour) {
        foreach ($dates as $date) {
            // Si aucune vente n'est enregistrée pour une date spécifique, l'ajouter avec 0 comme quantité
            if (!isset($ventesParJour[$date])) {
                $ventesParSite[$siteNom][$date] = 0;
            }
        }
    }

    // Retourner les données sous format JSON
    return response()->json(['ventesParSite' => $ventesParSite]);
}
}