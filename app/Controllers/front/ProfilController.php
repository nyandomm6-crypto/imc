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

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        
        // Récupérer les objectifs avec les données complètes
        $objectifs = $this->objectifModel->getObjectifsUtilisateurComplete($utilisateurId);
        $objectifEnCours = $this->objectifModel->getObjectifEnCoursComplete($utilisateurId);
        
        $listeObjectifs = $this->objectifModel->getAll();

        return view('front/profil/objectifs', [
            'utilisateur' => $utilisateur,
            'objectifs' => $objectifs,
            'objectifEnCours' => $objectifEnCours,
            'listeObjectifs' => $listeObjectifs,
        ]);
    }

    public function createObjectif()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier s'il y a déjà un objectif en cours
        $objectifEnCours = $this->objectifModel->getObjectifEnCoursComplete($utilisateurId);
        if ($objectifEnCours !== null) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Vous avez déjà un objectif en cours. Terminez-le avant d\'en créer un nouveau.');
        }

        $listeObjectifs = $this->objectifModel->getAll();

        return view('front/profil/create_objectif', [
            'listeObjectifs' => $listeObjectifs,
        ]);
    }

    public function storeObjectif()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier s'il y a déjà un objectif en cours
        $objectifEnCours = $this->objectifModel->getObjectifEnCoursComplete($utilisateurId);
        if ($objectifEnCours !== null) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Vous avez déjà un objectif en cours. Terminez-le avant d\'en créer un nouveau.');
        }

        $objectifId = (int) $this->request->getPost('objectif_id');
        $valeurCible = $this->request->getPost('valeur_cible');
        $valeurCible = $valeurCible !== '' ? (float) $valeurCible : null;

        if ($objectifId <= 0) {
            return redirect()->to('/profil/objectifs/create')
                ->withInput()
                ->with('error', 'Veuillez sélectionner un objectif valide.');
        }

        $this->objectifModel->createObjectifUtilisateur($utilisateurId, $objectifId, $valeurCible);

        return redirect()->to('/profil/objectifs')
            ->with('success', 'Objectif créé avec succès.');
    }

    public function achieveObjectif(int $utilisateur_objectif_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier que l'objectif appartient à l'utilisateur
        $objectif = $this->objectifModel->getObjectifUtilisateurById($utilisateur_objectif_id, $utilisateurId);

        if ($objectif === null) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Objectif non trouvé.');
        }

        $this->objectifModel->markObjectifAsAchieved($utilisateur_objectif_id);

        return redirect()->to('/profil/objectifs')
            ->with('success', 'Objectif marqué comme atteint.');
    }

    public function abandonObjectif(int $utilisateur_objectif_id)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Vérifier que l'objectif appartient à l'utilisateur
        $objectif = $this->objectifModel->getObjectifUtilisateurById($utilisateur_objectif_id, $utilisateurId);

        if ($objectif === null) {
            return redirect()->to('/profil/objectifs')
                ->with('error', 'Objectif non trouvé.');
        }

        $this->objectifModel->markObjectifAsAbandoned($utilisateur_objectif_id);

        return redirect()->to('/profil/objectifs')
            ->with('success', 'Objectif marqué comme abandonné.');
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
