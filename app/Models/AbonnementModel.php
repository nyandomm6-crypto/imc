<?php

namespace App\Models;

use CodeIgniter\Model;

class AbonnementModel extends Model
{
    protected $table = 'abonnements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'utilisateur_id',
        'option_id',
        'date_debut',
        'date_fin',
    ];

    public function getOptions(): array
    {
        return $this->db->table('abonnements_options')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getAbonnementActif(int $utilisateur_id)
    {
        return $this->db->table('abonnements a')
            ->select('a.*, ao.nom, ao.prix')
            ->join('abonnements_options ao', 'ao.id = a.option_id', 'inner')
            ->where('a.utilisateur_id', $utilisateur_id)
            ->where('(a.date_fin IS NULL OR a.date_fin > NOW())')
            ->orderBy('a.date_debut', 'DESC')
            ->get()
            ->getRowArray();
    }

    public function souscrire(int $utilisateur_id, int $option_id, $date_fin = null): int|false
    {
        $data = [
            'utilisateur_id' => $utilisateur_id,
            'option_id' => $option_id,
            'date_fin' => $date_fin,
        ];

        return $this->insert($data);
    }

    public function hasGold(int $utilisateur_id): bool
    {
        $abo = $this->db->table('abonnements a')
            ->join('abonnements_options ao', 'ao.id = a.option_id', 'inner')
            ->where('a.utilisateur_id', $utilisateur_id)
            ->where('(a.date_fin IS NULL OR a.date_fin > NOW())')
            ->where("LOWER(ao.nom) LIKE '%gold%'")
            ->get()
            ->getRowArray();

        return $abo !== null;
    }

    public function createOption(array $data): int|false
    {
        return $this->db->table('abonnements_options')->insert($data);
    }

    public function updateOption(int $id, array $data): bool
    {
        return (bool) $this->db->table('abonnements_options')
            ->where('id', $id)
            ->update($data);
    }
}
