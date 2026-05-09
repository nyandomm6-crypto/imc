<?php

namespace App\Models;

use CodeIgniter\Model;

class MesureModel extends Model
{
    protected $table = 'utilisateur_mesures';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'utilisateur_id',
        'poids_kg',
        'taille_m',
        'date_mesure'
    ];

    protected $validationRules = [
        'utilisateur_id' => 'required|integer',
        'poids_kg' => 'required|numeric',
        'taille_m' => 'required|numeric'
    ];

    public function getLastMesure(int $utilisateur_id)
    {
        return $this->asArray()
            ->where('utilisateur_id', $utilisateur_id)
            ->orderBy('date_mesure', 'DESC')
            ->first();
    }

    public function getHistorique(int $utilisateur_id): array
    {
        return $this->asArray()
            ->where('utilisateur_id', $utilisateur_id)
            ->orderBy('date_mesure', 'DESC')
            ->findAll();
    }

    public function ajouterMesure(int $utilisateur_id, $poids_kg, $taille_m)
    {
        $data = [
            'utilisateur_id' => $utilisateur_id,
            'poids_kg' => $poids_kg,
            'taille_m' => $taille_m
        ];

        $result = $this->insert($data);
        return $result ?: false;
    }
}
