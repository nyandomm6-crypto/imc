<?php

namespace App\Models;

use CodeIgniter\Model;

class AlimentModel extends Model
{
    protected $table = 'aliments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'calories_100g',
        'proteines_100g',
        'glucides_100g',
        'lipides_100g',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'calories_100g' => 'required|numeric',
        'proteines_100g' => 'numeric',
        'glucides_100g' => 'numeric',
        'lipides_100g' => 'numeric',
    ];

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    public function createAliment(array $data)
    {
        return $this->insert($data);
    }

    public function updateAliment($id, array $data)
    {
        return parent::update($id, $data);
    }

    public function deleteAliment($id)
    {
        return parent::delete($id);
    }

    public function getAlimentsByCategorie(string $categorie): array
    {
        $columns = $this->getAvailableColumns($this->table);

        foreach (['categorie', 'type', 'categorie_aliment'] as $col) {
            if (in_array($col, $columns, true)) {
                return $this->asArray()
                    ->where($col, $categorie)
                    ->orderBy('nom', 'ASC')
                    ->findAll();
            }
        }

        return $this->getAll();
    }

    private function getAvailableColumns(string $table): array
    {
        $rows = $this->db->query(
            'SELECT column_name FROM information_schema.columns WHERE table_schema = CURRENT_SCHEMA() AND table_name = ?',
            [$table]
        )->getResultArray();

        $cols = [];
        foreach ($rows as $r) {
            $cols[] = $r['column_name'];
        }

        return $cols;
    }
}
