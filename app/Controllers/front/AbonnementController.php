<?php

namespace App\Controllers\front;

use App\Models\AbonnementModel;
use App\Models\UtilisateurModel;
use CodeIgniter\Controller;

class AbonnementController extends Controller
{
    protected $abonnementModel;
    protected $utilisateurModel;

    public function __construct()
    {
        $this->abonnementModel = new AbonnementModel();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $options = $this->abonnementModel->getOptions();
        $abonnementActif = $this->abonnementModel->getAbonnementActif($userId);

        return view('front/abonnement/index', [
            'options' => $options,
            'abonnementActif' => $abonnementActif,
        ]);
    }

    public function souscrire()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $optionId = $this->request->getPost('option_id');
        if (!$optionId) {
            return redirect()->back()->with('error', 'Option invalide.');
        }

        // Souscrire à l'option choisie
        $result = $this->abonnementModel->souscrire($userId, (int)$optionId);
        if ($result === false) {
            return redirect()->back()->with('error', 'Erreur lors de la souscription.');
        }

        return redirect()->to('/abonnement')->with('success', 'Souscription réussie !');
    }
}