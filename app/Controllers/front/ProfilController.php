<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\MesureModel;
use App\Models\UtilisateurModel;
use App\Models\GenreModel;
use App\Models\ObjectifModel;

class ProfilController extends BaseController
{
    private UtilisateurModel $utilisateurModel;
    private MesureModel $mesureModel;
    private GenreModel $genreModel;
    private ObjectifModel $objectifModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->mesureModel = new MesureModel();
        $this->genreModel = new GenreModel();
        $this->objectifModel = new ObjectifModel();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        $mesure = $this->mesureModel->getLastMesure($utilisateurId);

        $genres = $this->genreModel->getAll();

        return view('front/profil/index', [
            'utilisateur' => $utilisateur,
            'genres' => $genres,
            'mesure' => $mesure,
        ]);
    }

    public function updateProfil()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $email = trim((string) $this->request->getPost('email'));
        $dateNaissance = (string) $this->request->getPost('date_naissance');
        $genreId = (int) $this->request->getPost('genre_id');
        $mdp = (string) $this->request->getPost('mot_de_passe');
        $mdpConfirm = (string) $this->request->getPost('confirmation_mot_de_passe');

        if ($nom === '' || $email === '' || $dateNaissance === '' || $genreId <= 0) {
            return redirect()->to('/profil')
                ->withInput()
                ->with('error', 'Tous les champs du profil sont requis.');
        }

        $existing = $this->utilisateurModel->getByEmail($email);
        if ($existing && (int) ($existing['id'] ?? 0) !== $utilisateurId) {
            return redirect()->to('/profil')
                ->withInput()
                ->with('error', 'Cet email est deja utilise.');
        }

        $data = [
            'nom' => $nom,
            'email' => $email,
            'date_naissance' => $dateNaissance,
            'genre_id' => $genreId,
        ];

        if ($mdp !== '') {
            if (strlen($mdp) < 8 || $mdp !== $mdpConfirm) {
                return redirect()->to('/profil')
                    ->withInput()
                    ->with('error', 'Le mot de passe est invalide ou ne correspond pas.');
            }

            $data['mot_de_passe'] = password_hash($mdp, PASSWORD_DEFAULT);
        }

        $this->utilisateurModel->update($utilisateurId, $data);

        return redirect()->to('/profil')->with('success', 'Profil mis a jour.');
    }

    public function updateMesure()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $poids = (string) $this->request->getPost('poids_kg');
        $taille = (string) $this->request->getPost('taille_m');
        $dateMesure = (string) $this->request->getPost('date_mesure');

        if ($poids === '' || $taille === '' || ! is_numeric($poids) || ! is_numeric($taille)) {
            return redirect()->to('/profil')
                ->withInput()
                ->with('error', 'Veuillez renseigner un poids et une taille valides.');
        }

        $dateMesure = $dateMesure !== '' ? $dateMesure : date('Y-m-d');

        $this->mesureModel->updateLastMesure(
            $utilisateurId,
            (float) $poids,
            (float) $taille,
            $dateMesure
        );

        return redirect()->to('/profil')->with('success', 'Mesure mise a jour.');
    }

    public function objectifs()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $objectifsUtilisateur = $this->objectifModel->getObjectifsUtilisateur($utilisateurId);
        $tousObjectifs = $this->objectifModel->getAll();
        $peutCreerObjectif = $this->objectifModel->peutCreerObjectif($utilisateurId);

        return view('front/profil/objectifs', [
            'objectifsUtilisateur' => $objectifsUtilisateur,
            'tousObjectifs' => $tousObjectifs,
            'peutCreerObjectif' => $peutCreerObjectif,
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

    public function addMesure()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $poids = (string) $this->request->getPost('poids_kg');
        $taille = (string) $this->request->getPost('taille_m');


        if ($poids === '' || $taille === '' || ! is_numeric($poids) || ! is_numeric($taille)) {
            return redirect()->to('/profil')
                ->withInput()
                ->with('error', 'Veuillez renseigner un poids et une taille valides.');
        }


        $this->mesureModel->ajouterMesure(
            $utilisateurId,
            (float) $poids,
            (float) $taille
        );

        return redirect()->to('/profil')->with('success', 'Nouvelle mesure ajoutee.');
    }

    public function creerObjectif()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $objectifId = (int) $this->request->getPost('objectif_id');
        $valeurCible = $this->request->getPost('valeur_cible');

        if ($objectifId <= 0) {
            return redirect()->to('/profil/objectifs')
                ->withInput()
                ->with('error', 'Veuillez sélectionner un objectif.');
        }

        // Vérifier si l'utilisateur peut créer un nouvel objectif
        if (!$this->objectifModel->peutCreerObjectif($utilisateurId)) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Vous devez terminer votre objectif actuel avant d\'en créer un nouveau.');
        }

        $result = $this->objectifModel->creerObjectif($utilisateurId, $objectifId, $valeurCible);

        if ($result === false) {
            return redirect()->to('/profil/objectifs')
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'objectif.');
        }

        return redirect()->to('/profil/objectifs')->with('success', 'Objectif créé avec succès.');
    }

    public function terminerObjectif()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $utilisateurObjectifId = (int) $this->request->getPost('utilisateur_objectif_id');

        if ($utilisateurObjectifId <= 0) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Objectif invalide.');
        }

        $result = $this->objectifModel->terminerObjectif($utilisateurObjectifId, $utilisateurId);

        if (!$result) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Erreur lors de la terminaison de l\'objectif.');
        }

        return redirect()->to('/profil/objectifs')->with('success', 'Objectif terminé avec succès.');
    }
}
