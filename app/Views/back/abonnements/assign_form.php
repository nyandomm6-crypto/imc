<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = 'Assigner Abonnement';
$activeNav = 'abonnements';
$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
?>

<?= $this->section('content') ?>

<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <a href="/admin/abonnements/utilisateurs" style="color: #2196f3; text-decoration: none; margin-bottom: 16px; display: inline-block;">← Retour</a>

    <div style="background: white; border-radius: 4px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h2 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 700;"><?= esc($utilisateur['nom'] ?? '') ?></h2>
        <p style="margin: 0; color: #666;"><?= esc($utilisateur['email'] ?? '') ?></p>
    </div>

    <?php if (session()->has('errors')): ?>
        <div style="background: rgba(244, 67, 54, 0.1); border: 1px solid #f44336; color: #f44336; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
            <strong>Erreurs:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/admin/abonnements/utilisateurs/store/<?= $utilisateur['id'] ?>" 
          style="display: flex; flex-direction: column; gap: 16px; background: white; border-radius: 4px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

        <!-- Option d'abonnement -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Option d'abonnement *</label>
            <select name="option_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($options as $opt): ?>
                    <option value="<?= $opt['id'] ?>" <?= (old('option_id') ?? ($aboActuel['option_id'] ?? '')) == $opt['id'] ? 'selected' : '' ?>>
                        <?= esc($opt['nom']) ?> - <?= number_format($opt['prix'], 2) ?>€/mois
                        <?php if (!empty($opt['description'])): ?>
                            (<?= esc($opt['description']) ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date d'expiration -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Date d'expiration (optionnel)</label>
            <input type="date" name="date_fin" value="<?= old('date_fin') ?? '' ?>" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
            <small style="color: #666; display: block; margin-top: 4px;">Laisser vide pour un abonnement illimité</small>
        </div>

        <!-- Boutons -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" style="flex: 1; background: #4caf50; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                ✅ Attribuer
            </button>
            <a href="/admin/abonnements/utilisateurs" style="flex: 1; background: #999; color: white; padding: 12px; border-radius: 4px; font-weight: 600; text-align: center; text-decoration: none;">
                Annuler
            </a>
        </div>
    </form>

    <!-- Abonnement actuel -->
    <?php if (!empty($aboActuel) && isset($aboActuel['id'])): ?>
        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 16px; margin-top: 20px;">
            <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #856404;">Abonnement Actuel</h3>
            <p style="margin: 0; color: #856404;">
                Cet utilisateur a actuellement un abonnement actif. Attribuer un nouvel abonnement va créer une nouvelle souscription.
            </p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
