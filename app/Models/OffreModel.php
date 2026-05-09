<?php

namespace App\Models;

use CodeIgniter\Model;
use Override;

class OffreModel extends Model
{
    protected $table = 'offres';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'prix',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'prix' => 'required|numeric',
    ];

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function demanderOffre(int $utilisateur_id, int $offre_id): int|false
    {
        return $this->db->table('demandes_offres')->insert([
            'utilisateur_id' => $utilisateur_id,
            'offre_id' => $offre_id,
        ]);
    }

    public function create(array $data): int|false
    {
        return $this->insert($data);
    }

  
    public function delete($id = null, bool $purge = false)
    {
        return parent::delete($id, $purge);
    }
}
