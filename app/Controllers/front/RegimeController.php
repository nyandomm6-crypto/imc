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

        return view('front/regime/liste', [
            'pageTitle' => 'Régimes',
            'pageSubtitle' => 'Générez une suggestion de régime personnalisée',
        ]);
    }

    public function generate()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $suggestion = $this->suggestionModel->getSuggestionRegime();

        return view('front/regime/suggestion', [
            'suggestion' => $suggestion,
            'pageTitle' => 'Suggestion de Régime',
            'pageSubtitle' => 'Votre régime personnalisé',
        ]);
    }

    public function suggere()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $regimeId = (int) $this->request->getPost('regime_id');
        if ($regimeId <= 0) {
            return redirect()->back()->with('error', 'Régime invalide.');
        }

        $regime = $this->regimeModel->getById($regimeId);
        if ($regime === null) {
            return redirect()->back()->with('error', 'Régime introuvable.');
        }

        $composition = $this->regimeModel->getCompositionRegime($regimeId);
        $hasGold = $this->abonnementModel->hasGold($utilisateurId);
        $prix = $this->suggestionModel->calculerPrixRegime($regime, $composition);
        $prixFinal = $this->suggestionModel->appliquerRemise($prix, $hasGold);

        $solde = $this->compteModel->getSolde($utilisateurId);
        if ($solde < $prixFinal) {
            return redirect()->back()->with('error', 'Solde insuffisant pour suggérer ce régime.');
        }

        if (! $this->compteModel->debiter($utilisateurId, $prixFinal)) {
            return redirect()->back()->with('error', 'Erreur lors du paiement de la suggestion.');
        }

        return redirect()->to('/regimes')->with('success', 'Suggestion du régime « ' . esc($regime['libelle']) . ' » achetée pour ' . number_format($prixFinal, 2, ',', ' ') . '€.');
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