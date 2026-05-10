<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\OffreModel;
use App\Models\AbonnementModel;
use App\Models\UtilisateurModel;
use App\Models\ObjectifModel;
use App\Libraries\SuggesterOffre;

class OffreController extends BaseController
{
    private OffreModel $offreModel;
    private AbonnementModel $abonnementModel;
    private UtilisateurModel $utilisateurModel;
    private ObjectifModel $objectifModel;
    private SuggesterOffre $suggester;

    public function __construct()
    {
        $this->offreModel = new OffreModel();
        $this->abonnementModel = new AbonnementModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->objectifModel = new ObjectifModel();
        $this->suggester = new SuggesterOffre();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        $offres = $this->offreModel->getAll();
        $demandesUtilisateur = $this->offreModel->getDemandUtilisateur($utilisateurId);

        // Vérifier si l'utilisateur a une offre Gold
        $isGold = $this->abonnementModel->hasGoldSubscription($utilisateurId);

        // Calculer les prix affichés pour chaque offre
        $offresAvecPrix = array_map(function($offre) use ($isGold) {
            $prix = $this->offreModel->calculerPrix($offre['id'], $isGold);
            return array_merge($offre, [
                'prix_affiche' => $prix,
                'remise_gold' => $isGold ? ($offre['prix'] * 0.14) : 0,
            ]);
        }, $offres);

        // Récupérer l'abonnement actuel
        $db = \Config\Database::connect();
        $abonnementActuel = $db->table('abonnements a')
            ->select('a.id, a.option_id, ao.nom')
            ->join('abonnements_options ao', 'ao.id = a.option_id')
            ->where('a.utilisateur_id', $utilisateurId)
            ->where('a.date_fin IS NULL OR a.date_fin > NOW()', null, false)
            ->get()
            ->getRow();

        return view('front/offre/index', [
            'utilisateur' => $utilisateur,
            'offres' => $offresAvecPrix,
            'demandesUtilisateur' => $demandesUtilisateur,
            'isGold' => $isGold,
            'abonnementActuel' => (array) $abonnementActuel,
        ]);
    }

    public function suggestions()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        
        // Récupérer l'objectif en cours
        $objectifEnCours = $this->objectifModel->getObjectifEnCoursComplete($utilisateurId);
        
