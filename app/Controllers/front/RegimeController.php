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

        // Vérifier le solde
        $hasGold = $this->abonnementModel->hasGold($utilisateurId);
        $prix = $this->suggestionModel->calculerPrixSuggestion();
        $prixFinal = $this->suggestionModel->appliquerRemise($prix, $hasGold);

        $solde = $this->compteModel->getSolde($utilisateurId);
        if ($solde < $prixFinal) {
            return redirect()->back()->with('error', 'Solde insuffisant pour générer une suggestion (0.50€ nécessaires).');
        }

        // Débiter le solde
        if (! $this->compteModel->debiter($utilisateurId, $prixFinal)) {
            return redirect()->back()->with('error', 'Erreur lors du paiement de la suggestion.');
        }

        // Récupérer l'objectif principal de l'utilisateur
        $objectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        // Générer la suggestion basée sur l'objectif
        $suggestion = $this->suggestionModel->getSuggestionRegime($objectif);

        $montantDebite = number_format($prixFinal, 2, ',', ' ');
        session()->setFlashdata('success', 'Suggestion générée et payée : ' . $montantDebite . '€.');

        return view('front/regime/suggestion', [
            'suggestion' => $suggestion,
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
