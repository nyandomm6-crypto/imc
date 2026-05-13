<?= var_dump($suggestion) ?>
<?php if (session()->has('success')): ?>
    <div class="alert alert-green">
        <?= esc(session('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-red">
        <?= esc(session('error')) ?>
    </div>
<?php endif; ?>

<?= $this->extend('layouts/main') ?>

<?php
/** @var string $pageTitle */
/** @var string $objectif */
/** @var array $suggestion */
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p>Votre programme complet régime + sport personnalisé</p>
    </div>
</div>

<div class="container">

    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🔥</span>
                Générateur régime + sport
            </span>
        </div>

        <div class="card-body">

            <?php if (!empty($suggestion)): ?>

                <!-- REGIME PREVIEW -->
                <?php if (!empty($suggestion['regime'])): ?>
                    <div class="suggestion-preview" style="background: rgba(124,110,245,0.1); border:1px solid rgba(124,110,245,0.3); padding:15px; border-radius:8px; margin-bottom:15px;">
                        <div style="font-size:13px;color:var(--accent)">🥗 Régime suggéré</div>
                        <div style="font-size:18px;font-weight:700"><?= esc($suggestion['regime']['libelle'] ?? '') ?></div>
                    </div>
                <?php endif; ?>

                <!-- SPORT PREVIEW -->
                <?php if (!empty($suggestion['sport'])): ?>
                    <div class="suggestion-preview" style="background: rgba(124,110,245,0.1); border:1px solid rgba(124,110,245,0.3); padding:15px; border-radius:8px;">
                        <div style="font-size:13px;color:var(--accent)">🏃 Sport suggéré</div>
                        <div style="font-size:18px;font-weight:700"><?= esc($suggestion['sport']['nom'] ?? '') ?></div>
                        <div style="font-size:12px;color:var(--muted)">
                            <?= esc($suggestion['sport']['calories_par_heure'] ?? 0) ?> kcal/h
                        </div>
                    </div>
                <?php endif; ?>

                <div style="display:flex;gap:10px;margin-top:20px;">

                    <form action="<?= site_url('regimes_sports/confirmer') ?>" method="post" style="flex:1;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="regime_id" value="<?= esc($suggestion['regime']['id'] ?? 0) ?>">
                        <input type="hidden" name="sport_id" value="<?= esc($suggestion['sport']['id'] ?? 0) ?>">

                        <button class="btn btn-primary" style="width:100%;">
                            💰 Confirmer (0.50€)
                        </button>
                    </form>

                    <form action="<?= site_url('regimes_sports/generate') ?>" method="post" style="flex:0.4;">
                        <?= csrf_field() ?>
                        <button class="btn btn-secondary" style="width:100%;">
                            🔄 Autre
                        </button>
                    </form>

                </div>

            <?php else: ?>

                <div style="text-align:center;padding:30px;">
                    <div style="font-size:40px">🏋️‍♂️</div>
                    <p>Générez votre programme complet personnalisé</p>

                    <form action="<?= site_url('regimes_sports/generate') ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn btn-primary">
                            🎲 Générer (0.50€)
                        </button>
                    </form>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>