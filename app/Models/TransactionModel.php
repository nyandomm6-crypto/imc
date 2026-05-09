<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'compte_id',
        'type',
        'montant',
        'description',
        'date_transaction',
    ];

    protected $validationRules = [
        'compte_id' => 'required|integer',
        'type' => 'required|in_list[income,expense]',
        'montant' => 'required|numeric',
    ];

    public function getByCompte(int $compte_id): array
    {
        return $this->asArray()
            ->where('compte_id', $compte_id)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
    }

    public function ajouterTransaction(int $compte_id, string $type, float $montant, string $description = ''): int|false
    {
        if ($montant <= 0) {
            return false;
        }

        if (! in_array($type, ['income', 'expense'], true)) {
            return false;
        }

        $data = [
            'compte_id' => $compte_id,
            'type' => $type,
            'montant' => $montant,
            'description' => $description,
        ];

        return $this->insert($data);
    }

    public function getTotalIncome(int $compte_id): float
    {
        $result = $this->db->table('transactions')
            ->selectSum('montant')
            ->where('compte_id', $compte_id)
            ->where('type', 'income')
            ->get()
            ->getRowArray();

        return (float) ($result['montant'] ?? 0.0);
    }

    public function getTotalExpense(int $compte_id): float
    {
        $result = $this->db->table('transactions')
            ->selectSum('montant')
            ->where('compte_id', $compte_id)
            ->where('type', 'expense')
            ->get()
            ->getRowArray();

        return (float) ($result['montant'] ?? 0.0);
    }
}
