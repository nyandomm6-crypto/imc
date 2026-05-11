<?= $this->extend('layouts/main') ?>

<?php
/** @var string $pageTitle */
/** @var string $pageSubtitle */
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p><?= esc($pageSubtitle) ?></p>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                Générateur de régime personnalisé
            </span>
        </div>
        <div class="card-body">
            <div class="generate-content">
                <?php if (isset($suggestionPreview) && !empty($suggestionPreview)): ?>
                    <div class="suggestion-preview" style="background: rgba(45,212,160,0.1); border: 1px solid rgba(45,212,160,0.3); border-radius: 8px; padding: 20px; margin: 0 0 20px 0;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--green); margin-bottom: 8px;">
                            🎯 Régime suggéré pour vous :
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                            <?= esc($suggestionPreview['libelle'] ?? 'Régime personnalisé') ?>
                        </div>
                        <div style="font-size: 14px; color: var(--muted); line-height: 1.5;">
                            <?= esc($suggestionPreview['description'] ?? '') ?>
                        </div>
                        <?php if (isset($suggestionPreview['duree_jours'])): ?>
                            <div style="font-size: 12px; color: var(--accent2); margin-top: 12px;">
                                ⏱️ Durée: <?= esc($suggestionPreview['duree_jours']) ?> jours
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <form action="<?= site_url('regimes/confirmer') ?>" method="post" style="flex: 1;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="regime_id" value="<?= esc($suggestionPreview['id'] ?? 0) ?>">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                                💰 Générer cette suggestion (0.50€)
                            </button>
                        </form>
                        <form action="<?= site_url('regimes/generate') ?>" method="post" style="flex: 0.4;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 12px;">
                                🔄 Autre
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="generate-icon">🍽️</div>
                    <h3>Découvrez votre régime idéal</h3>
                    <p>Cliquez sur le bouton pour générer une suggestion personnalisée basée sur votre objectif.</p>
                    <form action="<?= site_url('regimes/generate') ?>" method="post" style="margin-top:20px">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary">
                            🎲 Générer une suggestion (0.50€)
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>