<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\SuggestionModel;
use App\Models\CompteModel;
use App\Models\AbonnementModel;
use App\Models\RegimeModel;
use App\Models\SportModel;

class RegimeSportController extends BaseController
{
    private SuggestionModel $suggestionModel;
    private CompteModel $compteModel;
    private AbonnementModel $abonnementModel;
    private RegimeModel $regimeModel;
    private SportModel $sportModel;

    public function __construct()
    {
        $this->suggestionModel = new SuggestionModel();
        $this->compteModel = new CompteModel();
        $this->abonnementModel = new AbonnementModel();

        $this->regimeModel = new RegimeModel();
        $this->sportModel = new SportModel();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();
        if (!$utilisateurId) {
            return redirect()->to('/');
        }

        $dataObjectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        $objectif = $dataObjectif['libelle'] ?? null;
        $valeur = (float) ($dataObjectif['valeur_cible'] ?? 0);

        $suggestion = $this->suggestionModel->getSuggestionRegimeSport($objectif, $valeur);

        return view('front/regime_sport/liste', [
            'objectif' => $objectif,
            'suggestion' => $suggestion,
            'pageTitle' => 'Regime & Sport'
        ]);
    }

    public function generate()
    {
        $utilisateurId = $this->getUtilisateurId();
        if (!$utilisateurId) {
            return redirect()->to('/');
        }

        $dataObjectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        if (!$dataObjectif) {
            return redirect()->back()->with('error', 'Aucun objectif trouvé');
        }

        $objectif = $dataObjectif['libelle'];
        $valeur = (float) $dataObjectif['valeur_cible'];

        $suggestion = $this->suggestionModel->getSuggestionRegimeSport($objectif, $valeur);

        return redirect()->to('/regimes_sports')
            ->with('info', 'Nouvelle suggestion générée.');
    }
public function confirmer()
{
    $utilisateurId = $this->getUtilisateurId();
    if ($utilisateurId === null) {
        return redirect()->to('/');
    }

    $regimeId = (int) $this->request->getPost('regime_id');
    $sportId  = (int) $this->request->getPost('sport_id');

    if ($regimeId <= 0 || $sportId <= 0) {
        return redirect()->back()->with('error', 'Données invalides.');
    }

    // 🔹 Charger régime
    $regime = $this->regimeModel->find($regimeId);
    if (!$regime) {
        return redirect()->back()->with('error', 'Régime introuvable.');
    }

    // 🔹 Charger sport
    $sport = $this->sportModel->getById($sportId);
    if (!$sport) {
        return redirect()->back()->with('error', 'Sport introuvable.');
    }

    // 🔹 enrichissement régime
    $regime['recettes'] = $this->suggestionModel->getRecettesForRegime($regimeId);

    // 🔹 enrichissement sport
    $sport['description'] = 'Programme sportif personnalisé';
    $sport['duree_recommandee'] = '45-60 min';
    $sport['frequence'] = '3-4 fois/semaine';

    // 🔹 calcul prix
    $hasGold = $this->abonnementModel->hasGold($utilisateurId);

    $prixRegime = $this->suggestionModel->calculerPrixSuggestion();
    $prixSport  = $this->suggestionModel->calculerPrixSuggestion();

    $prixTotal = $this->suggestionModel->appliquerRemise($prixRegime + $prixSport, $hasGold);

    // 🔹 solde
    $solde = $this->compteModel->getSolde($utilisateurId);
    if ($solde < $prixTotal) {
        return redirect()->back()->with('error', 'Solde insuffisant.');
    }

    // 🔹 debit
    if (! $this->compteModel->debiter($utilisateurId, $prixTotal)) {
        return redirect()->back()->with('error', 'Erreur paiement.');
    }

    session()->setFlashdata('success', 'Programme régime + sport confirmé.');

    // 🔥 IMPORTANT : comme ton RegimeController
    return view('front/regime_sport/suggestion', [
        'pageTitle' => 'Programme complet',
        'pageSubtitle' => 'Régime + Sport personnalisé',
        'suggestion' => [
            'regime' => $regime,
            'sport' => $sport
        ]
    ]);
}

    public function suggere()
    {
        return redirect()->back()->with('success', 'Suggestion achetée.');
    }

    private function getUtilisateurId(): ?int
    {
        $session = session();
        foreach (['utilisateur_id', 'user_id', 'id'] as $key) {
            $value = $session->get($key);
            if (is_numeric($value)) return (int) $value;
        }
        return null;
    }
}