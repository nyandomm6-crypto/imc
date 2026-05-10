<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Demandes d\'Offres';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 24px; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>

    <?php if (session()->has('success')): ?>
        <div style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($demandes)): ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Utilisateur</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Offre</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Statut</th>
                    <th style="padding: 12px 16px; text-align: right; font-weight: 600;">Prix</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 16px;">
                            <strong><?= esc($demande['user_nom']) ?></strong>
                            <br><small style="color: #666;"><?= esc($demande['email']) ?></small>
                        </td>
                        <td style="padding: 12px 16px;">
                            <strong><?= esc($demande['offre_nom']) ?></strong>
                            <?php if (!empty($demande['regime_libelle']) || !empty($demande['sport_libelle'])): ?>
                                <br>
                                <?php if (!empty($demande['regime_libelle'])): ?>
                                    <small style="color: #666;">🍽️ <?= esc($demande['regime_libelle']) ?></small><br>
                                <?php endif; ?>
                                <?php if (!empty($demande['sport_libelle'])): ?>
                                    <small style="color: #666;">🏃 <?= esc($demande['sport_libelle']) ?></small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <span style="padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;
                                         background: <?= 
                                            $demande['statut'] === 'demande' ? '#fff3cd' : 
                                            ($demande['statut'] === 'acceptée' ? '#d4edda' : '#f8d7da') 
                                         ?>; 
                                         color: <?= 
                                            $demande['statut'] === 'demande' ? '#856404' : 
                                            ($demande['statut'] === 'acceptée' ? '#155724' : '#721c24') 
                                         ?>;">
                                <?= esc($demande['statut']) ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <?php if (!empty($demande['prix_paye'])): ?>
                                <strong><?= number_format($demande['prix_paye'], 2) ?>€</strong>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="/admin/offres/demandes/<?= $demande['id'] ?>" style="color: #2196f3; text-decoration: none;">👁️ Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: #f5f5f5; border-radius: 4px;">
            <p style="font-size: 16px; color: #999;">Aucune demande trouvée</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
