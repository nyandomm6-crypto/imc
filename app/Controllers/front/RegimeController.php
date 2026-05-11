<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\AbonnementModel;
use App\Models\CompteModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\SuggestionModel;

class RegimeController extends BaseController
{
    private RegimeModel $regimeModel;
    private CompteModel $compteModel;
    private ObjectifModel $objectifModel;
    private AbonnementModel $abonnementModel;
    private SuggestionModel $suggestionModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->compteModel = new CompteModel();
        $this->objectifModel = new ObjectifModel();
        $this->abonnementModel = new AbonnementModel();
        $this->suggestionModel = new SuggestionModel();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer l'objectif principal de l'utilisateur
        $objectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        // Pré-calculer la suggestion pour afficher le nom
        $suggestion = $this->suggestionModel->getSuggestionRegime($objectif);

        return view('front/regime/liste', [
            'pageTitle' => 'Régimes',
            'pageSubtitle' => 'Générez une suggestion de régime personnalisée',
            'objectif' => $objectif,
            'suggestionPreview' => $suggestion
        ]);
    }

    public function generate()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer l'objectif principal de l'utilisateur
        $objectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        // Générer une nouvelle suggestion basée sur l'objectif
        $suggestion = $this->suggestionModel->getSuggestionRegime($objectif);

        return redirect()->to('/regimes')->with('info', 'Nouvelle suggestion générée.');
    }

    public function confirmer()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $regimeId = (int) $this->request->getPost('regime_id');
        if ($regimeId <= 0) {
            return redirect()->back()->with('error', 'ID de régime invalide.');
        }

        // Récupérer le régime depuis la DB
        $regime = $this->regimeModel->find($regimeId);
        if (!$regime) {
            return redirect()->back()->with('error', 'Régime non trouvé.');
        }

        // Ajouter les recettes enrichies
        $regime['description'] = $regime['description'] ?? 'Régime équilibré pour votre bien-être';
        $regime['duree_jours'] = $regime['duree_jours'] ?? 30;
        $regime['recettes'] = $this->suggestionModel->getRecettesForRegime($regime['id']);

        // Vérifier le solde
        $hasGold = $this->abonnementModel->hasGold($utilisateurId);
        $prix = $this->suggestionModel->calculerPrixSuggestion();
        $prixFinal = $this->suggestionModel->appliquerRemise($prix, $hasGold);

        $solde = $this->compteModel->getSolde($utilisateurId);
        if ($solde < $prixFinal) {
            return redirect()->back()->with('error', 'Solde insuffisant pour confirmer cette suggestion (0.50€ nécessaires).');
        }

        // Débiter le solde
        if (! $this->compteModel->debiter($utilisateurId, $prixFinal)) {
            return redirect()->back()->with('error', 'Erreur lors du paiement de la suggestion.');
        }

        $montantDebite = number_format($prixFinal, 2, ',', ' ');
        session()->setFlashdata('success', 'Régime confirmé et payé : ' . $montantDebite . '€.');

        return view('front/regime/suggestion', [
            'suggestion' => $regime,
            'pageTitle' => 'Suggestion de Régime',
            'pageSubtitle' => 'Votre régime personnalisé basé sur votre objectif',
        ]);
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
