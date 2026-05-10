<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\CompteModel;
use App\Models\TransactionModel;
use App\Models\UtilisateurModel;

class PorteMonnaieController extends BaseController
{
    private CompteModel $compteModel;
    private TransactionModel $transactionModel;
    private UtilisateurModel $utilisateurModel;

    public function __construct()
    {
        $this->compteModel = new CompteModel();
        $this->transactionModel = new TransactionModel();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function index()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        if ($utilisateur === null) {
            $utilisateur = ['nom' => 'Utilisateur'];
        }

        $compte = $this->compteModel->getByUtilisateur($utilisateurId);
        $transactions = [];
        if ($compte !== null && isset($compte['id'])) {
            $transactions = $this->transactionModel->getByCompte((int) $compte['id']);
        }

        return view('front/porte_monnaie/index', [
            'utilisateur' => $utilisateur,
            'soldeCompte' => $this->compteModel->getSolde($utilisateurId),
            'transactions' => $transactions,
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
}
