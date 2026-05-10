<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\AbonnementModel;
use App\Models\UtilisateurModel;
use Config\Database;

class AdminAbonnementController extends BaseController
{
    protected AbonnementModel $abonnementModel;
    protected UtilisateurModel $utilisateurModel;

    public function __construct()
    {
        $this->abonnementModel = new AbonnementModel();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function options()
    {
        $db = Database::connect();
        $options = $db->table('abonnements_options')
            ->orderBy('prix', 'ASC')
            ->get()
            ->getResultArray();

        return view('back/abonnements/options', [
            'options' => $options
        ]);
    }

    public function createOption()
    {
        return view('back/abonnements/form_option', [
            'option' => null
        ]);
    }

    public function storeOption()
    {
        $rules = [
            'nom' => 'required|max_length[100]|is_unique[abonnements_options,nom]',
            'prix' => 'required|numeric|greater_than_equal_to[0]',
            'description' => 'max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $data = $this->request->getPost(['nom', 'prix', 'description']);

        if ($db->table('abonnements_options')->insert($data)) {
            return redirect()->to('/admin/abonnements/options')->with('success', 'Option créée');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur');
    }

    public function editOption(int $id)
    {
        $db = Database::connect();
        $option = $db->table('abonnements_options')
            ->where('id', $id)
            ->get()
            ->getRow();

        if (!$option) {
            return redirect()->to('/admin/abonnements/options')->with('error', 'Option non trouvée');
        }

        return view('back/abonnements/form_option', [
            'option' => (array) $option
        ]);
    }

    public function updateOption(int $id)
    {
        $db = Database::connect();
        $option = $db->table('abonnements_options')
            ->where('id', $id)
            ->get()
            ->getRow();

        if (!$option) {
            return redirect()->to('/admin/abonnements/options')->with('error', 'Option non trouvée');
        }

        $rules = [
            'nom' => 'required|max_length[100]',
            'prix' => 'required|numeric|greater_than_equal_to[0]',
            'description' => 'max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'prix', 'description']);

        if ($db->table('abonnements_options')->where('id', $id)->update($data)) {
            return redirect()->to('/admin/abonnements/options')->with('success', 'Option mise à jour');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur');
    }

    public function deleteOption(int $id)
    {
        $db = Database::connect();
        
        // Vérifier qu'aucun abonnement n'utilise cette option
        $count = $db->table('abonnements')
            ->where('option_id', $id)
            ->countAllResults();

        if ($count > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer: option utilisée par ' . $count . ' abonnement(s)');
        }

        if ($db->table('abonnements_options')->where('id', $id)->delete()) {
            return redirect()->to('/admin/abonnements/options')->with('success', 'Option supprimée');
        }

        return redirect()->back()->with('error', 'Erreur');
    }

    // Gestion des abonnements utilisateur
    public function utilisateurs()
    {
        $db = Database::connect();
        $utilisateurs = $db->table('utilisateurs u')
            ->select('u.id, u.nom, u.email, 
                     COALESCE(ao.nom, "Aucun") as abo_type, 
                     ab.date_debut, ab.date_fin,
                     CASE WHEN ab.date_fin IS NULL OR ab.date_fin > NOW() THEN "Actif" ELSE "Expiré" END as statut')
            ->join('abonnements ab', 'ab.utilisateur_id = u.id', 'left')
            ->join('abonnements_options ao', 'ao.id = ab.option_id', 'left')
            ->orderBy('u.nom', 'ASC')
            ->get()
            ->getResultArray();

        return view('back/abonnements/utilisateurs', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    public function assignAbo(int $user_id)
    {
        $utilisateur = $this->utilisateurModel->find($user_id);

        if (!$utilisateur) {
            return redirect()->to('/admin/abonnements/utilisateurs')->with('error', 'Utilisateur non trouvé');
        }

        $db = Database::connect();
        $options = $db->table('abonnements_options')
            ->orderBy('prix', 'ASC')
            ->get()
            ->getResultArray();

        // Récupérer l'abonnement actuel
        $aboActuel = $db->table('abonnements')
            ->where('utilisateur_id', $user_id)
            ->where('(date_fin IS NULL OR date_fin > NOW())')
            ->get()
            ->getRow();

        return view('back/abonnements/assign_form', [
            'utilisateur' => $utilisateur,
            'options' => $options,
            'aboActuel' => (array) ($aboActuel ?? [])
        ]);
    }

    public function storeAbo(int $user_id)
    {
        $utilisateur = $this->utilisateurModel->find($user_id);

        if (!$utilisateur) {
            return redirect()->to('/admin/abonnements/utilisateurs')->with('error', 'Utilisateur non trouvé');
        }

        $rules = [
            'option_id' => 'required|integer',
            'date_fin' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $option_id = $this->request->getPost('option_id');
        $date_fin = $this->request->getPost('date_fin');

        // Vérifier que l'option existe
        $option = $db->table('abonnements_options')->where('id', $option_id)->get()->getRow();
        if (!$option) {
            return redirect()->back()->with('error', 'Option invalide');
        }

        $data = [
            'utilisateur_id' => $user_id,
            'option_id' => $option_id,
            'date_debut' => date('Y-m-d H:i:s'),
            'date_fin' => $date_fin ? date('Y-m-d 23:59:59', strtotime($date_fin)) : null
        ];

        if ($db->table('abonnements')->insert($data)) {
            return redirect()->to('/admin/abonnements/utilisateurs')
                ->with('success', 'Abonnement attribué: ' . $option->nom);
        }

        return redirect()->back()->withInput()->with('error', 'Erreur');
    }

    public function cancelAbo(int $abo_id)
    {
        $db = Database::connect();
        
        $abo = $db->table('abonnements')->where('id', $abo_id)->get()->getRow();
        if (!$abo) {
            return redirect()->back()->with('error', 'Abonnement non trouvé');
        }

        if ($db->table('abonnements')->where('id', $abo_id)->delete()) {
            return redirect()->back()->with('success', 'Abonnement annulé');
        }

        return redirect()->back()->with('error', 'Erreur');
    }

    // Statistiques
    public function stats()
    {
        $db = Database::connect();

        $stats = [
            'total_utilisateurs' => $db->table('utilisateurs')->countAllResults(),
            'utilisateurs_gold' => $db->table('utilisateurs u')
                ->join('abonnements ab', 'ab.utilisateur_id = u.id')
                ->join('abonnements_options ao', 'ao.id = ab.option_id')
                ->where('LOWER(ao.nom)', 'gold')
                ->where('(ab.date_fin IS NULL OR ab.date_fin > NOW())')
                ->countAllResults(),
            'total_offres' => $db->table('offres')->countAllResults(),
            'demandes_en_attente' => $db->table('demandes_offres')
                ->where('statut', 'demande')
                ->countAllResults(),
            'demandes_acceptees' => $db->table('demandes_offres')
                ->where('statut', 'acceptée')
                ->countAllResults(),
            'revenu_total' => $db->table('demandes_offres')
                ->where('statut', 'acceptée')
                ->selectSum('prix_paye')
                ->get()
                ->getRow()['prix_paye'] ?? 0,
        ];

        return view('back/abonnements/stats', [
            'stats' => $stats
        ]);
    }
}
