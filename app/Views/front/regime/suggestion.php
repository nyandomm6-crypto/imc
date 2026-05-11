<?= $this->extend('layouts/main') ?>

<?php
/** @var array $suggestion */
/** @var string $pageTitle */
/** @var string $pageSubtitle */

$suggestion = is_array($suggestion ?? null) ? $suggestion : [];
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p><?= esc($pageSubtitle) ?></p>
    </div>
</div>

<div class="container">
    <!-- Messages flash -->
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

    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                <?= esc($suggestion['libelle'] ?? 'Régime suggéré') ?>
            </span>
            <span style="font-size:11px;color:var(--muted)">Durée: <?= esc($suggestion['duree_jours'] ?? 0) ?> jours</span>
        </div>
        <div class="card-body">
            <div class="suggestion-description" style="margin-bottom:20px">
                <p style="color:var(--text);font-size:14px;line-height:1.5"><?= esc($suggestion['description'] ?? '') ?></p>
            </div>

            <?php if (!empty($suggestion['recettes']) && is_array($suggestion['recettes'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px">
                    <h4 style="margin:0 0 15px 0;color:var(--text);font-size:16px;font-weight:600">📋 Recettes incluses</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:15px">
                        <?php foreach ($suggestion['recettes'] as $recette): ?>
                            <div style="border:1px solid var(--border);border-radius:8px;padding:15px;background:var(--bg2)">
                                <h5 style="margin:0 0 10px 0;color:var(--text);font-size:14px;font-weight:600">
                                    <?= esc($recette['nom'] ?? '') ?>
                                </h5>

                                <?php if (!empty($recette['ingredients']) && is_array($recette['ingredients'])): ?>
                                    <div style="margin-bottom:10px">
                                        <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:5px">INGRÉDIENTS:</div>
                                        <ul style="margin:0;padding-left:15px;font-size:12px;color:var(--text)">
                                            <?php foreach ($recette['ingredients'] as $ingredient): ?>
                                                <li style="margin-bottom:2px"><?= esc($ingredient) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($recette['instructions'])): ?>
                                    <div>
                                        <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:5px">INSTRUCTIONS:</div>
                                        <p style="margin:0;font-size:12px;color:var(--text);line-height:1.4">
                                            <?= esc($recette['instructions']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:20px;display:flex;gap:10px">
                <a href="<?= site_url('export/regime') ?>" class="btn btn-secondary" style="flex:1;text-align:center" target="_blank">
                    📄 Exporter en PDF
                </a>
                <a href="<?= site_url('regimes') ?>" class="btn btn-secondary" style="flex:1;text-align:center">
                    🔄 Générer un autre régime
                </a>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-primary" style="flex:1;text-align:center">
                    🏠 Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>