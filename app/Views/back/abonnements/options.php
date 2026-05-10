<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Options d\'Abonnement';
$activeNav = 'abonnements';
?>

<?= $this->section('content') ?>

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>
        <a href="/admin/abonnements/options/create" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">
            + Nouvelle Option
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($options)): ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Nom</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Description</th>
                    <th style="padding: 12px 16px; text-align: right; font-weight: 600;">Prix (€)</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($options as $option): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 16px;">
                            <strong><?= esc($option['nom']) ?></strong>
                            <?php if (strtolower($option['nom']) === 'gold'): ?>
                                <span style="background: #ffd700; color: #333; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: 600; margin-left: 8px;">★ GOLD</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; color: #666;">
                            <?= esc($option['description'] ?? '-') ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right; font-weight: 600;">
                            <?= number_format($option['prix'], 2) ?>€
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="/admin/abonnements/options/edit/<?= $option['id'] ?>" style="color: #2196f3; text-decoration: none; margin-right: 12px;">✏️ Éditer</a>
                            <form method="post" action="/admin/abonnements/options/delete/<?= $option['id'] ?>" style="display: inline;" 
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
            <p style="font-size: 16px; color: #999;">Aucune option trouvée</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
