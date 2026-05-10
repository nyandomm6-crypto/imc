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

    public function updateLastMesure(int $utilisateur_id, float $poids_kg, float $taille_m, ?string $date_mesure = null)
    {
        $last = $this->getLastMesure($utilisateur_id);
        $data = [
            'poids_kg' => $poids_kg,
            'taille_m' => $taille_m,
        ];

        if ($date_mesure !== null && $date_mesure !== '') {
            $data['date_mesure'] = $date_mesure;
        }

        if ($last && isset($last['id'])) {
            return $this->update((int) $last['id'], $data);
        }

        $data['utilisateur_id'] = $utilisateur_id;
        return $this->insert($data);
    }
}
