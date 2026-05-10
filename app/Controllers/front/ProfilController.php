<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\MesureModel;
use App\Models\UtilisateurModel;
use App\Models\GenreModel;

class ProfilController extends BaseController
{
    private UtilisateurModel $utilisateurModel;
    private MesureModel $mesureModel;
    private GenreModel $genreModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->mesureModel = new MesureModel();
        $this->genreModel = new GenreModel();
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
        return view('front/profil/objectifs');
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
}
