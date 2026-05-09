<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function dbTest(): string
    {
        $db = \Config\Database::connect();

        try {
            $db->initialize();
            $result = $db->query('SELECT 1 AS ok')->getRowArray();
        } catch (\Throwable $e) {
            return 'Database error: ' . $e->getMessage();
        }

        if (!isset($result['ok'])) {
            return 'Database error: unexpected response.';
        }

        return 'Database OK';
    }
}
