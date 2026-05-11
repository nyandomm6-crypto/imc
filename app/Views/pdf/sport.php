<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Sport</title>
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

        .sport-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #7c6ef5;
        }

        .sport-title {
            font-size: 20px;
            font-weight: bold;
            color: #7c6ef5;
            margin-bottom: 10px;
        }

        .sport-description {
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.6;
        }

        .sport-meta {
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

        .advantages-list, .tips-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }

        .advantage-item, .tip-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .advantage-icon, .tip-icon {
            font-size: 18px;
            flex-shrink: 0;
        }

        .advantage-content, .tip-content {
            flex: 1;
        }

        .advantage-content strong, .tip-content strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .advantage-content p, .tip-content p {
            margin: 0;
            font-size: 13px;
            line-height: 1.5;
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
        <h1>Details du Sport</h1>
        <p>Genere le <?= date('d/m/Y a H:i') ?></p>
    </div>

    <div class="sport-info">
        <div class="sport-title">Sport: <?= esc($sport['nom'] ?? 'Sport personnalise') ?></div>
        <div class="sport-description">
            <?= esc($sport['description'] ?? 'Description du sport non disponible.') ?>
        </div>

        <div class="sport-meta">
            <?php if (isset($sport['calories_par_heure'])): ?>
            <div class="meta-item">
                <div class="meta-label">Calories/heure</div>
                <div class="meta-value"><?= esc($sport['calories_par_heure']) ?> kcal</div>
            </div>
            <?php endif; ?>

            <?php if (isset($sport['duree_recommandee'])): ?>
            <div class="meta-item">
                <div class="meta-label">Durée recommandée</div>
                <div class="meta-value"><?= esc($sport['duree_recommandee']) ?></div>
            </div>
            <?php endif; ?>

            <?php if (isset($sport['frequence'])): ?>
            <div class="meta-item">
                <div class="meta-label">Fréquence</div>
                <div class="meta-value"><?= esc($sport['frequence']) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($sport['avantages']) && is_array($sport['avantages']) && !empty($sport['avantages'])): ?>
        <div class="section">
            <h2>Avantages</h2>
            <div class="advantages-list">
                <?php foreach ($sport['avantages'] as $avantage): ?>
                    <div class="advantage-item">
                        <div class="advantage-icon">+</div>
                        <div class="advantage-content">
                            <p><?= esc($avantage) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($sport['conseils']) && is_array($sport['conseils']) && !empty($sport['conseils'])): ?>
        <div class="section">
            <h2>Conseils pratiques</h2>
            <div class="tips-list">
                <?php foreach ($sport['conseils'] as $conseil): ?>
                    <div class="tip-item">
                        <div class="tip-icon">*</div>
                        <div class="tip-content">
                            <p><?= esc($conseil) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Document généré automatiquement par FitIMC - <?= date('d/m/Y') ?></p>
    </div>
</body>
</html>