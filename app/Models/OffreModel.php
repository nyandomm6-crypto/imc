<?php

namespace App\Models;

use CodeIgniter\Model;

class OffreModel extends Model
{
    protected $table = 'offres';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'type',
        'description',
        'prix',
        'prix_gold',
        'date_creation',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'type' => 'required|in_list[regime,sport,regime_sport]',
        'prix' => 'required|numeric',
        'prix_gold' => 'required|numeric',
    ];

    public function getAll(): array
    {
        return $this->orderBy('type', 'ASC')->orderBy('nom', 'ASC')->findAll();
    }

    public function getByType(string $type): array
    {
        return $this->where('type', $type)->orderBy('nom', 'ASC')->findAll();
    }

    public function getOffresSuggestions(int $objectif_id): array
    {
        // Récupérer l'objectif pour déterminer le type d'offre
        $objectifData = $this->db->table('objectifs')
            ->where('id', $objectif_id)
            ->get()
            ->getRow();

        if (!$objectifData) {
            return [];
        }

        $libelle = strtolower($objectifData->libelle ?? '');

        // Déterminer le type d'offre selon l'objectif
        $typesOffre = [];
        if (strpos($libelle, 'prise') !== false) {
            // Prise de masse → régime + sport
            $typesOffre = ['regime_sport', 'regime', 'sport'];
        } elseif (strpos($libelle, 'perte') !== false) {
            // Perte de poids → sport + régime
            $typesOffre = ['sport', 'regime_sport', 'regime'];
        } else {
            // Maintien → régime + sport
            $typesOffre = ['regime_sport', 'regime', 'sport'];
        }

        $offres = [];
        foreach ($typesOffre as $type) {
            $resultat = $this->where('type', $type)->get()->getRow();
            if ($resultat) {
                $offres[] = (array) $resultat;
            }
        }

        return $offres;
    }

    public function calculerPrix(int $offre_id, bool $isGold = false): float
    {
        $offre = $this->find($offre_id);
        if (!$offre) {
            return 0;
        }

        if ($isGold) {
            return (float) $offre['prix_gold'];
        }

        return (float) $offre['prix'];
    }

    public function demanderOffre(int $utilisateur_id, int $offre_id, ?int $regime_id = null, ?int $sport_id = null): int|false
    {
        return $this->db->table('demandes_offres')->insert([
            'utilisateur_id' => $utilisateur_id,
            'offre_id' => $offre_id,
            'regime_id' => $regime_id,
            'sport_id' => $sport_id,
            'statut' => 'demande',
        ]);
    }

    public function accepterOffre(int $demande_id, float $prix_paye): bool
    {
        return (bool) $this->db->table('demandes_offres')
            ->where('id', $demande_id)
            ->update([
                'statut' => 'acceptée',
                'prix_paye' => $prix_paye,
                'date_acceptation' => date('Y-m-d H:i:s'),
            ]);
    }

    public function rejeterOffre(int $demande_id): bool
    {
        return (bool) $this->db->table('demandes_offres')
            ->where('id', $demande_id)
            ->update(['statut' => 'rejetée']);
    }

    public function getDemandUtilisateur(int $utilisateur_id): array
    {
        return $this->db->table('demandes_offres do')
            ->select('do.*, o.nom, o.type, o.prix, o.prix_gold, r.libelle as regime_libelle, s.nom as sport_libelle')
            ->join('offres o', 'o.id = do.offre_id', 'inner')
            ->join('regimes r', 'r.id = do.regime_id', 'left')
            ->join('sports s', 's.id = do.sport_id', 'left')
            ->where('do.utilisateur_id', $utilisateur_id)
            ->orderBy('do.date_demande', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function create(array $data): int|false
    {
        return $this->insert($data);
    }

    public function delete($id = null, bool $purge = false): bool
    {
        return (bool) parent::delete($id, $purge);
    }
}
