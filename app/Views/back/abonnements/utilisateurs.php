<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Gestion des Abonnements';
$activeNav = 'abonnements';
?>

<?= $this->section('content') ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>
        <div style="display: flex; gap: 12px;">
            <a href="/admin/abonnements/stats" style="background: #2196f3; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                📊 Statistiques
            </a>
            <a href="/admin/abonnements/options" style="background: #9c27b0; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                ⚙️ Gérer Options
            </a>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($utilisateurs)): ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Utilisateur</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Abonnement</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Dates</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Statut</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 16px;">
                            <strong><?= esc($utilisateur['nom']) ?></strong>
                            <br><small style="color: #666;"><?= esc($utilisateur['email']) ?></small>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <span style="background: <?= strtolower($utilisateur['abo_type']) === 'gold' ? '#ffd700' : '#e0e0e0' ?>; 
                                      color: <?= strtolower($utilisateur['abo_type']) === 'gold' ? '#333' : '#666' ?>; 
                                      padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                <?= esc($utilisateur['abo_type'] ?? 'Aucun') ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #666;">
                            <?php if (!empty($utilisateur['date_debut'])): ?>
                                Du <?= date_format(date_create($utilisateur['date_debut']), 'd/m/Y') ?>
                                <?php if (!empty($utilisateur['date_fin'])): ?>
                                    au <?= date_format(date_create($utilisateur['date_fin']), 'd/m/Y') ?>
                                <?php else: ?>
                                    (Sans limite)
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <span style="background: <?= $utilisateur['statut'] === 'Actif' ? '#d4edda' : '#f8d7da' ?>; 
                                      color: <?= $utilisateur['statut'] === 'Actif' ? '#155724' : '#721c24' ?>; 
                                      padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">
                                <?= esc($utilisateur['statut']) ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="/admin/abonnements/utilisateurs/assign/<?= $utilisateur['id'] ?>" style="color: #2196f3; text-decoration: none;">
                                📝 Gérer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: #f5f5f5; border-radius: 4px;">
            <p style="font-size: 16px; color: #999;">Aucun utilisateur trouvé</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
