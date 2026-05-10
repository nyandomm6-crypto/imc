<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Détail de Demande';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <a href="/admin/offres/demandes/all" style="color: #2196f3; text-decoration: none; margin-bottom: 16px; display: inline-block;">← Retour</a>

    <div style="background: white; border-radius: 4px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700;">
                <?= esc($demande['offre_nom']) ?>
            </h1>
            <span style="padding: 8px 12px; border-radius: 3px; font-size: 12px; font-weight: 600;
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
        </div>

        <!-- Utilisateur -->
        <div style="background: #f5f5f5; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; text-transform: uppercase; color: #666;">Utilisateur</h3>
            <p style="margin: 0; font-size: 16px;"><strong><?= esc($demande['user_nom']) ?></strong></p>
            <p style="margin: 4px 0 0 0; font-size: 14px; color: #666;"><?= esc($demande['email']) ?></p>
            <?php if (!empty($demande['abo_nom'])): ?>
                <p style="margin: 8px 0 0 0; font-size: 13px;">
                    <strong>Abonnement:</strong> 
                    <span style="background: <?= strtolower($demande['abo_nom']) === 'gold' ? '#ffd700' : '#e0e0e0' ?>; 
                          padding: 2px 6px; border-radius: 3px;">
                        <?= esc($demande['abo_nom'] ?? 'Aucun') ?>
                    </span>
                </p>
            <?php endif; ?>
        </div>

        <!-- Offre -->
        <div style="background: #f5f5f5; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; text-transform: uppercase; color: #666;">Offre</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 6px 0; color: #666;">Type:</td>
                    <td style="padding: 6px 0; font-weight: 600;">
                        <?php 
                            $types = ['regime' => '🍽️ Régime', 'sport' => '🏃 Sport', 'regime_sport' => '🍽️🏃 Régime + Sport'];
                            echo $types[$demande['type']] ?? $demande['type'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #666;">Prix Normal:</td>
                    <td style="padding: 6px 0; font-weight: 600;"><?= number_format($demande['prix'], 2) ?>€</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #666;">Prix Gold:</td>
                    <td style="padding: 6px 0; font-weight: 600; color: #ffa500;"><?= number_format($demande['prix_gold'], 2) ?>€</td>
                </tr>
            </table>
        </div>

        <!-- Suggestions -->
        <?php if (!empty($demande['regime_libelle']) || !empty($demande['sport_libelle'])): ?>
            <div style="background: #f5f5f5; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; text-transform: uppercase; color: #666;">Éléments Suggérés</h3>
                <?php if (!empty($demande['regime_libelle'])): ?>
                    <p style="margin: 0; padding: 8px; background: white; border-left: 3px solid #2196f3; border-radius: 2px;">
                        <strong>🍽️ Régime:</strong> <?= esc($demande['regime_libelle']) ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($demande['sport_libelle'])): ?>
                    <p style="margin: 8px 0 0 0; padding: 8px; background: white; border-left: 3px solid #4caf50; border-radius: 2px;">
                        <strong>🏃 Sport:</strong> <?= esc($demande['sport_libelle']) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Prix Payé -->
        <?php if (!empty($demande['prix_paye'])): ?>
            <div style="background: #d4edda; padding: 16px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <h3 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #155724;">Prix Payé</h3>
                <p style="margin: 0; font-size: 20px; font-weight: 700; color: #155724;">
                    <?= number_format($demande['prix_paye'], 2) ?>€
                </p>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <?php if ($demande['statut'] === 'demande'): ?>
            <div style="display: flex; gap: 12px;">
                <form method="post" action="/admin/offres/demandes/accept/<?= $demande['id'] ?>" style="flex: 1;">
                    <button type="submit" style="width: 100%; background: #4caf50; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        ✓ Accepter
                    </button>
                </form>
                <form method="post" action="/admin/offres/demandes/reject/<?= $demande['id'] ?>" style="flex: 1;">
                    <button type="submit" style="width: 100%; background: #f44336; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        ✕ Rejeter
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
