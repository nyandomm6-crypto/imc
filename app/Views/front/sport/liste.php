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
                <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                Générateur de sport personnalisé
            </span>
        </div>
        <div class="card-body">
            <div class="generate-content">
                <?php if (isset($suggestionPreview) && !empty($suggestionPreview)): ?>
                    <div class="suggestion-preview" style="background: rgba(124,110,245,0.1); border: 1px solid rgba(124,110,245,0.3); border-radius: 8px; padding: 20px; margin: 0 0 20px 0;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--accent); margin-bottom: 8px;">
                            🎯 Activité suggérée pour vous :
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                            <?= esc($suggestionPreview['nom'] ?? 'Activité personnalisée') ?>
                        </div>
                        <div style="font-size: 14px; color: var(--muted); line-height: 1.5; margin-bottom: 12px;">
                            <?= esc($suggestionPreview['description'] ?? '') ?>
                        </div>
                        <div style="font-size: 12px; color: var(--accent2);">
                            🔥 <?= esc($suggestionPreview['calories_par_heure'] ?? 0) ?> kcal/h · ⏱️ <?= esc($suggestionPreview['duree_recommandee'] ?? '') ?>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <form action="<?= site_url('sports/confirmer') ?>" method="post" style="flex: 1;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="sport_id" value="<?= esc($suggestionPreview['id'] ?? 0) ?>">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                                💰 Générer cette suggestion (0.50€)
                            </button>
                        </form>
                        <form action="<?= site_url('sports/generate') ?>" method="post" style="flex: 0.4;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 12px;">
                                🔄 Autre
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="generate-icon">🏃‍♂️</div>
                    <h3>Découvrez votre activité sportive idéale</h3>
                    <p>Cliquez sur le bouton pour générer une suggestion personnalisée basée sur votre objectif.</p>
                    <form action="<?= site_url('sports/generate') ?>" method="post" style="margin-top:20px">
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