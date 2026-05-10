<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Statistiques - Abonnements & Offres';
$activeNav = 'abonnements';
?>

<?= $this->section('content') ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 24px; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>

    <!-- Cartes de statistiques -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        <!-- Total Utilisateurs -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2196f3;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Total Utilisateurs</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2196f3;">
                <?= number_format($stats['total_utilisateurs'] ?? 0) ?>
            </p>
        </div>

        <!-- Utilisateurs Gold -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #ffd700;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Utilisateurs Gold</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ffa500;">
                <?= number_format($stats['utilisateurs_gold'] ?? 0) ?>
            </p>
            <p style="margin: 8px 0 0 0; font-size: 12px; color: #999;">
                <?= round(($stats['utilisateurs_gold'] ?? 0) / max($stats['total_utilisateurs'] ?? 1, 1) * 100, 1) ?>% du total
            </p>
        </div>

        <!-- Total Offres -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #4caf50;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Total Offres</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #4caf50;">
                <?= number_format($stats['total_offres'] ?? 0) ?>
            </p>
        </div>
    </div>

    <!-- Demandes -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        <!-- Demandes en attente -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #ff9800;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Demandes en Attente</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ff9800;">
                <?= number_format($stats['demandes_en_attente'] ?? 0) ?>
            </p>
        </div>

        <!-- Demandes acceptées -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #4caf50;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Demandes Acceptées</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #4caf50;">
                <?= number_format($stats['demandes_acceptees'] ?? 0) ?>
            </p>
        </div>

        <!-- Revenu total -->
        <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #9c27b0;">
            <p style="margin: 0 0 12px 0; color: #666; font-size: 13px; font-weight: 600; text-transform: uppercase;">Revenu Total</p>
            <p style="margin: 0; font-size: 32px; font-weight: 700; color: #9c27b0;">
                <?= number_format($stats['revenu_total'] ?? 0, 2) ?>€
            </p>
        </div>
    </div>

    <!-- Actions rapides -->
    <div style="background: white; border-radius: 4px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Actions Rapides</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="/admin/offres/demandes/all" style="background: #2196f3; color: white; padding: 10px 16px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                👁️ Voir Demandes en Attente
            </a>
            <a href="/admin/abonnements/utilisateurs" style="background: #ffd700; color: #333; padding: 10px 16px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                ⭐ Gérer Abonnements Gold
            </a>
            <a href="/admin/offres" style="background: #4caf50; color: white; padding: 10px 16px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                📦 Gérer Offres
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
