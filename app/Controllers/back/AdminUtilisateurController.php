<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use App\Models\MesureModel;
use App\Models\ImcModel;
use App\Models\TransactionModel;

class AdminUtilisateurController extends BaseController
{
    protected $utilisateurModel;
    protected $mesureModel;
    protected $imcModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->mesureModel = new MesureModel();
        $this->imcModel = new ImcModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $users = $this->utilisateurModel->getAllWithDetails();

        return view('back/utilisateurs/list', [
            'users' => $users
        ]);
    }

    public function show($id)
    {
        $user = $this->utilisateurModel->getByIdWithDetails($id);

        if (!$user) {
            return redirect()->to('/admin/utilisateurs')->with('error', 'Utilisateur non trouvé');
        }

        // Dernière mesure
        $lastMesure = $this->mesureModel->getLastMesure($id);

        // Historique IMC
        $imcHistory = $this->imcModel->getHistoriqueUser($id, 10);

        // Transactions via compte
        $compteModel = new \App\Models\CompteModel();
        $compte = $compteModel->getByUtilisateur($id);

        $transactions = [];
        if ($compte) {
            $transactions = $this->transactionModel->getLatestByCompte($compte['id'], 10);
        }

        $solde = $compte['solde'] ?? 0;

        return view('back/utilisateurs/show', [
            'user' => $user,
            'lastMesure' => $lastMesure,
            'imcHistory' => $imcHistory,
            'transactions' => $transactions,
            'solde' => $solde
        ]);
    }

    public function delete($id)
    {
        try {
            $this->utilisateurModel->delete($id);
            return redirect()->to('/admin/utilisateurs')->with('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->to('/admin/utilisateurs')->with('error', 'Erreur lors de la suppression');
        }
    }
}
