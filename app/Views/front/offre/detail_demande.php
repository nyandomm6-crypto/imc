<?php $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<string, mixed>|null $demande */

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$demande = is_array($demande ?? null) ? $demande : [];

$pageTitle = 'Détail de la Demande';
$pageSubtitle = $demande['nom'] ?? 'Offre';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<style>
.detail-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    max-width: 800px;
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
    width: fit-content;
}

.btn-back:hover {
    background: var(--bg2);
    border-color: var(--accent);
}

.detail-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 16px;
}

.offre-titre {
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.status-demande {
    background: rgba(255, 193, 7, 0.2);
    color: #ff9800;
}

.status-acceptee {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
}

.status-rejetee {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
}

.info-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--muted);
    letter-spacing: 0.07em;
}

.info-value {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
}

.divider {
    height: 1px;
    background: var(--border);
}

.section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
}

.suggestion-item {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.suggestion-icon {
    font-size: 24px;
}

.suggestion-titre {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
}

.suggestion-detail {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.5;
}

.alert {
    padding: 14px 16px;
    border-radius: var(--radius-sm);
    font-size: 13px;
}

.alert-info {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.3);
    color: #2196f3;
}

.btn-accept {
    background: #4caf50;
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    align-self: flex-start;
    margin-top: 12px;
}

.btn-accept:hover {
    background: #45a049;
}

.btn-reject {
    background: #f44336;
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    align-self: flex-start;
}

.btn-reject:hover {
    background: #da190b;
}
</style>

<div class="detail-container">
    <!-- Bouton retour -->
    <button onclick="history.back()" class="btn-back">
        ← Retour aux offres
    </button>

    <!-- Détails de la demande -->
    <div class="detail-card">
        <div class="detail-header">
            <div>
                <div class="offre-titre"><?= esc($demande['nom'] ?? 'Offre') ?></div>
            </div>
            <span class="status-badge status-<?= esc($demande['statut'] ?? 'demande') ?>">
                <?= esc($demande['statut'] ?? 'demande') ?>
            </span>
        </div>

        <!-- Informations principales -->
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Type d'Offre</div>
                <div class="info-value">
                    <?php if ($demande['type'] === 'regime'): ?>
                        🍽️ Régime
                    <?php elseif ($demande['type'] === 'sport'): ?>
                        🏃 Sport
                    <?php else: ?>
                        🍽️🏃 Régime + Sport
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Prix</div>
                <div class="info-value">
                    <?php if (!empty($demande['prix_paye'])): ?>
                        <?= number_format($demande['prix_paye'], 2) ?>€
                    <?php else: ?>
                        <?= number_format($demande['prix'], 2) ?>€
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Régime et Sport suggérés -->
        <div>
            <div class="section-title">
                🎯 Éléments Suggérés Pour Vous
            </div>

            <div class="suggestions-grid">
                <?php if (!empty($demande['regime_libelle'])): ?>
                    <div class="suggestion-item">
                        <div class="suggestion-icon">🍽️</div>
                        <div class="suggestion-titre">Régime</div>
                        <div class="suggestion-detail">
                            <?= esc($demande['regime_libelle']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($demande['sport_libelle'])): ?>
                    <div class="suggestion-item">
                        <div class="suggestion-icon">🏃</div>
                        <div class="suggestion-titre">Sport</div>
                        <div class="suggestion-detail">
                            <?= esc($demande['sport_libelle']) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($demande['regime_libelle']) && empty($demande['sport_libelle'])): ?>
                <div class="alert alert-info">
                    Aucun élément suggéré pour cette offre.
                </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <!-- Informations de dates -->
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Date de Demande</div>
                <div class="info-value">
                    <?= date_format(date_create($demande['date_demande']), 'd/m/Y H:i') ?>
                </div>
            </div>

            <?php if (!empty($demande['date_acceptation'])): ?>
            <div class="info-item">
                <div class="info-label">Date d'Acceptation</div>
                <div class="info-value">
                    <?= date_format(date_create($demande['date_acceptation']), 'd/m/Y H:i') ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <!-- Statut de la demande -->
        <?php if ($demande['statut'] === 'demande'): ?>
            <div class="alert alert-info">
                <strong>⏳ En attente:</strong> Votre demande est actuellement en attente de validation par notre équipe. Nous allons la traiter rapidement.
            </div>
        <?php elseif ($demande['statut'] === 'acceptée'): ?>
            <div class="alert" style="background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); color: #4caf50;">
                <strong>✓ Acceptée:</strong> Votre demande a été approuvée ! Vous pouvez maintenant accéder à votre offre.
            </div>
        <?php else: ?>
            <div class="alert" style="background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3); color: #f44336;">
                <strong>✕ Rejetée:</strong> Malheureusement, votre demande a été rejetée. Vous pouvez demander une autre offre.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
