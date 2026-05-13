<?= $this->extend('layouts/main') ?>

<?php
/** @var array $suggestion */
/** @var string $pageTitle */
/** @var string $pageSubtitle */

$suggestion = is_array($suggestion ?? null) ? $suggestion : [];

$regime = $suggestion['regime'] ?? [];
$sport  = $suggestion['sport'] ?? [];
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p><?= esc($pageSubtitle) ?></p>
    </div>
</div>

<div class="container">

    <!-- FLASH -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-green" style="margin-bottom: 20px;">
            ✅ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-red" style="margin-bottom: 20px;">
            ❌ <?= esc(session('error')) ?>
        </div>
    <?php endif; ?>

    <!-- ================= REGIME ================= -->
    <?php if (!empty($regime)): ?>
    <div class="card" style="margin-bottom:20px;">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                <?= esc($regime['libelle'] ?? 'Régime suggéré') ?>
            </span>
        </div>

        <div class="card-body">

            <p style="color:var(--text);font-size:14px;line-height:1.5;margin-bottom:20px">
                <?= esc($regime['description'] ?? '') ?>
            </p>

            <?php if (!empty($regime['recettes'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px">

                    <h4 style="margin:0 0 15px 0;">📋 Recettes incluses</h4>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:15px">

                        <?php foreach ($regime['recettes'] as $recette): ?>
                            <div style="border:1px solid var(--border);padding:15px;border-radius:8px;background:var(--bg2)">

                                <h5 style="margin:0 0 10px 0;">
                                    <?= esc($recette['nom'] ?? '') ?>
                                </h5>

                                <?php if (!empty($recette['ingredients'])): ?>
                                    <div>
                                        <strong style="font-size:12px;">INGRÉDIENTS :</strong>
                                        <ul style="margin:5px 0 0 15px;font-size:12px;">
                                            <?php foreach ($recette['ingredients'] as $ing): ?>
                                                <li><?= esc($ing) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($recette['instructions'])): ?>
                                    <div style="margin-top:10px;">
                                        <strong style="font-size:12px;">INSTRUCTIONS :</strong>
                                        <p style="font-size:12px;margin:5px 0 0 0;">
                                            <?= esc($recette['instructions']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>


    <!-- ================= SPORT (IDENTIQUE À TA VUE SPORT) ================= -->
    <?php if (!empty($sport)): ?>
    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                <?= esc($sport['nom'] ?? 'Sport suggéré') ?>
            </span>
            <span style="font-size:11px;color:var(--muted)">
                <?= esc($sport['calories_par_heure'] ?? 0) ?> kcal/h · <?= esc($sport['duree_recommandee'] ?? '') ?>
            </span>
        </div>

        <div class="card-body">

            <div style="margin-bottom:20px">
                <p style="color:var(--text);font-size:14px;line-height:1.5">
                    <?= esc($sport['description'] ?? '') ?>
                </p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
                <div>
                    <h4 style="margin:0 0 10px 0;">📅 Fréquence recommandée</h4>
                    <p style="margin:0;color:var(--muted);font-size:13px">
                        <?= esc($sport['frequence'] ?? '') ?>
                    </p>
                </div>

                <div>
                    <h4 style="margin:0 0 10px 0;">⏱️ Durée par séance</h4>
                    <p style="margin:0;color:var(--muted);font-size:13px">
                        <?= esc($sport['duree_recommandee'] ?? '') ?>
                    </p>
                </div>
            </div>

            <?php if (!empty($sport['avantages'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px">
                    <h4>✅ Avantages</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px">

                        <?php foreach ($sport['avantages'] as $avantage): ?>
                            <div style="padding:8px;border:1px solid var(--border);border-radius:6px;background:var(--bg2)">
                                ✓ <?= esc($avantage) ?>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($sport['conseils'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px">
                    <h4>💡 Conseils pratiques</h4>

                    <?php foreach ($sport['conseils'] as $conseil): ?>
                        <div style="margin-bottom:8px;padding:10px;background:var(--bg2);border-radius:6px;">
                            💡 <?= esc($conseil) ?>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>


    <!-- ================= ACTIONS ================= -->
    <div style="margin-top:20px;display:flex;gap:10px">

        <form action="<?= site_url('regimes_sports/confirmer') ?>" method="post" style="flex:1;">
            <?= csrf_field() ?>
            <input type="hidden" name="regime_id" value="<?= esc($regime['id'] ?? 0) ?>">
            <input type="hidden" name="sport_id" value="<?= esc($sport['id'] ?? 0) ?>">

            <button class="btn btn-primary" style="width:100%;">
                💰 Confirmer (0.50€)
            </button>
        </form>

        <form action="<?= site_url('regimes_sports/generate') ?>" method="post" style="flex:1;">
            <?= csrf_field() ?>
            <button class="btn btn-secondary" style="width:100%;">
                🔄 Régénérer
            </button>
        </form>

    </div>

</div>

<?= $this->endSection() ?>