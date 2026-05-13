<?= $this->extend('layouts/main') ?>

<?php
/** @var string $pageTitle */
/** @var string $pageSubtitle */
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle ?? 'Nos Offres') ?></h1>
        <p><?= esc($pageSubtitle ?? 'Choisissez le type de suggestion qui vous convient') ?></p>
    </div>
</div>

<div class="container">

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px">

        <!-- ================= REGIME SEUL ================= -->
        <a href="<?= site_url('regimes') ?>" style="text-decoration:none;color:inherit">
            <div class="card" style="transition:0.2s;cursor:pointer">
                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                        Régime seul
                    </span>
                </div>

                <div class="card-body">
                    <p style="font-size:13px;color:var(--muted);line-height:1.5">
                        Obtenez un régime personnalisé adapté à votre objectif (perte, prise ou maintien).
                    </p>

                    <div style="margin-top:15px;font-size:12px;color:var(--accent);font-weight:600">
                        → Accéder aux régimes
                    </div>
                </div>
            </div>
        </a>

        <!-- ================= SPORT SEUL ================= -->
        <a href="<?= site_url('sports') ?>" style="text-decoration:none;color:inherit">
            <div class="card" style="transition:0.2s;cursor:pointer">
                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                        Sport seul
                    </span>
                </div>

                <div class="card-body">
                    <p style="font-size:13px;color:var(--muted);line-height:1.5">
                        Recevez un programme sportif adapté à votre niveau et votre objectif.
                    </p>

                    <div style="margin-top:15px;font-size:12px;color:var(--accent);font-weight:600">
                        → Accéder aux sports
                    </div>
                </div>
            </div>
        </a>

        <!-- ================= REGIME + SPORT ================= -->
        <a href="<?= site_url('regimes_sports') ?>" style="text-decoration:none;color:inherit">
            <div class="card" style="transition:0.2s;cursor:pointer;border:2px solid var(--accent)">

                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:linear-gradient(135deg, var(--accent), var(--green))">
                            🔥
                        </span>
                        Pack complet
                    </span>

                    <span style="font-size:11px;color:var(--accent);font-weight:600">
                        RECOMMANDÉ
                    </span>
                </div>

                <div class="card-body">

                    <p style="font-size:13px;color:var(--muted);line-height:1.5">
                        La solution complète : régime + sport parfaitement combinés selon votre objectif.
                    </p>

                    <ul style="margin:10px 0 0 18px;font-size:12px;color:var(--text)">
                        <li>🥗 Régime optimisé</li>
                        <li>🏃 Programme sportif adapté</li>
                        <li>⚡ Meilleure efficacité</li>
                    </ul>

                    <div style="margin-top:15px;font-size:12px;color:var(--accent);font-weight:700">
                        → Obtenir le pack complet
                    </div>

                </div>
            </div>
        </a>

    </div>

</div>

<?= $this->endSection() ?>