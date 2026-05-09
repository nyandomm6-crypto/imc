<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- --style.css -->
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">



<?php
$nom       = "huhu";
$initiales = "H";
?>
<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">💪</div>
            <span class="logo-text">FitIMC</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <a class="nav-item" href="/dashboard">
                <span class="ni">⊞</span> Dashboard
            </a>
            <a class="nav-item active" href="/profil">
                <span class="ni">◎</span> Mon profil
            </a>
            <a class="nav-item" href="/profil/objectifs">
                <span class="ni">◈</span> Objectifs
            </a>

            <div class="nav-section">Nutrition</div>
            <a class="nav-item" href="/regimes">
                <span class="ni">🥗</span> Régimes
            </a>
            <a class="nav-item" href="/sports">
                <span class="ni">🏃</span> Sports
            </a>

            <div class="nav-section">Compte</div>
            <a class="nav-item" href="/porte-monnaie">
                <span class="ni">◈</span> Porte-monnaie
            </a>
            <a class="nav-item" href="/gold">
                <span class="ni">★</span> Passer Gold
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar"><?= esc($initiales) ?></div>
            <div>
                <div class="user-name"><?= esc(strlen($nom) > 14 ? substr($nom, 0, 14) . '…' : $nom) ?></div>
                <div class="user-role">Membre</div>
            </div>
            <a href="/logout" title="Déconnexion">⏻</a>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">Profil</div>
                <div class="topbar-sub">Bonjour, <?= esc($nom) ?> 👋</div>
            </div>
            <a href="/gold" class="btn-gold">★ Option Gold — 15% de remise</a>
        </div>
        <div> content</div>
    </div><!-- /main -->
</div><!-- /layout -->

<?= $this->endSection() ?>