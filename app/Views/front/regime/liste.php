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
                <div class="generate-icon">🍽️</div>
                <h3>Découvrez votre régime idéal</h3>
                <?php if (isset($suggestionPreview) && !empty($suggestionPreview)): ?>
                    <div class="suggestion-preview" style="background: rgba(45,212,160,0.1); border: 1px solid rgba(45,212,160,0.3); border-radius: 8px; padding: 12px; margin: 16px 0;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--green); margin-bottom: 4px;">
                            🎯 Régime suggéré pour vous :
                        </div>
                        <div style="font-size: 16px; font-weight: 700; color: var(--text);">
                            <?= esc($suggestionPreview['libelle'] ?? 'Régime personnalisé') ?>
                        </div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                            <?= esc($suggestionPreview['description'] ?? '') ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($objectif) && $objectif): ?>
                    <p class="objectif-message">
                        <?php if ($objectif === 'prise_de_masse'): ?>
                            💪 <strong>Objectif Prise de Masse :</strong> Vous recevrez un régime riche en protéines et calories pour favoriser la croissance musculaire.
                        <?php elseif ($objectif === 'perte_de_poids'): ?>
                            ⚖️ <strong>Objectif Perte de Poids :</strong> Vous recevrez un régime hypocalorique équilibré pour atteindre votre poids cible.
                        <?php elseif ($objectif === 'maintien'): ?>
                            🎯 <strong>Objectif Maintien :</strong> Vous recevrez un régime équilibré pour maintenir votre poids actuel.
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p>Notre algorithme analyse vos objectifs et votre profil pour vous proposer un régime adapté à vos besoins nutritionnels.</p>
                <?php endif; ?>
                <form action="<?= site_url('regimes/generate') ?>" method="post" style="margin-top:20px">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">
                        🎲 Générer une suggestion (0.50€)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>