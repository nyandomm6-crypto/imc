<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table = 'comptes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'utilisateur_id',
        'solde',
        'status',
        'date_creation',
    ];

    public function getByUtilisateur(int $utilisateur_id)
    {
        return $this->asArray()
            ->where('utilisateur_id', $utilisateur_id)
            ->first();
    }

    public function creerCompte(int $utilisateur_id, float $soldeInitial = 0.0)
    {
        $data = [
            'utilisateur_id' => $utilisateur_id,
            'solde' => $soldeInitial,
            'status' => 'active',
        ];

        return $this->insert($data);
    }

    public function getSolde(int $utilisateur_id): float
    {
        $compte = $this->getByUtilisateur($utilisateur_id);
        return $compte ? (float) ($compte['solde'] ?? 0.0) : 0.0;
    }

    public function crediter(int $utilisateur_id, float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $compte = $this->getByUtilisateur($utilisateur_id);
        if (! $compte) {
            return false;
        }

        $nouveauSolde = (float) ($compte['solde'] ?? 0.0) + $montant;

        $this->db->transStart();

        $this->db->table('comptes')
            ->where('utilisateur_id', $utilisateur_id)
            ->update(['solde' => $nouveauSolde]);

        $this->db->table('transactions')->insert([
            'compte_id' => $compte['id'],
            'type' => 'income',
            'montant' => $montant,
            'description' => 'Crédit au compte',
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function debiter(int $utilisateur_id, float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $compte = $this->getByUtilisateur($utilisateur_id);
        if (! $compte) {
            return false;
        }

        $soldeActuel = (float) ($compte['solde'] ?? 0.0);
        if ($soldeActuel < $montant) {
            return false;
        }

        $nouveauSolde = $soldeActuel - $montant;

        $this->db->transStart();

        $this->db->table('comptes')
            ->where('utilisateur_id', $utilisateur_id)
            ->update(['solde' => $nouveauSolde]);

        $this->db->table('transactions')->insert([
            'compte_id' => $compte['id'],
            'type' => 'expense',
            'montant' => $montant,
            'description' => 'Débit du compte',
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function suspendre(int $utilisateur_id): bool
    {
        return (bool) $this->db->table('comptes')
            ->where('utilisateur_id', $utilisateur_id)
            ->update(['status' => 'suspended']);
    }

    public function topUsersBySolde($limit = 5)
    {
        return $this->select('utilisateurs.nom, comptes.solde')
            ->join('utilisateurs', 'utilisateurs.id = comptes.utilisateur_id')
            ->orderBy('comptes.solde', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
