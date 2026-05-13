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

        $dataObjectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        $objectif = $dataObjectif['libelle'] ?? null;
        $valeur = (float) ($dataObjectif['valeur_cible'] ?? 0);

        $suggestion = $this->suggestionModel->getSuggestionRegime($objectif, $valeur);

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

        $dataObjectif = $this->suggestionModel->getObjectifPrincipalUtilisateur($utilisateurId);

        $objectif = $dataObjectif['libelle'] ?? null;
        $valeur = (float) ($dataObjectif['valeur_cible'] ?? 0);

        $suggestion = $this->suggestionModel->getSuggestionRegime($objectif, $valeur);

        return redirect()->to('/sports')->with('info', 'Nouvelle suggestion générée.');
    }

    public function confirmer()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $sportId = (int) $this->request->getPost('sport_id');
        if ($sportId <= 0) {
            return redirect()->back()->with('error', 'ID de sport invalide.');
        }

        // Récupérer le sport depuis la DB
        $sport = $this->sportModel->getById($sportId);
        if (!$sport) {
            return redirect()->back()->with('error', 'Sport non trouvé.');
        }

        // Ajouter les détails enrichis
        $sport['description'] = $sport['description'] ?? 'Activité physique équilibrée pour votre bien-être';
        $sport['duree_recommandee'] = $sport['duree_recommandee'] ?? '45-60 minutes';
        $sport['frequence'] = $sport['frequence'] ?? '3-4 fois par semaine';
        $sport['avantages'] = $sport['avantages'] ?? ['Maintien de la forme', 'Bien-être général', 'Amélioration de la santé cardiovasculaire'];
        $sport['conseils'] = $sport['conseils'] ?? ['Adapter l\'intensité à votre niveau', 'Consulter un professionnel si nécessaire', 'Rester régulier dans la pratique'];

        // Vérifier le solde (0.5€ sans réduction pour les sports)
        $prix = $this->suggestionModel->calculerPrixSuggestion();

        $solde = $this->compteModel->getSolde($utilisateurId);
        if ($solde < $prix) {
            return redirect()->back()->with('error', 'Solde insuffisant pour confirmer cette suggestion (0.50€ nécessaires).');
        }

        // Débiter le solde
        if (! $this->compteModel->debiter($utilisateurId, $prix)) {
            return redirect()->back()->with('error', 'Erreur lors du paiement de la suggestion.');
        }

        $montantDebite = number_format($prix, 2, ',', ' ');
        session()->setFlashdata('success', 'Sport confirmé et payé : ' . $montantDebite . '€.');

        return view('front/sport/suggestion', [
            'suggestion' => $sport,
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
