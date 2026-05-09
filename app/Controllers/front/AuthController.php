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

		if ($motDePasse !== (string) $utilisateur['mot_de_passe']) {
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
}
