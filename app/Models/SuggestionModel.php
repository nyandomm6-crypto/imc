<?php

namespace App\Models;

use CodeIgniter\Model;

class SuggestionModel extends Model
{
    private const GOLD_DISCOUNT = 15;

    public function calculerPrixRegime(array $regime, array $composition = []): float
    {
        $base = 12.0;

        if (isset($regime['duree_jours']) && is_numeric($regime['duree_jours'])) {
            $base = 8.0 + (float) $regime['duree_jours'] * 1.5;
        }

        $detailCount = isset($composition['detail']) && is_array($composition['detail']) ? count($composition['detail']) : 0;
        $totalPourcentage = isset($composition['total_pourcentage']) ? (float) $composition['total_pourcentage'] : 0;

        $prix = $base + $detailCount * 1.8 + min(10, $totalPourcentage / 10);

        return round(max(8.0, min(45.0, $prix)), 2);
    }

    public function calculerPrixSport(array $sport): float
    {
        $calories = isset($sport['calories_par_heure']) ? (float) $sport['calories_par_heure'] : 0.0;
        if ($calories <= 0) {
            return 12.0;
        }

        $prix = 5.0 + ($calories / 10.0);
        return round(max(8.0, min(40.0, $prix)), 2);
    }

    public function appliquerRemise(float $prix, bool $hasGold): float
    {
        if (! $hasGold) {
            return round($prix, 2);
        }

        return round($prix * (1 - self::GOLD_DISCOUNT / 100), 2);
    }

    public function calculerNutritionScore(array $composition): float
    {
        if (isset($composition['total_pourcentage'])) {
            return (float) $composition['total_pourcentage'];
        }

        return isset($composition['detail']) && is_array($composition['detail']) ? count($composition['detail']) : 0;
    }

    public function getSuggestionRegime(?string $objectif = null): array
    {
        if ($objectif === null) {
            // Si pas d'objectif spécifié, retourner un régime par défaut
            $regime = $this->db->table('regimes')
                ->select('id, libelle')
                ->limit(1)
                ->get()
                ->getRowArray();

            if ($regime) {
                $regime['description'] = 'Régime équilibré pour votre bien-être';
                $regime['duree_jours'] = 30;
                $regime['recettes'] = $this->getRecettesForRegime($regime['id']);
                return $regime;
            }

            return [];
        }

        // Mapper les objectifs aux IDs de régimes
        $objectifMapping = [
            'prise de masse' => 1, // Régime Prise de Masse
            'perte de poids' => 2, // Régime Perte de Poids
            'maintien' => 3       // Régime Maintien
        ];

        $regimeId = $objectifMapping[$objectif] ?? 3; // Défaut: Maintien

        $regime = $this->db->table('regimes')
            ->select('id, libelle')
            ->where('id', $regimeId)
            ->get()
            ->getRowArray();

        if (!$regime) {
            return [];
        }

        // Ajouter les informations spécifiques selon l'objectif
        $descriptions = [
            'prise de masse' => 'Un régime riche en calories et protéines pour favoriser la prise de masse musculaire.',
            'perte de poids' => 'Un régime contrôlé en calories pour favoriser la perte de poids de manière saine.',
            'maintien' => 'Un régime équilibré pour maintenir son poids et sa forme physique.'
        ];

        $durees = [
            'prise de masse' => 90,
            'perte de poids' => 60,
            'maintien' => 30
        ];

        $regime['description'] = $descriptions[$objectif] ?? $descriptions['maintien'];
        $regime['duree_jours'] = $durees[$objectif] ?? $durees['maintien'];
        $regime['recettes'] = $this->getRecettesForRegime($regime['id']);

        return $regime;
    }

    private function getRecettesForRegime(int $regimeId): array
    {
        $recettes = $this->db->table('recettes r')
            ->select('r.pourcentage, a.nom, a.calories_100g, a.proteines_100g, a.glucides_100g, a.lipides_100g')
            ->join('aliments a', 'a.id = r.aliment_id', 'inner')
            ->where('r.regime_id', $regimeId)
            ->orderBy('r.pourcentage', 'DESC')
            ->get()
            ->getResultArray();

        // Créer des recettes fictives basées sur les aliments disponibles
        $recettesSuggeres = [];

        if (count($recettes) >= 3) {
            // Recette 1: Principale (avec les aliments les plus présents)
            $recette1 = [
                'nom' => 'Plat principal équilibré',
                'ingredients' => array_slice(array_map(function($r) {
                    return $r['nom'] . ' : ' . $r['pourcentage'] . '%';
                }, $recettes), 0, 4),
                'instructions' => 'Préparer les ingrédients selon vos préférences culinaires.'
            ];

            // Recette 2: Accompagnement
            $recette2 = [
                'nom' => 'Accompagnement léger',
                'ingredients' => array_slice(array_map(function($r) {
                    return $r['nom'] . ' : ' . round($r['pourcentage'] * 0.6) . '%';
                }, array_slice($recettes, 2, 3)), 0, 3),
                'instructions' => 'Cuire à la vapeur ou griller pour conserver les nutriments.'
            ];

            $recettesSuggeres = [$recette1, $recette2];
        } else {
            // Recette par défaut si pas assez d'ingrédients
            $recettesSuggeres = [[
                'nom' => 'Repas équilibré',
                'ingredients' => array_map(function($r) {
                    return $r['nom'] . ' : ' . $r['pourcentage'] . '%';
                }, $recettes),
                'instructions' => 'Combiner les ingrédients pour un repas nutritif.'
            ]];
        }

        return $recettesSuggeres;
    }

