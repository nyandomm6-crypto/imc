<?php

namespace App\Models;

use CodeIgniter\Model;

class GenreModel extends Model
{
    protected $table = 'genres';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
    ];

    /**
     * Récupère tous les genres triés par nom
     */
    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    /**
     * Récupère un genre par ID
     */
    public function getById(int $id)
    {
        return $this->find($id);
    }

    /**
     * Récupère un genre par nom
     */
    public function getByName(string $nom)
    {
        return $this->where('nom', $nom)->first();
    }
}
