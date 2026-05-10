<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\OffreModel;
use Config\Database;

class AdminOffreController extends BaseController
{
    protected OffreModel $offreModel;

    public function __construct()
    {
        $this->offreModel = new OffreModel();
    }

    public function index()
    {
        $offres = $this->offreModel->orderBy('nom', 'ASC')->findAll();
        
        return view('back/offres/list', [
            'offres' => $offres
        ]);
    }

    public function create()
    {
        return view('back/offres/form', [
            'offre' => null,
            'types' => ['regime' => 'Régime', 'sport' => 'Sport', 'regime_sport' => 'Régime + Sport']
        ]);
    }

    public function store()
    {
        $rules = [
            'nom' => 'required|max_length[100]',
            'type' => 'required|in_list[regime,sport,regime_sport]',
            'prix' => 'required|numeric',
            'prix_gold' => 'required|numeric',
            'description' => 'max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'type', 'prix', 'prix_gold', 'description']);
        
        if ($this->offreModel->insert($data)) {
            return redirect()->to('/admin/offres')->with('success', 'Offre créée avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
    }

    public function edit(int $id)
    {
        $offre = $this->offreModel->find($id);

        if (!$offre) {
            return redirect()->to('/admin/offres')->with('error', 'Offre non trouvée');
        }

        return view('back/offres/form', [
            'offre' => $offre,
            'types' => ['regime' => 'Régime', 'sport' => 'Sport', 'regime_sport' => 'Régime + Sport']
        ]);
    }

    public function update(int $id)
    {
        $offre = $this->offreModel->find($id);

        if (!$offre) {
            return redirect()->to('/admin/offres')->with('error', 'Offre non trouvée');
        }

        $rules = [
            'nom' => 'required|max_length[100]',
            'type' => 'required|in_list[regime,sport,regime_sport]',
            'prix' => 'required|numeric',
            'prix_gold' => 'required|numeric',
            'description' => 'max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'type', 'prix', 'prix_gold', 'description']);

        if ($this->offreModel->update($id, $data)) {
            return redirect()->to('/admin/offres')->with('success', 'Offre mise à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete(int $id)
    {
        $offre = $this->offreModel->find($id);

        if (!$offre) {
            return redirect()->to('/admin/offres')->with('error', 'Offre non trouvée');
        }

        if ($this->offreModel->delete($id)) {
            return redirect()->to('/admin/offres')->with('success', 'Offre supprimée avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression');
    }

    public function demandes()
    {
        $db = Database::connect();
        $demandes = $db->table('demandes_offres do')
            ->select('do.*, u.nom as user_nom, u.email, o.nom as offre_nom, r.libelle as regime_libelle, s.nom as sport_libelle')
            ->join('utilisateurs u', 'u.id = do.utilisateur_id', 'inner')
            ->join('offres o', 'o.id = do.offre_id', 'inner')
            ->join('regimes r', 'r.id = do.regime_id', 'left')
            ->join('sports s', 's.id = do.sport_id', 'left')
            ->orderBy('do.date_demande', 'DESC')
            ->get()
            ->getResultArray();

        return view('back/offres/demandes', [
            'demandes' => $demandes
        ]);
    }

    public function detailDemande(int $demande_id)
    {
        $db = Database::connect();
        $demande = $db->table('demandes_offres do')
            ->select('do.*, u.nom as user_nom, u.email, o.nom as offre_nom, o.type, o.prix, o.prix_gold, 
                     r.libelle as regime_libelle, s.nom as sport_libelle, a.nom as abo_nom')
            ->join('utilisateurs u', 'u.id = do.utilisateur_id', 'inner')
            ->join('offres o', 'o.id = do.offre_id', 'inner')
            ->join('regimes r', 'r.id = do.regime_id', 'left')
            ->join('sports s', 's.id = do.sport_id', 'left')
            ->join('abonnements ab', 'ab.utilisateur_id = u.id', 'left')
            ->join('abonnements_options a', 'a.id = ab.option_id', 'left')
            ->where('do.id', $demande_id)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/admin/offres/demandes')->with('error', 'Demande non trouvée');
        }

        return view('back/offres/detail_demande', [
            'demande' => (array) $demande
        ]);
    }

    public function acceptDemande(int $demande_id)
    {
        $db = Database::connect();
        
        // Récupérer la demande
        $demande = $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/admin/offres/demandes')->with('error', 'Demande non trouvée');
        }

        // Récupérer l'utilisateur et ses abonnements
        $utilisateur = $db->table('utilisateurs u')
            ->select('u.*, COALESCE(ao.nom, "Gratuit") as abo_type')
            ->join('abonnements ab', 'ab.utilisateur_id = u.id', 'left')
            ->join('abonnements_options ao', 'ao.id = ab.option_id', 'left')
            ->where('u.id', $demande->utilisateur_id)
            ->get()
            ->getRow();

        // Récupérer l'offre
        $offre = $db->table('offres')
            ->where('id', $demande->offre_id)
            ->get()
            ->getRow();

        // Déterminer le prix
        $isGold = (strtolower($utilisateur->abo_type ?? 'gratuit') === 'gold');
        $prix_paye = $isGold ? $offre->prix_gold : $offre->prix;

        // Mettre à jour
        $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->update([
                'statut' => 'acceptée',
                'prix_paye' => $prix_paye,
                'date_acceptation' => date('Y-m-d H:i:s')
            ]);

        return redirect()->to('/admin/offres/demandes')->with('success', 'Demande acceptée');
    }

    public function rejectDemande(int $demande_id)
    {
        $db = Database::connect();
        
        $demande = $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->get()
            ->getRow();

        if (!$demande) {
            return redirect()->to('/admin/offres/demandes')->with('error', 'Demande non trouvée');
        }

        $db->table('demandes_offres')
            ->where('id', $demande_id)
            ->update([
                'statut' => 'rejetée'
            ]);

        return redirect()->to('/admin/offres/demandes')->with('success', 'Demande rejetée');
    }
}