    public function getSuggestionSport(?string $objectif = null): array
    {
        if ($objectif === null) {
            // Si pas d'objectif spécifié, retourner un sport par défaut
            $sport = $this->db->table('sports')
                ->select('id, nom, calories_par_heure')
                ->limit(1)
                ->get()
                ->getRowArray();

            if ($sport) {
                $sport['description'] = 'Activité physique équilibrée pour votre bien-être';
                $sport['duree_recommandee'] = '45-60 minutes';
                $sport['frequence'] = '3-4 fois par semaine';
                $sport['avantages'] = ['Maintien de la forme', 'Bien-être général'];
                $sport['conseils'] = ['Adapter l\'intensité à votre niveau', 'Consulter un professionnel si nécessaire'];
                return $sport;
            }

            return [];
        }

        // Mapper les objectifs aux IDs de sports
        $objectifMapping = [
            'prise de masse' => 1, // Musculation intensive
            'perte de poids' => 5, // HIIT (Entraînement par intervalles)
            'maintien' => 6     // Yoga
        ];

        $sportId = $objectifMapping[$objectif] ?? 6; // Défaut: Yoga

        $sport = $this->db->table('sports')
            ->select('id, nom, calories_par_heure')
            ->where('id', $sportId)
            ->get()
            ->getRowArray();

        if (!$sport) {
            return [];
        }

        // Ajouter les informations spécifiques selon l'objectif
        $descriptions = [
            'prise de masse' => 'Entraînement de musculation axé sur le développement de la masse musculaire avec des charges lourdes.',
            'perte de poids' => 'Entraînement par intervalles à haute intensité pour maximiser la dépense calorique.',
            'maintien' => 'Activité douce et équilibrée pour maintenir votre condition physique.'
        ];

        $durees = [
            'prise de masse' => '60-90 minutes',
            'perte de poids' => '45-60 minutes',
            'maintien' => '45-60 minutes'
        ];

        $frequences = [
            'prise de masse' => '4-5 fois par semaine',
            'perte de poids' => '4-5 fois par semaine',
            'maintien' => '3-4 fois par semaine'
        ];

        $avantages = [
            'prise de masse' => [
                'Développement de la masse musculaire',
                'Augmentation de la force',
                'Amélioration de la densité osseuse',
                'Boost du métabolisme'
            ],
            'perte de poids' => [
                'Excellente dépense calorique',
                'Amélioration de l\'endurance cardiovasculaire',
                'Renforcement musculaire global',
                'Réduction du stress'
            ],
            'maintien' => [
                'Maintien de la condition physique',
                'Prévention des blessures',
                'Équilibre musculaire',
                'Bien-être général'
            ]
        ];

        $conseils = [
            'prise de masse' => [
                'Commencer avec des charges adaptées à votre niveau',
                'Respecter les temps de récupération entre les séries',
                'Maintenir une alimentation riche en protéines',
                'Alterner les groupes musculaires'
            ],
            'perte de poids' => [
                'Commencer progressivement pour éviter les blessures',
                'Alterner intensité haute et récupération',
                'Bien s\'hydrater avant, pendant et après',
                'Écouter son corps et adapter l\'intensité'
            ],
            'maintien' => [
                'Varier les activités pour éviter la routine',
                'Maintenir une intensité modérée',
                'Inclure des étirements',
                'Adapter selon l\'humeur et la météo'
            ]
        ];

        $sport['description'] = $descriptions[$objectif] ?? $descriptions['maintien'];
        $sport['duree_recommandee'] = $durees[$objectif] ?? $durees['maintien'];
        $sport['frequence'] = $frequences[$objectif] ?? $frequences['maintien'];
        $sport['avantages'] = $avantages[$objectif] ?? $avantages['maintien'];
        $sport['conseils'] = $conseils[$objectif] ?? $conseils['maintien'];

        return $sport;
    }

    public function getTypeSuggestionByObjectif(string $objectif): string
    {
        $mapping = [
            'prise de masse' => 'sport', // Pour prise de masse, privilégier le sport
            'perte de poids' => 'sport', // Pour perte de poids, privilégier le sport
            'maintien' => 'regime' // Pour maintien, privilégier le régime
        ];

        return $mapping[$objectif] ?? 'regime';
    }

    public function getObjectifPrincipalUtilisateur(int $utilisateurId): ?string
    {
        $objectifs = $this->db->table('utilisateur_objectifs uo')
            ->select('o.libelle')
            ->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
            ->where('uo.utilisateur_id', $utilisateurId)
            ->where('uo.statut', 'en_cours')
            ->orderBy('uo.date_creation', 'DESC')
            ->get()
            ->getResultArray();

        return !empty($objectifs) ? $objectifs[0]['libelle'] : null;
    }
}