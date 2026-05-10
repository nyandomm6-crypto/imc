<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\MesureModel;
use App\Models\RoleModel;
use App\Models\UtilisateurModel;
use App\Models\GenreModel;

class AuthController extends BaseController
{
    private UtilisateurModel $utilisateurModel;
    private RoleModel $roleModel;
    private MesureModel $mesureModel;
    private GenreModel $genreModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->roleModel = new RoleModel();
        $this->mesureModel = new MesureModel();
        $this->genreModel = new GenreModel();
    }

    public function login()
    {
        return view('front/auth/login');
    }

    public function inscriptionEtape1()
    {
        return view('front/auth/inscription_etape1', $this->getInscriptionOptions());
    }

    public function inscriptionEtape1Store()
    {
        $nom = trim((string) $this->request->getPost('nom'));
        $email = trim((string) $this->request->getPost('email'));
        $dateNaissance = (string) $this->request->getPost('date_naissance');
        $genreId = (int) $this->request->getPost('genre_id');
        $roleId =  $this->roleModel->getIdUser();
        $motDePasse = (string) $this->request->getPost('mot_de_passe');
        $confirmation = (string) $this->request->getPost('confirmation_mot_de_passe');

        if ($nom === '' || $email === '' || $dateNaissance === '' || $genreId <= 0) {
            return redirect()->to(site_url('/inscription'))
                ->withInput()
                ->with('error', 'Tous les champs sont requis.');
        }

        if ($motDePasse === '' || $motDePasse !== $confirmation) {
            return redirect()->to(site_url('/inscription'))
                ->withInput()
                ->with('error', 'Les mots de passe ne correspondent pas.');
        }

        if ($this->utilisateurModel->getByEmail($email)) {
            return redirect()->to(site_url('/inscription'))
                ->withInput()
                ->with('error', 'Cet email est deja utilise.');
        }

        $utilisateurId = $this->utilisateurModel->insert([
            'nom' => $nom,
            'email' => $email,
            'date_naissance' => $dateNaissance,
            'genre_id' => $genreId,
            'role_id' => $roleId,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'date_creation' => date('Y-m-d H:i:s'),
        ], true);

        if (! $utilisateurId) {
            return redirect()->to(site_url('/inscription'))
                ->withInput()
                ->with('error', 'Inscription impossible, reessayez.');
        }

        session()->set('inscription_user_id', (int) $utilisateurId);

        return redirect()->to('/inscription/etape-2');
    }

    public function inscriptionEtape2()
    {
        return view('front/auth/inscription_etape2');
    }

    public function inscriptionEtape2Store()
    {
        $utilisateurId = (int) session()->get('inscription_user_id');
        if ($utilisateurId <= 0) {
            return redirect()->to(site_url('/inscription'))
                ->with('error', 'Session inscription expirée, recommencez.');
        }

        $action = (string) $this->request->getPost('action');
        if ($action !== 'skip') {
            $poids = (string) $this->request->getPost('poids_kg');
            $taille = (string) $this->request->getPost('taille_m');
            $dateMesure = (string) $this->request->getPost('date_mesure');

            if ($poids === '' || $taille === '' || ! is_numeric($poids) || ! is_numeric($taille)) {
                return redirect()->to(site_url('/inscription/etape-2'))
                    ->withInput()
                    ->with('error', 'Veuillez renseigner un poids et une taille valides.');
            }

            $dateMesure = $dateMesure !== '' ? $dateMesure : date('Y-m-d');

            $inserted = $this->mesureModel->insert([
                'utilisateur_id' => $utilisateurId,
                'poids_kg' => (float) $poids,
                'taille_m' => (float) $taille,
                'date_mesure' => $dateMesure,
            ]);

            if (! $inserted) {
                return redirect()->to(site_url('/inscription/etape-2'))
                    ->withInput()
                    ->with('error', 'Impossible d\'enregistrer la mesure, reessayez.');
            }
        }

        $session = session();
        $session->set([
            'utilisateur_id' => $utilisateurId,
            'user_id' => $utilisateurId,
        ]);
        $session->remove('inscription_user_id');

        return redirect()->to(site_url('/'));
    }

    public function authenticate()
    {
        $email = trim((string) $this->request->getPost('email'));
        $motDePasse = (string) $this->request->getPost('mot_de_passe');

        if ($email === '' || $motDePasse === '') {
            return redirect()->to(site_url('/login'))
                ->withInput()
                ->with('error', 'Email et mot de passe requis.');
        }

        $utilisateur = $this->utilisateurModel->getByEmail($email);

        if (! $utilisateur || empty($utilisateur['mot_de_passe'])) {
            return redirect()->to(site_url('/login'))
                ->withInput()
                ->with('error', 'Identifiants invalides.');
        }

        $motDePasseStocke = (string) $utilisateur['mot_de_passe'];
        $motDePasseOk = password_verify($motDePasse, $motDePasseStocke);
        if (! $motDePasseOk && hash_equals($motDePasseStocke, $motDePasse)) {
            $motDePasseOk = true;
        }

        if (! $motDePasseOk) {
            return redirect()->to(site_url('/login'))
                ->withInput()
                ->with('error', 'Identifiants invalides.');
        }

        $session = session();
        $session->set([
            'utilisateur_id' => (int) $utilisateur['id'],
            'user_id' => (int) $utilisateur['id'],
        ]);
        if ($utilisateur['role_id']==1) {
            return redirect()->to(site_url('admin/dashboard'));
        }
        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('/login'));
    }

    private function getInscriptionOptions(): array
    {
        $genres = $this->genreModel->getAll();

        return [
            'genres' => $genres
        ];
    }
}
