<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\SuggestionModel;

class SuggestionController extends BaseController
{
    private SuggestionModel $suggestionModel;

    public function __construct()
    {
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

        if (!$objectif) {
            // Si pas d'objectif défini, rediriger vers la page des objectifs
            return redirect()->to('/objectifs')->with('info', 'Veuillez définir un objectif pour recevoir des suggestions personnalisées.');
        }

        // Déterminer automatiquement le type de suggestion selon l'objectif
        $typeSuggestion = $this->suggestionModel->getTypeSuggestionByObjectif($objectif);

        // Rediriger vers la page appropriée
        if ($typeSuggestion === 'sport') {
            return redirect()->to('/sports/generate');
        } else {
            return redirect()->to('/regimes/generate');
        }
    }

    public function generate()
    {
        // Alias pour la méthode index pour compatibilité
        return $this->index();
    }
}