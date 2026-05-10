<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $id = $session->get('utilisateur_id') ?? $session->get('user_id');

        if (is_numeric($id) && (int) $id > 0) {
            return null;
        }

        if (strtolower((string) $request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Authentification requise.']);
        }

        return redirect()->to('/login');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
