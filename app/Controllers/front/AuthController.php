<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UtilisateurModel;

class AuthController extends BaseController
{
    private UtilisateurModel $utilisateurModel;
    private RoleModel $roleModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->roleModel = new RoleModel();
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
        $genreId = $this->roleModel->getIdUser();
        $roleId = (int) $this->request->getPost('role_id');
        $motDePasse = (string) $this->request->getPost('mot_de_passe');
        $confirmation = (string) $this->request->getPost('confirmation_mot_de_passe');

        if ($nom === '' || $email === '' || $dateNaissance === '' || $genreId <= 0 || $roleId <= 0) {
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
            'isLoggedIn' => true,
            'utilisateur_id' => (int) $utilisateur['id'],
            'user_id' => (int) $utilisateur['id'],
            'role_id' => (int) $utilisateur['role_id'] ,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('/login'));
    }

    private function getInscriptionOptions(): array
    {
        $db = db_connect();

        $genres = $db->table('genres')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();

        return [
            'genres' => $genres
        ];
    }
}
