<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'codes_promo';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'code',
        'status',
        'date_expiration',
    ];

    protected $validationRules = [
        'code' => 'required|max_length[100]',
    ];

    public function getAll(): array
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    public function getByCode(string $code)
    {
        return $this->asArray()->where('code', $code)->first();
    }

    public function isValid(string $code): bool
    {
        $row = $this->getByCode($code);
        if (! $row) {
            return false;
        }

        if (($row['status'] ?? '') !== 'active') {
            return false;
        }

        $dateExp = $row['date_expiration'] ?? null;
        if ($dateExp && strtotime($dateExp) <= time()) {
            return false;
        }

        return true;
    }

    public function createCode(array $data)
    {
        return $this->insert($data);
    }

    public function utiliserCode(string $code, int $utilisateur_id): bool
    {
        $row = $this->getByCode($code);
        if (! $row) {
            return false;
        }

        if (! $this->isValid($code)) {
            return false;
        }

        $this->db->transStart();

        $this->db->table('codes_promo')
            ->where('id', $row['id'])
            ->update(['status' => 'used']);

        $this->db->table('utilisateurs_codes')
            ->insert([
                'code_id' => $row['id'],
                'utilisateur_id' => $utilisateur_id,
            ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function getMontantCode(string $code)
    {
        $row = $this->getByCode($code);
        if (! $row) {
            return null;
        }

        if (array_key_exists('montant', $row)) {
            return (float) $row['montant'];
        }

        return null;
    }

    public function expirer(int $id): bool
    {
        return (bool) $this->db->table('codes_promo')->where('id', $id)->update(['status' => 'expired']);
    }

    public function validerCode(int $id): bool
    {
        return (bool) $this->db->table('codes_promo')->where('id', $id)->update(['status' => 'active']);
    }
}
