<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Régime</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #7c6ef5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #7c6ef5;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .regime-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #7c6ef5;
        }

        .regime-title {
            font-size: 20px;
            font-weight: bold;
            color: #7c6ef5;
            margin-bottom: 10px;
        }

        .regime-description {
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.6;
        }

        .regime-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            background: white;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            flex: 1;
            min-width: 150px;
        }

        .meta-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .meta-value {
            font-size: 16px;
            color: #333;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: #7c6ef5;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .recette {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .recette-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .ingredients {
            margin-bottom: 15px;
        }

        .ingredients h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .ingredients ul {
            margin: 0;
            padding-left: 20px;
        }

        .ingredients li {
            margin-bottom: 3px;
            font-size: 13px;
        }

        .instructions {
            margin-bottom: 15px;
        }

        .instructions h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .instructions p {
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Details du Regime</h1>
        <p>Genere le <?= date('d/m/Y a H:i') ?></p>
    </div>

    <div class="regime-info">
        <div class="regime-title">Regime: <?= esc($regime['libelle'] ?? $regime['nom'] ?? 'Regime personnalise') ?></div>
        <div class="regime-description">
            <?= esc($regime['description'] ?? 'Description du régime non disponible.') ?>
        </div>

        <div class="regime-meta">
            <?php if (isset($regime['duree_jours'])): ?>
            <div class="meta-item">
                <div class="meta-label">Durée</div>
                <div class="meta-value"><?= esc($regime['duree_jours']) ?> jours</div>
            </div>
            <?php endif; ?>

            <?php if (isset($regime['aliments']) && is_array($regime['aliments'])): ?>
            <div class="meta-item">
                <div class="meta-label">Aliments</div>
                <div class="meta-value"><?= count($regime['aliments']) ?> ingrédients</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($regime['recettes']) && is_array($regime['recettes']) && !empty($regime['recettes'])): ?>
        <div class="section">
            <h2>Recettes recommandees</h2>

            <?php foreach ($regime['recettes'] as $recette): ?>
                <div class="recette">
                    <div class="recette-title">Recette: <?= esc($recette['nom'] ?? 'Recette') ?></div>

                    <?php if (isset($recette['ingredients']) && is_array($recette['ingredients'])): ?>
                        <div class="ingredients">
                            <h4>Ingrédients :</h4>
                            <ul>
                                <?php foreach ($recette['ingredients'] as $ingredient): ?>
                                    <li><?= esc($ingredient) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($recette['instructions'])): ?>
                        <div class="instructions">
                            <h4>Instructions :</h4>
                            <p><?= esc($recette['instructions']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($regime['aliments']) && is_array($regime['aliments']) && !empty($regime['aliments'])): ?>
        <div class="section">
            <h2>Composition nutritionnelle</h2>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #7c6ef5; color: white;">
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Aliment</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Pourcentage</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Calories/100g</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Protéines</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Glucides</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Lipides</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regime['aliments'] as $aliment): ?>
                        <tr style="background-color: <?= ($aliment === reset($regime['aliments'])) ? '#f8f9fa' : 'white' ?>;">
                            <td style="padding: 12px; border: 1px solid #ddd;"><?= esc($aliment['nom'] ?? '') ?></td>
                            <td style="padding: 12px; text-align: center; border: 1px solid #ddd;"><?= esc($aliment['pourcentage'] ?? 0) ?>%</td>
                            <td style="padding: 12px; text-align: center; border: 1px solid #ddd;"><?= esc($aliment['calories_100g'] ?? 0) ?> kcal</td>
                            <td style="padding: 12px; text-align: center; border: 1px solid #ddd;"><?= esc($aliment['proteines_100g'] ?? 0) ?>g</td>
                            <td style="padding: 12px; text-align: center; border: 1px solid #ddd;"><?= esc($aliment['glucides_100g'] ?? 0) ?>g</td>
                            <td style="padding: 12px; text-align: center; border: 1px solid #ddd;"><?= esc($aliment['lipides_100g'] ?? 0) ?>g</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Document généré automatiquement par FitIMC - <?= date('d/m/Y') ?></p>
    </div>
</body>
</html>