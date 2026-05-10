<?php $this->extend('layouts/admin') ?>

<?php
$pageTitle = $offre !== null ? 'Éditer l\'Offre' : 'Créer une Offre';
$activeNav = 'offres';
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

    <form method="post" action="<?= $offre ? '/admin/offres/update/' . $offre['id'] : '/admin/offres/store' ?>" style="display: flex; flex-direction: column; gap: 16px;">

        <!-- Nom -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Nom de l'offre *</label>
            <input type="text" name="nom" value="<?= old('nom') ?? ($offre['nom'] ?? '') ?>" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                   required>
        </div>

        <!-- Type -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Type d'offre *</label>
            <select name="type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($types as $value => $label): ?>
                    <option value="<?= $value ?>" <?= (old('type') ?? ($offre['type'] ?? '')) === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Prix Normal -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Prix Normal (€) *</label>
            <input type="number" name="prix" value="<?= old('prix') ?? ($offre['prix'] ?? '') ?>" 
                   step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                   required>
        </div>

        <!-- Prix Gold -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Prix Gold (14% réduction) (€) *</label>
            <input type="number" name="prix_gold" value="<?= old('prix_gold') ?? ($offre['prix_gold'] ?? '') ?>" 
                   step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                   required>
            <small style="color: #666; display: block; margin-top: 4px;">
                💡 La remise Gold doit être environ 14% moins cher que le prix normal
            </small>
        </div>

        <!-- Description -->
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Description</label>
            <textarea name="description" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; min-height: 100px; font-family: inherit;"><?= old('description') ?? ($offre['description'] ?? '') ?></textarea>
        </div>

        <!-- Boutons -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" style="flex: 1; background: #4caf50; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
                <?= $offre ? '💾 Mettre à jour' : '✅ Créer' ?>
            </button>
            <a href="/admin/offres" style="flex: 1; background: #999; color: white; padding: 12px; border-radius: 4px; font-weight: 600; text-align: center; text-decoration: none;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
