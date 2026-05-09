<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'email',
        'date_naissance',
        'genre_id',
        'role_id',
        'date_creation'
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'date_naissance' => 'required|valid_date[Y-m-d]',
        'genre_id' => 'required|integer',
    ];

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    public function getByEmail(string $email)
    {
        return $this->asArray()->where('email', $email)->first();
    }

    public function inscrire(array $data)
    {
        $result = $this->insert($data);
        return $result ?: false;
    }

    public function updateUtilisateur($id, array $data)
    {
        return parent::update($id, $data);
    }

    public function deleteUtilisateur($id)
    {
        return parent::delete($id);
    }
}
