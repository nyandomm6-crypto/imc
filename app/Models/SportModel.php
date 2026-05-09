<?php

namespace App\Models;

use CodeIgniter\Model;

class SportModel extends Model
{
    protected $table = 'sports';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'calories_par_heure',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'calories_par_heure' => 'required|numeric',
    ];

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getById(int $id)
    {
        return $this->asArray()->where('id', $id)->first();
    }

    public function createSport(array $data)
    {
        return $this->insert($data);
    }

    public function updateSport($id, array $data)
    {
        return parent::update($id, $data);
    }

    public function deleteSport($id)
    {
        return parent::delete($id);
    }

    public function calculerCaloriesBrulees(int $sport_id, float $duree_heures): float
    {
        $sport = $this->getById($sport_id);
        if (! $sport) {
            return 0.0;
        }

        $caloriesParHeure = (float) ($sport['calories_par_heure'] ?? 0);
        $duree = max(0.0, (float) $duree_heures);

        return round($caloriesParHeure * $duree, 2);
    }

    public function getSportsRecommandes($objectif_id = null, $imc = null): array
    {
        if ($objectif_id !== null) {
            $obj = $this->db->table('objectifs')->where('id', $objectif_id)->get()->getRowArray();
            $libelle = $obj ? strtolower($obj['libelle']) : null;
        } else {
            $libelle = null;
        }

        if ($libelle === 'perte de poids') {
            return $this->orderBy('calories_par_heure', 'DESC')->findAll(5);
        }

        if ($libelle === 'prise de masse') {
            return $this->orderBy('calories_par_heure', 'ASC')->findAll(5);
        }

        return $this->orderBy('calories_par_heure', 'DESC')->findAll(5);
    }
}
