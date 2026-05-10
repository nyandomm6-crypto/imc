<?php

namespace App\Models;

use CodeIgniter\Model;

class ImcModel extends Model
{
    protected $table = 'historique_imc';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'utilisateur_id',
        'valeur_imc',
        'date_calcul'
    ];

    public function getHistoriqueUser(int $utilisateur_id, int $limit = 10): array
    {
        return $this->where('utilisateur_id', $utilisateur_id)
            ->orderBy('date_calcul', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function calculerIMC($poids_kg, $taille_m): float
    {
        if (! $taille_m || $taille_m <= 0) {
            return 0.0;
        }
        $imc = $poids_kg / ($taille_m * $taille_m);
        return round((float) $imc, 2);
    }

    public function getCategorie($imc)
    {
        $row = $this->db->table('imc_categories')
            ->where('imc_min <=', $imc)
            ->where('imc_max >=', $imc)
            ->get()
            ->getRowArray();

        return $row ? $row['categorie'] : null;
    }

    public function sauvegarderIMC(int $utilisateur_id, $valeur_imc)
    {
        $data = [
            'utilisateur_id' => $utilisateur_id,
            'valeur_imc' => $valeur_imc
        ];

        $result = $this->insert($data);
        return $result ?: false;
    }

    public function getHistoriqueIMC(int $utilisateur_id): array
    {
        return $this->asArray()
            ->where('utilisateur_id', $utilisateur_id)
            ->orderBy('date_calcul', 'DESC')
            ->findAll();
    }

    public function getImcIdeal($genre)
    {
        $genreName = null;

        if (is_int($genre) || ctype_digit((string) $genre)) {
            $g = $this->db->table('genres')->where('id', (int) $genre)->get()->getRowArray();
            $genreName = $g ? strtolower($g['nom']) : null;
        } else {
            $genreName = strtolower((string) $genre);
        }

        if ($genreName === 'homme') return 22.0;
        if ($genreName === 'femme') return 21.0;

        return 22.0; 
    }

    public function calculerPoidsIdeal($taille_m, $genre)
    {
        $imcIdeal = $this->getImcIdeal($genre);
        $poids = $imcIdeal * ($taille_m * $taille_m);
        return round((float) $poids, 2);
    }
}
