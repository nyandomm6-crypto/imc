<?php $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<string, mixed>|null $objectifEnCours */
/** @var array<int, array<string, mixed>>|null $offres */
/** @var bool $isGold */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$objectifEnCours = is_array($objectifEnCours ?? null) ? $objectifEnCours : null;
$offres = is_array($offres ?? null) ? $offres : [];
$isGold = $isGold ?? false;

$pageTitle = 'Suggestions Personnalisées';
$pageSubtitle = 'Offres adaptées à votre objectif';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<style>
.suggestions-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    max-width: 1000px;
}

.objectif-info {
    background: linear-gradient(135deg, rgba(107, 95, 224, 0.1), rgba(107, 95, 224, 0.05));
    border: 1px solid rgba(107, 95, 224, 0.3);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.objectif-icon {
    font-size: 32px;
}

.objectif-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.objectif-titre {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}

.objectif-statut {
    font-size: 12px;
    color: var(--muted);
}

.gold-badge {
    display: inline-block;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #333;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-bottom: 12px;
}

.offres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.offre-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: 0.2s;
    position: relative;
}

.offre-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(107, 95, 224, 0.1);
}

.offre-recommended {
    border: 2px solid #4caf50;
    background: rgba(76, 175, 80, 0.05);
}

.offre-recommended::before {
    content: '★ RECOMMANDÉE';
    position: absolute;
    top: 12px;
    right: 12px;
    background: #4caf50;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
}

.offre-type {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
    background: rgba(107, 95, 224, 0.1);
    color: var(--accent);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.offre-titre {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin-top: 8px;
}

.offre-description {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.5;
}

.offre-prix {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin: 8px 0;
}

.prix-normal {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
}

.prix-gold {
    font-size: 14px;
    font-weight: 600;
    text-decoration: line-through;
    color: var(--muted);
}

.remise-gold {
    font-size: 12px;
    color: #4caf50;
    font-weight: 600;
}

.btn-demand {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    margin-top: auto;
}

.btn-demand:hover {
    background: #6b5fe0;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-back {
    background: var(--bg3);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    align-self: flex-start;
    margin-bottom: 12px;
}

.btn-back:hover {
    background: var(--bg2);
    border-color: var(--accent);
}
</style>

<div class="suggestions-container">
    <!-- Objectif en cours -->
    <?php if ($objectifEnCours !== null): ?>
        <div class="objectif-info">
            <div class="objectif-icon">📍</div>
            <div class="objectif-details">
                <div class="objectif-titre"><?= esc($objectifEnCours['libelle']) ?></div>
                <div class="objectif-statut">
                    Démarré le <?= date_format(date_create($objectifEnCours['date_debut'] ?? 'now'), 'd/m/Y') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Badge Gold -->
    <?php if ($isGold): ?>
        <div class="gold-badge">
            ★ Abonnement Gold - 14% de remise sur toutes les offres
        </div>
    <?php endif; ?>

    <!-- Bouton retour -->
    <button onclick="history.back()" class="btn-back">
        ← Retour
    </button>

    <!-- Offres suggérées -->
    <div>
        <div class="section-title">
            ✨ Offres Recommandées Pour Vous
        </div>

        <?php if (!empty($offres)): ?>
            <div class="offres-grid">
                <?php foreach ($offres as $index => $offre): ?>
                    <div class="offre-card <?= $index === 0 ? 'offre-recommended' : '' ?>">
                        <div class="offre-type">
                            <?php if ($offre['type'] === 'regime'): ?>
                                🍽️ Régime
                            <?php elseif ($offre['type'] === 'sport'): ?>
                                🏃 Sport
                            <?php else: ?>
                                🍽️🏃 Régime + Sport
                            <?php endif; ?>
                        </div>

                        <div class="offre-titre"><?= esc($offre['nom']) ?></div>

                        <?php if (!empty($offre['description'])): ?>
                            <div class="offre-description">
                                <?= esc($offre['description']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="offre-prix">
                            <?php if ($isGold): ?>
                                <span class="prix-gold"><?= number_format($offre['prix'], 2) ?>€</span>
                                <span class="prix-normal"><?= number_format($offre['prix_affiche'], 2) ?>€</span>
                                <span class="remise-gold">-14%</span>
                            <?php else: ?>
                                <span class="prix-normal"><?= number_format($offre['prix_affiche'], 2) ?>€</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($index === 0): ?>
                            <p style="font-size: 12px; color: #4caf50; margin: 0; font-weight: 600;">
                                ★ Spécialement adaptée à votre objectif
                            </p>
                        <?php endif; ?>

                        <form method="post" action="/offres/demand/<?= $offre['id'] ?>" style="display: contents;">
                            <button type="submit" class="btn-demand">
                                Demander cette offre
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; color: var(--muted);">
                <div style="font-size: 48px; margin-bottom: 16px;">✨</div>
                <p>Aucune offre disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Abonnement Gold -->
    <?php if (!$isGold): ?>
    <div>
        <div class="section-title">
            ⭐ Obtenez 14% de Remise Avec Gold!
        </div>

        <div style="background: rgba(255, 215, 0, 0.05); border: 2px solid #ffd700; border-radius: var(--radius); padding: 20px; display: flex; flex-direction: column; gap: 12px;">
            <div>
                <strong style="color: #ffd700; font-size: 14px;">🌟 Abonnement Gold - 9.99€/mois</strong>
                <p style="font-size: 13px; color: var(--text); margin: 8px 0 0 0;">
                    Économisez 14% sur cette offre recommandée et toutes les autres !
                </p>
                <p style="font-size: 12px; color: var(--muted); margin: 4px 0 0 0;">
                    ✓ 14% de remise sur toutes les offres<br>
                    ✓ Support prioritaire<br>
                    ✓ Offres exclusives
                </p>
            </div>
            <form method="post" action="/offres/subscribe" style="display: flex; gap: 8px;">
                <input type="hidden" name="option_id" value="2">
                <button type="submit" class="btn-demand" style="background: #ffd700; color: #333; flex: 1;">
                    Souscrire à Gold 🌟
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Information -->
    <div style="background: rgba(33, 150, 243, 0.1); border: 1px solid rgba(33, 150, 243, 0.3); border-radius: var(--radius); padding: 16px; font-size: 13px; color: #2196f3;">
        <strong>💡 Info:</strong> Chaque offre demandée vous suggère automatiquement un régime et/ou un sport adapté à votre objectif.
    </div>
</div>

<?= $this->endSection() ?>