        if ($objectifEnCours === null) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Vous devez avoir un objectif actif pour voir les suggestions.');
        }

        // Récupérer les suggestions d'offres selon l'objectif
        $offresSuggestions = $this->offreModel->getOffresSuggestions($objectifEnCours['objectif_id']);

        // Vérifier si l'utilisateur a une offre Gold
        $isGold = $this->abonnementModel->hasGoldSubscription($utilisateurId);

        // Calculer les prix
        $offresAvecPrix = array_map(function($offre) use ($isGold) {
            $prix = $this->offreModel->calculerPrix($offre['id'], $isGold);
            return array_merge($offre, [
                'prix_affiche' => $prix,
                'remise_gold' => $isGold ? ($offre['prix'] * 0.14) : 0,
            ]);
        }, $offresSuggestions);

        return view('front/offre/suggestions', [
            'utilisateur' => $utilisateur,
            'objectifEnCours' => $objectifEnCours,
            'offres' => $offresAvecPrix,
            'isGold' => $isGold,
        ]);
    }

    public function demand(int $offre_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier que l'offre existe
        $offre = $this->offreModel->find($offre_id);
        if (!$offre) {
            return redirect()->to('/offres')
                ->with('error', 'Offre non trouvée.');
        }

        // Récupérer l'objectif en cours pour les suggestions
        $objectifEnCours = $this->objectifModel->getObjectifEnCoursComplete($utilisateurId);
        
        // Suggérer régime et/ou sport selon le type d'offre
        $regime = null;
        $sport = null;
        $regimeId = null;
        $sportId = null;

        if ($objectifEnCours !== null) {
            $suggestions = $this->suggester->suggerRegimeEtSport(
                $objectifEnCours['objectif_id'],
                null // IMC pourrait être passé ici
            );

            if ($offre['type'] === 'regime' || $offre['type'] === 'regime_sport') {
                $regime = $suggestions['regime'];
                $regimeId = $regime['id'] ?? null;
            }

            if ($offre['type'] === 'sport' || $offre['type'] === 'regime_sport') {
                $sport = $suggestions['sport'];
                $sportId = $sport['id'] ?? null;
            }
        }

        // Créer la demande
        $this->offreModel->demanderOffre($utilisateurId, $offre_id, $regimeId, $sportId);

        return redirect()->to('/offres')
            ->with('success', 'Demande d\'offre créée avec succès ! Un régime et/ou sport vous ont été suggérés.');
    }

    public function detailDemande(int $demande_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer la demande
        $db = \Config\Database::connect();
        $demande = $db->table('demandes_offres do')
            ->select('do.*, o.nom, o.type, o.prix, o.prix_gold, r.libelle as regime_libelle, s.nom as sport_libelle')
            ->join('offres o', 'o.id = do.offre_id', 'inner')
            ->join('regimes r', 'r.id = do.regime_id', 'left')
            ->join('sports s', 's.id = do.sport_id', 'left')
            ->where('do.id', $demande_id)
            ->where('do.utilisateur_id', $utilisateurId)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/offres')
                ->with('error', 'Demande non trouvée.');
        }

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);

        return view('front/offre/detail_demande', [
            'utilisateur' => $utilisateur,
            'demande' => (array) $demande,
        ]);
    }

    public function accept(int $demande_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier que la demande appartient à l'utilisateur
        $db = \Config\Database::connect();
        $demande = $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->where('utilisateur_id', $utilisateurId)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/offres')
                ->with('error', 'Demande non trouvée.');
        }

        // Récupérer le prix
        $offre = $db->table('offres')
            ->where('id', $demande->offre_id)
            ->get()
            ->getRow();

        if (!$offre) {
            return redirect()->to('/offres')
                ->with('error', 'Offre non trouvée.');
        }

        // Vérifier si Gold
        $isGold = $this->abonnementModel->hasGoldSubscription($utilisateurId);
        $prix = $this->offreModel->calculerPrix($demande->offre_id, $isGold);

        // Mettre à jour la demande
        $this->offreModel->accepterOffre($demande_id, $prix);

        return redirect()->to('/offres')
            ->with('success', 'Offre acceptée avec succès !');
    }

    public function reject(int $demande_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier que la demande appartient à l'utilisateur
        $db = \Config\Database::connect();
        $demande = $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->where('utilisateur_id', $utilisateurId)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/offres')
                ->with('error', 'Demande non trouvée.');
        }

        // Mettre à jour la demande
        $this->offreModel->rejeterOffre($demande_id);

        return redirect()->to('/offres')
            ->with('success', 'Offre rejetée.');
    }

    public function subscribe()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer l'option_id du formulaire
        $option_id = (int) $this->request->getPost('option_id');
        
        if ($option_id <= 0) {
            return redirect()->to('/offres')
                ->with('error', 'Option invalide.');
        }

        // Vérifier que l'option existe
        $db = \Config\Database::connect();
        $option = $db->table('abonnements_options')
            ->where('id', $option_id)
            ->get()
            ->getRow();

        if (!$option) {
            return redirect()->to('/offres')
                ->with('error', 'Option d\'abonnement non trouvée.');
        }

        // Vérifier si l'utilisateur a déjà un abonnement actif et le supprimer
        $existingAbo = $db->table('abonnements')
            ->where('utilisateur_id', $utilisateurId)
            ->where('date_fin IS NULL OR date_fin > NOW()', null, false)
            ->get()
            ->getRow();

        if ($existingAbo) {
            // Annuler l'abonnement existant
            $db->table('abonnements')
                ->where('id', $existingAbo->id)
                ->update([
                    'date_fin' => date('Y-m-d H:i:s')
                ]);
        }

        // Créer le nouvel abonnement
        // La date_fin sera NULL pour subscription illimitée jusqu'à annulation
        $db->table('abonnements')->insert([
            'utilisateur_id' => $utilisateurId,
            'option_id' => $option_id,
            'date_debut' => date('Y-m-d H:i:s'),
            'date_fin' => null
        ]);

        $optionNom = (string) ($option->nom ?? 'Gold');
        return redirect()->to('/offres')
            ->with('success', 'Abonnement ' . esc($optionNom) . ' activé avec succès !');
    }

    private function getUtilisateurId(): ?int
    {
        $session = session();

        foreach (['utilisateur_id', 'user_id', 'id'] as $key) {
            $value = $session->get($key);

            if (is_numeric($value) && (int) $value > 0) {
                return (int) $value;
            }
        }

        return null;
    }
}
