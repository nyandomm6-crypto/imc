<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\AbonnementModel;
use App\Models\CompteModel;
use App\Models\ObjectifModel;
use App\Models\SportModel;
use App\Models\SuggestionModel;

class SportController extends BaseController
{
    private SportModel $sportModel;
    private CompteModel $compteModel;
    private ObjectifModel $objectifModel;
    private AbonnementModel $abonnementModel;
    private SuggestionModel $suggestionModel;

    public function __construct()
    {
        $this->sportModel = new SportModel();
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
        $suggestion = $this->suggestionModel->getSuggestionSport($objectif);

        return view('front/sport/liste', [
            'pageTitle' => 'Sports',
            'pageSubtitle' => 'Générez une suggestion de sport personnalisée',
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

        // Générer la suggestion basée sur l'objectif
        $suggestion = $this->suggestionModel->getSuggestionSport($objectif);

        return view('front/sport/suggestion', [
            'suggestion' => $suggestion,
            'pageTitle' => 'Suggestion de Sport',
            'pageSubtitle' => 'Votre activité sportive personnalisée basée sur votre objectif',
        ]);
    }

    public function suggere()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $sportId = (int) $this->request->getPost('sport_id');
        if ($sportId <= 0) {
            return redirect()->back()->with('error', 'Sport invalide.');
        }

        $sport = $this->sportModel->getById($sportId);
        if ($sport === null) {
            return redirect()->back()->with('error', 'Sport introuvable.');
        }

        $hasGold = $this->abonnementModel->hasGold($utilisateurId);
        $prix = $this->suggestionModel->calculerPrixSport($sport);
        $prixFinal = $this->suggestionModel->appliquerRemise($prix, $hasGold);

        $solde = $this->compteModel->getSolde($utilisateurId);
        if ($solde < $prixFinal) {
            return redirect()->back()->with('error', 'Solde insuffisant pour suggérer ce sport.');
        }

        if (! $this->compteModel->debiter($utilisateurId, $prixFinal)) {
            return redirect()->back()->with('error', 'Erreur lors du paiement de la suggestion.');
        }

        return redirect()->to('/sports')->with('success', 'Suggestion du sport « ' . esc($sport['nom']) . ' » achetée pour ' . number_format($prixFinal, 2, ',', ' ') . '€.');
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
