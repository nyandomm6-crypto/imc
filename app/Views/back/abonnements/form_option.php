<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = $option !== null ? 'Éditer l\'Option' : 'Créer une Option';
$activeNav = 'abonnements';
?>

<?= $this->section('content') ?>

<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 24px; font-size: 28px; font-weight: 700;"><?= $pageTitle ?></h1>

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

    <form method="post" action="<?= $option ? '/admin/abonnements/options/update/' . $option['id'] : '/admin/abonnements/options/store' ?>" 
          style="display: flex; flex-direction: column; gap: 16px;">

        <!-- Nom -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Nom de l'option *</label>
            <input type="text" name="nom" value="<?= old('nom') ?? ($option['nom'] ?? '') ?>" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                   required>
            <small style="color: #666; display: block; margin-top: 4px;">Exemple: Gold, Diamond, etc.</small>
        </div>

        <!-- Prix -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Prix Mensuel (€) *</label>
            <input type="number" name="prix" value="<?= old('prix') ?? ($option['prix'] ?? '') ?>" 
                   step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Description</label>
            <textarea name="description" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; min-height: 80px; font-family: inherit;"><?= old('description') ?? ($option['description'] ?? '') ?></textarea>
        </div>

        <!-- Boutons -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" style="flex: 1; background: #4caf50; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                <?= $option ? '💾 Mettre à jour' : '✅ Créer' ?>
            </button>
            <a href="/admin/abonnements/options" style="flex: 1; background: #999; color: white; padding: 12px; border-radius: 4px; font-weight: 600; text-align: center; text-decoration: none;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
