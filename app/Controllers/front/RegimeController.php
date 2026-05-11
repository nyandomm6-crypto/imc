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
        $this->compteModel->debiter($utilisateurId, 1);
        $suggestion = $this->suggestionModel->getSuggestionRegime();

        return view('front/regime/suggestion', [
            'suggestion' => $suggestion,
            'pageTitle' => 'Suggestion de Régime',
            'pageSubtitle' => 'Votre régime personnalisé',
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
