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
                <p>Notre algorithme analyse vos objectifs et votre profil pour vous proposer un régime adapté à vos besoins nutritionnels.</p>
                <form action="<?= site_url('regimes/generate') ?>" method="post" style="margin-top:20px">
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