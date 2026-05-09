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
