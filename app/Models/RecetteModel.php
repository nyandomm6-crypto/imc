<?php

namespace App\Models;

use CodeIgniter\Model;

class RecetteModel extends Model
{
    protected $table = 'recettes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'regime_id',
        'aliment_id',
        'pourcentage',
    ];

    protected $validationRules = [
        'regime_id' => 'required|integer',
        'aliment_id' => 'required|integer',
        'pourcentage' => 'required|numeric|greater_than[0]|less_than_equal_to[100]',
    ];

    /**
     * Récupère toutes les recettes pour un régime avec détails des aliments
     */
    public function getByRegime(int $regime_id): array
    {
        return $this->select('recettes.id, recettes.pourcentage, aliments.nom, aliments.id as aliment_id')
            ->join('aliments', 'aliments.id = recettes.aliment_id')
            ->where('recettes.regime_id', $regime_id)
            ->findAll();
    }

    /**
     * Récupère une recette spécifique avec ses détails
     */
    public function getWithDetails(int $recette_id, int $regime_id)
    {
        return $this->select('recettes.*, aliments.nom, aliments.id as aliment_id')
            ->join('aliments', 'aliments.id = recettes.aliment_id')
            ->where('recettes.id', $recette_id)
            ->where('recettes.regime_id', $regime_id)
            ->first();
    }

    /**
     * Crée une nouvelle recette
     */
    public function createRecette(int $regime_id, int $aliment_id, float $pourcentage): bool
    {
        $data = [
            'regime_id' => $regime_id,
            'aliment_id' => $aliment_id,
            'pourcentage' => $pourcentage,
        ];

        return $this->insert($data) !== false;
    }

    /**
     * Met à jour une recette
     */
    public function updateRecette(int $recette_id, int $regime_id, array $data): bool
    {
        return $this->where('id', $recette_id)
            ->where('regime_id', $regime_id)
            ->update($data) !== false;
    }

    /**
     * Supprime une recette
     */
    public function deleteRecette(int $recette_id, int $regime_id): bool
    {
        return $this->where('id', $recette_id)
            ->where('regime_id', $regime_id)
            ->delete() !== false;
    }

    /**
     * Récupère les aliments disponibles pour une recette (avec détails)
     */
    public function getAvailableAliments(): array
    {
        $alimentModel = new AlimentModel();
        return $alimentModel->orderBy('nom', 'ASC')->findAll();
    }
}
