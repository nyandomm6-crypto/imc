<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Gestion des Offres';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>
        <a href="/admin/offres/create" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">
            + Nouvelle Offre
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div style="background: rgba(244, 67, 54, 0.1); border: 1px solid #f44336; color: #f44336; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            ✕ <?= esc(session('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($offres)): ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Nom</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Type</th>
                    <th style="padding: 12px 16px; text-align: right; font-weight: 600;">Prix Normal</th>
                    <th style="padding: 12px 16px; text-align: right; font-weight: 600;">Prix Gold</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offres as $offre): ?>
                    <tr style="border-bottom: 1px solid #eee; transition: 0.2s;">
                        <td style="padding: 12px 16px;">
                            <strong><?= esc($offre['nom']) ?></strong>
                            <?php if (!empty($offre['description'])): ?>
                                <br><small style="color: #666;"><?= substr(esc($offre['description']), 0, 60) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                <?php 
                                    $types = ['regime' => '🍽️ Régime', 'sport' => '🏃 Sport', 'regime_sport' => '🍽️🏃 Combo'];
                                    echo $types[$offre['type']] ?? $offre['type'];
                                ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <?= number_format($offre['prix'], 2) ?>€
                        </td>
                        <td style="padding: 12px 16px; text-align: right; color: #ffa500;">
                            <strong><?= number_format($offre['prix_gold'], 2) ?>€</strong>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="/admin/offres/edit/<?= $offre['id'] ?>" style="color: #2196f3; text-decoration: none; margin-right: 12px;">✏️ Éditer</a>
                            <form method="post" action="/admin/offres/delete/<?= $offre['id'] ?>" style="display: inline;" 
                                  onsubmit="return confirm('Êtes-vous sûr ?');">
                                <button type="submit" style="background: none; border: none; color: #f44336; cursor: pointer; text-decoration: none;">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: #f5f5f5; border-radius: 4px;">
            <p style="font-size: 16px; color: #999;">Aucune offre trouvée</p>
            <a href="/admin/offres/create" style="color: #2196f3; text-decoration: none;">Créer une nouvelle offre</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
