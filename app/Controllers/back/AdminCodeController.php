<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\CodePromoModel;
use App\Models\CompteModel;

class AdminCodeController extends BaseController
{

    private CodePromoModel $codePromoModel;
    private CompteModel $compteModel;


    public function __construct()
    {
        $this->codePromoModel = new CodePromoModel();
        $this->compteModel = new CompteModel();
    }

    // ── ADMIN CRUD ──

    public function index()
    {
        $codes = $this->codePromoModel->orderBy('date_expiration', 'DESC')->findAll();

        return view('back/codes/list', [
            'codes' => $codes
        ]);
    }

    public function create()
    {
        return view('back/codes/form', [
            'code' => null
        ]);
    }

    public function store()
    {
        $rules = [
            'prix' => 'required|numeric|greater_than_equal_to[0]',
            'date_expiration' => 'required|valid_date[Y-m-d]',
            'quantite' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $prix = $this->request->getPost('prix');
        $dateExpiration = $this->request->getPost('date_expiration');
        $quantite = (int)$this->request->getPost('quantite');

        $generated = 0;
        for ($i = 0; $i < $quantite; $i++) {
            $code = $this->generateUniqueCode();
            
            $data = [
                'code' => $code,
                'prix' => $prix,
                'status' => 'active',
                'date_expiration' => $dateExpiration,
            ];

            if ($this->codePromoModel->insert($data)) {
                $generated++;
            }
        }

        return redirect()->to('/admin/codes')->with('success', "{$generated} code(s) promo généré(s) avec succès");
    }

    public function delete($id)
    {
        try {
            $this->codePromoModel->delete($id);
            return redirect()->to('/admin/codes')->with('success', 'Code supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->to('/admin/codes')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ── UTILITY ──

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
        } while ($this->codePromoModel->where('code', $code)->first());

        return $code;
    }

    // ── API (LEGACY) ──

    public function apiCode()
    {
        $payload = $this->request->getJSON(true) ?? [];
        $idUser = $payload['id_user'] ?? $this->request->getPost('id_user');
        $code = $payload['code'] ?? $this->request->getPost('code');

        if (! $idUser || ! $code) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'ID utilisateur et code sont requis.']);
        }

        if (! $this->codePromoModel->isValid((string) $code)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Ce code est invalide ou deja utilise.']);
        }

        $montant = $this->codePromoModel->getMontantCode((string) $code);
        if ($montant === null) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Montant du code introuvable.']);
        }

        $utilisateurId = (int) $idUser;
        $used = $this->codePromoModel->utiliserCode((string) $code, $utilisateurId);
        if (! $used) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Ce code a deja ete utilise.']);
        }

        $compte = $this->compteModel->getByUtilisateur($utilisateurId);
        if (! $compte) {
            $this->compteModel->creerCompte($utilisateurId, 0.0);
        }

        $credited = $this->compteModel->crediter($utilisateurId, (float) $montant);
        if (! $credited) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Impossible de crediter le compte.']);
        }

        $nouveauSolde = $this->compteModel->getSolde($utilisateurId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Code utilise avec succes.',
            'montant' => (float) $montant,
            'nouveau_solde' => $nouveauSolde,
        ]);
    }
}

