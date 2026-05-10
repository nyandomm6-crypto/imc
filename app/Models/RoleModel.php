<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
    ];

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    public function getIdUser()
    {
        $name = 'utilisateur';
        $role = $this->asArray()->where('nom', $name)->first();

        return $role ? (int) $role['id'] : null;
    }
}
