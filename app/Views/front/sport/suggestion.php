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
                <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                <?= esc($suggestion['nom'] ?? 'Sport suggéré') ?>
            </span>
            <span style="font-size:11px;color:var(--muted)">
                <?= esc($suggestion['calories_par_heure'] ?? 0) ?> kcal/h · <?= esc($suggestion['duree_recommandee'] ?? '') ?>
            </span>
        </div>
        <div class="card-body">
            <div class="suggestion-description" style="margin-bottom:20px">
                <p style="color:var(--text);font-size:14px;line-height:1.5"><?= esc($suggestion['description'] ?? '') ?></p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
                <div>
                    <h4 style="margin:0 0 10px 0;color:var(--text);font-size:14px;font-weight:600">📅 Fréquence recommandée</h4>
                    <p style="margin:0;color:var(--muted);font-size:13px"><?= esc($suggestion['frequence'] ?? '') ?></p>
                </div>
                <div>
                    <h4 style="margin:0 0 10px 0;color:var(--text);font-size:14px;font-weight:600">⏱️ Durée par séance</h4>
                    <p style="margin:0;color:var(--muted);font-size:13px"><?= esc($suggestion['duree_recommandee'] ?? '') ?></p>
                </div>
            </div>

            <?php if (!empty($suggestion['avantages']) && is_array($suggestion['avantages'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px">
                    <h4 style="margin:0 0 15px 0;color:var(--text);font-size:16px;font-weight:600">✅ Avantages</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px">
                        <?php foreach ($suggestion['avantages'] as $avantage): ?>
                            <div style="display:flex;align-items:center;gap:8px;padding:8px;background:var(--bg2);border-radius:6px;border:1px solid var(--border)">
                                <span style="color:var(--green);font-size:14px">✓</span>
                                <span style="font-size:12px;color:var(--text)"><?= esc($avantage) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($suggestion['conseils']) && is_array($suggestion['conseils'])): ?>
                <div style="border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px">
                    <h4 style="margin:0 0 15px 0;color:var(--text);font-size:16px;font-weight:600">💡 Conseils pratiques</h4>
                    <div style="display:grid;grid-template-columns:1fr;gap:8px">
                        <?php foreach ($suggestion['conseils'] as $conseil): ?>
                            <div style="display:flex;align-items:flex-start;gap:8px;padding:10px;background:var(--bg2);border-radius:6px;border:1px solid var(--border)">
                                <span style="color:var(--amber);font-size:14px;margin-top:1px">💡</span>
                                <span style="font-size:13px;color:var(--text);line-height:1.4"><?= esc($conseil) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:20px;display:flex;gap:10px">
                <a href="<?= site_url('export/sport') ?>" class="btn btn-secondary" style="flex:1;text-align:center" target="_blank">
                    📄 Exporter en PDF
                </a>
                <a href="<?= site_url('sports') ?>" class="btn btn-secondary" style="flex:1;text-align:center">
                    🔄 Générer un autre sport
                </a>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-primary" style="flex:1;text-align:center">
                    🏠 Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>