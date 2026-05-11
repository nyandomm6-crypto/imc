<?php

namespace App\Controllers\front;

use App\Models\AbonnementModel;
use App\Models\CompteModel;
use App\Models\UtilisateurModel;
use CodeIgniter\Controller;

class OptionController extends Controller
{
    protected $abonnementModel;
    protected $utilisateurModel;
    protected $compteModel;

    public function __construct()
    {
        $this->abonnementModel = new AbonnementModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->compteModel = new CompteModel();
    }

    /**
     * Affiche la page des options disponibles
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $options = $this->abonnementModel->getOptions();
        $abonnementActif = $this->abonnementModel->getAbonnementActif($userId);
        $utilisateur = $this->utilisateurModel->find($userId);

        return view('front/option/index', [
            'options' => $options,
            'abonnementActif' => $abonnementActif,
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * Affiche la page de détails d'une option
     */
    public function detail($id = null)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        if (!$id) {
            return redirect()->to('/options')->with('error', 'Option non trouvée.');
        }

        $option = $this->abonnementModel->getOptionById((int)$id);
        if (!$option) {
            return redirect()->to('/options')->with('error', 'Option non trouvée.');
        }

        $abonnementActif = $this->abonnementModel->getAbonnementActif($userId);
        $utilisateur = $this->utilisateurModel->find($userId);

        return view('front/option/detail', [
            'option' => $option,
            'abonnementActif' => $abonnementActif,
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * Traite l'achat d'une option
     */
    public function acheter()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $optionId = $this->request->getPost('option_id');
        if (!$optionId) {
            return redirect()->back()->with('error', 'Option invalide.');
        }

        $option = $this->abonnementModel->getOptionById((int)$optionId);
        if (!$option) {
            return redirect()->back()->with('error', 'Option non trouvée.');
        }

        // Vérifier le solde de l'utilisateur
        $prixOption = (float)($option['prix'] ?? 0);
        $soldeActuel = $this->compteModel->getSolde($userId);

        if ($soldeActuel < $prixOption) {
            return redirect()->back()
                ->with('error', 'Solde insuffisant. Vous avez ' . number_format($soldeActuel, 2, ',', ' ') . '€ et l\'option coûte ' . number_format($prixOption, 2, ',', ' ') . '€');
        }

        // Débiter le solde
        if (!$this->compteModel->debiter($userId, $prixOption)) {
            return redirect()->back()
                ->with('error', 'Erreur lors du paiement de l\'option.');
        }

        // Créer une souscription
        $result = $this->abonnementModel->souscrire($userId, (int)$optionId);
        if ($result === false) {
            // Rembourser si la souscription échoue
            $this->compteModel->crediter($userId, $prixOption);
            return redirect()->back()->with('error', 'Erreur lors de l\'achat de l\'option.');
        }

        return redirect()->to('/options')
            ->with('success', 'Option achetée avec succès ! 🎉 Votre solde a été débité de ' . number_format($prixOption, 2, ',', ' ') . '€');
    }

    /**
     * Affiche les options achetées par l'utilisateur
     */
    public function mesOptions()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $options = $this->abonnementModel->getAbonnementsByUser($userId);
        $utilisateur = $this->utilisateurModel->find($userId);

        return view('front/option/mes-options', [
            'options' => $options,
            'utilisateur' => $utilisateur,
        ]);
    }
}
