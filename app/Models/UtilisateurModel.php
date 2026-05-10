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
        'mot_de_passe',
        'date_creation'
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'date_naissance' => 'required|valid_date[Y-m-d]',
        'genre_id' => 'required|integer',
        'mot_de_passe' => 'required|min_length[8]'
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

    public function getAllWithDetails(): array
    {
        return $this->select('utilisateurs.*, genres.nom as genre, roles.nom as role')
            ->join('genres', 'genres.id = utilisateurs.genre_id', 'left')
            ->join('roles', 'roles.id = utilisateurs.role_id', 'left')
            ->orderBy('utilisateurs.date_creation', 'DESC')
            ->findAll();
    }

    public function getByIdWithDetails(int $id)
    {
        return $this->select('utilisateurs.*, genres.nom as genre, roles.nom as role')
            ->join('genres', 'genres.id = utilisateurs.genre_id', 'left')
            ->join('roles', 'roles.id = utilisateurs.role_id', 'left')
            ->where('utilisateurs.id', $id)
            ->first();
    }


    public function updateUtilisateur($id, array $data)
    {
        return parent::update($id, $data);
    }

    public function deleteUtilisateur($id)
    {
        return parent::delete($id);
    }

    public function getUsers()
    {
        return $this->where('role_id', 2)->findAll();
    }

    public function countByRole(int $roleId)
    {
        return $this->where('role_id', $roleId)->countAllResults();
    }

    public function inscriptionsParMois()
    {
        return $this->select("DATE_TRUNC('month', date_creation) AS mois, COUNT(*) AS total")
            ->groupBy("mois")
            ->orderBy("mois", "ASC")
            ->findAll();
    }
}
