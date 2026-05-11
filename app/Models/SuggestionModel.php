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

    public function getSuggestionRegime(): array
    {
        return [
            'id' => 1,
            'libelle' => 'Régime Méditerranéen',
            'description' => 'Un régime équilibré inspiré de la cuisine méditerranéenne, riche en légumes, fruits, poissons et huile d\'olive.',
            'duree_jours' => 30,
            'recettes' => [
                [
                    'nom' => 'Salade de tomates et concombres',
                    'ingredients' => [
                        'Tomates : 200g',
                        'Concombres : 150g',
                        'Huile d\'olive : 2 cuillères à soupe',
                        'Vinaigre balsamique : 1 cuillère à soupe',
                        'Herbes aromatiques : au goût'
                    ],
                    'instructions' => 'Couper les tomates et concombres en morceaux. Mélanger avec l\'huile d\'olive, le vinaigre et les herbes. Servir frais.'
                ],
                [
                    'nom' => 'Poisson grillé aux légumes',
                    'ingredients' => [
                        'Saumon : 150g',
                        'Courgettes : 100g',
                        'Poivrons : 100g',
                        'Huile d\'olive : 1 cuillère à soupe',
                        'Citron : 1/2',
                        'Herbes de Provence : au goût'
                    ],
                    'instructions' => 'Faire griller le saumon avec les légumes coupés. Assaisonner avec huile d\'olive, citron et herbes.'
                ],
                [
                    'nom' => 'Yaourt grec aux fruits',
                    'ingredients' => [
                        'Yaourt grec : 200g',
                        'Fruits frais (fraises, bananes) : 150g',
                        'Miel : 1 cuillère à soupe',
                        'Noix concassées : 20g'
                    ],
                    'instructions' => 'Mélanger le yaourt avec les fruits coupés. Ajouter miel et noix pour le croquant.'
                ]
            ]
        ];
    }

    public function getSuggestionSport(): array
    {
        return [
            'id' => 1,
            'nom' => 'Course à pied',
            'description' => 'Activité cardiovasculaire excellente pour améliorer l\'endurance et brûler des calories.',
            'calories_par_heure' => 600,
            'duree_recommandee' => '30-45 minutes',
            'frequence' => '3-4 fois par semaine',
            'avantages' => [
                'Améliore la santé cardiovasculaire',
                'Aide à la perte de poids',
                'Renforce les muscles des jambes',
                'Réduit le stress'
            ],
            'conseils' => [
                'Commencer par une marche rapide avant de courir',
                'Maintenir un rythme régulier',
                'Bien s\'hydrater avant, pendant et après',
                'Écouter son corps et ne pas forcer'
            ]
        ];
    }
}