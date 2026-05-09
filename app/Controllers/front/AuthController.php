<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;

class AuthController extends BaseController
{
	private UtilisateurModel $utilisateurModel;

	public function __construct()
	{
		$this->utilisateurModel = new UtilisateurModel();
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
		$roleId = (int) $this->request->getPost('role_id');
		$motDePasse = (string) $this->request->getPost('mot_de_passe');
		$confirmation = (string) $this->request->getPost('confirmation_mot_de_passe');

		if ($nom === '' || $email === '' || $dateNaissance === '' || $genreId <= 0 || $roleId <= 0) {
			return redirect()->to('/inscription')
				->withInput()
				->with('error', 'Tous les champs sont requis.');
		}

		if ($motDePasse === '' || $motDePasse !== $confirmation) {
			return redirect()->to('/inscription')
				->withInput()
				->with('error', 'Les mots de passe ne correspondent pas.');
		}

		if ($this->utilisateurModel->getByEmail($email)) {
			return redirect()->to('/inscription')
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
			return redirect()->to('/inscription')
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
			return redirect()->to('/login')
				->withInput()
				->with('error', 'Email et mot de passe requis.');
		}

		$utilisateur = $this->utilisateurModel->getByEmail($email);

		if (! $utilisateur || empty($utilisateur['mot_de_passe'])) {
			return redirect()->to('/login')
				->withInput()
				->with('error', 'Identifiants invalides.');
		}

		$motDePasseStocke = (string) $utilisateur['mot_de_passe'];
		$motDePasseOk = password_verify($motDePasse, $motDePasseStocke);
		if (! $motDePasseOk && hash_equals($motDePasseStocke, $motDePasse)) {
			$motDePasseOk = true;
		}

		if (! $motDePasseOk) {
			return redirect()->to('/login')
				->withInput()
				->with('error', 'Identifiants invalides.');
		}

		$session = session();
		$session->set([
			'utilisateur_id' => (int) $utilisateur['id'],
			'user_id' => (int) $utilisateur['id'],
		]);

		return redirect()->to('/dashboard');
	}

	public function logout()
	{
		session()->destroy();

		return redirect()->to('/login');
	}

	private function getInscriptionOptions(): array
	{
		$db = db_connect();

		$genres = $db->table('genres')
			->orderBy('nom', 'ASC')
			->get()
			->getResultArray();

		$roles = $db->table('roles')
			->orderBy('nom', 'ASC')
			->get()
			->getResultArray();

		return [
			'genres' => $genres,
			'roles' => $roles,
		];
	}
}
