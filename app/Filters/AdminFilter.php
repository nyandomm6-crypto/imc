<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UtilisateurModel;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $utilisateurId = session()->get('utilisateur_id');

        if (!$utilisateurId) {
            return redirect()->to('/login');
        }

        $utilisateurModel = new UtilisateurModel();
        $utilisateur      = $utilisateurModel->getById((int) $utilisateurId);

        if ($utilisateur === null) {
            session()->destroy();
            return redirect()->to('/login');
        }

        $roleId = (int) ($utilisateur['role_id'] ?? 0);

        if ($roleId !== 1) {
            return redirect()->to('/dashboard');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // rien
    }
}
