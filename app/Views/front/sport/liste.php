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