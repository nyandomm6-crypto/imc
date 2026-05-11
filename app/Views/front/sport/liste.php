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
                <div class="generate-icon">🏃‍♂️</div>
                <h3>Découvrez votre activité sportive idéale</h3>
                <?php if (isset($suggestionPreview) && !empty($suggestionPreview)): ?>
                    <div class="suggestion-preview" style="background: rgba(124,110,245,0.1); border: 1px solid rgba(124,110,245,0.3); border-radius: 8px; padding: 12px; margin: 16px 0;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--accent); margin-bottom: 4px;">
                            🎯 Activité suggérée pour vous :
                        </div>
                        <div style="font-size: 16px; font-weight: 700; color: var(--text);">
                            <?= esc($suggestionPreview['nom'] ?? 'Activité personnalisée') ?>
                        </div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                            <?= esc($suggestionPreview['description'] ?? '') ?>
                        </div>
                        <div style="font-size: 11px; color: var(--accent2); margin-top: 4px;">
                            🔥 <?= esc($suggestionPreview['calories_par_heure'] ?? 0) ?> kcal/h · ⏱️ <?= esc($suggestionPreview['duree_recommandee'] ?? '') ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($objectif) && $objectif): ?>
                    <p class="objectif-message">
                        <?php if ($objectif === 'prise_de_masse'): ?>
                            💪 <strong>Objectif Prise de Masse :</strong> Vous recevrez des suggestions d'activités de musculation et de renforcement musculaire.
                        <?php elseif ($objectif === 'perte_de_poids'): ?>
                            ⚖️ <strong>Objectif Perte de Poids :</strong> Vous recevrez des suggestions d'activités cardio et HIIT pour brûler des calories.
                        <?php elseif ($objectif === 'maintien'): ?>
                            🎯 <strong>Objectif Maintien :</strong> Vous recevrez des suggestions d'activités variées pour maintenir votre forme.
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p>Notre système analyse votre condition physique et vos objectifs pour vous recommander l'activité parfaite.</p>
                <?php endif; ?>
                <form action="<?= site_url('sports/generate') ?>" method="post" style="margin-top:20px">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">
                        🎲 Générer une suggestion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>