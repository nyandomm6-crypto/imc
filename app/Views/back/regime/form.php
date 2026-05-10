<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="/css/regime-form.css">

<?php
$regimeData = is_array($regime ?? null) ? $regime : [];
$offreLabel = ! empty($regimeData['offre']['nom']) ? $regimeData['offre']['nom'] : 'Aucune offre sélectionnée';
$alimentOptions = '';
foreach ($aliments as $a) {
	$alimentOptions .= '<option value="' . $a['id'] . '">' . esc($a['nom']) . '</option>';
}
?>

<div class="regime-form-page">
    <div class="regime-form-header">
        <div>
            <div class="regime-breadcrumb">
                <a href="/admin/regimes">Régimes</a>
                <span>/</span>
                <span><?= ! empty($regimeData) ? 'Édition' : 'Création' ?></span>
            </div>
            <h1 class="regime-form-title"><?= ! empty($regimeData) ? 'Modifier le régime' : 'Créer un nouveau régime' ?></h1>
            <p class="regime-form-intro">
                Définissez le libellé, rattachez le régime à une offre et composez-le avec des aliments et leurs pourcentages.
            </p>
        </div>

        <div class="regime-form-summary">
            <span class="regime-form-summary-label">Offre actuelle</span>
            <strong><?= esc($offreLabel) ?></strong>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="regime-alert regime-alert-error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= ! empty($regimeData) ? '/admin/regimes/update/' . $regimeData['id'] : '/admin/regimes/store' ?>" class="regime-form">
        <?= csrf_field() ?>

        <section class="regime-card">
            <div class="regime-card-body">
                <div class="regime-section-title">
                    <h2>Informations générales</h2>
                    <p>Les champs principaux déterminent l’identité et l’offre liée à ce régime.</p>
                </div>

                <div class="regime-field-grid">
                    <div class="regime-field">
                        <label class="regime-label" for="libelle">Libellé du régime *</label>
                        <input id="libelle" type="text" name="libelle" class="regime-input" value="<?= old('libelle', $regimeData['libelle'] ?? '') ?>" placeholder="Ex: Régime Kéto, Régime Méditerranéen..." required>
                    </div>

                    <div class="regime-field">
                        <label class="regime-label" for="offre_id">Offre associée *</label>
                        <?php $selOffre = (int) old('offre_id', (int) ($regimeData['offre_id'] ?? 0)); ?>
                        <select id="offre_id" name="offre_id" class="regime-input" required>
                            <option value="">Choisir une offre</option>
                            <?php if (! empty($offres ?? [])): ?>
                                <?php foreach ($offres as $o): ?>
                                    <option value="<?= $o['id'] ?>" <?= $selOffre === (int) $o['id'] ? 'selected' : '' ?>><?= esc($o['nom']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="regime-card">
            <div class="regime-card-body">
                <div class="regime-section-title">
                    <h2>Composition du régime</h2>
                    <p>Ajoutez un ou plusieurs aliments, puis indiquez leur répartition en pourcentage.</p>
                </div>

                <div id="recettes-container" class="regime-recipes">
                        <?php if (! empty($regimeData['aliments'] ?? [])): ?>
                            <?php foreach ($regimeData['aliments'] as $r): ?>
                                <div class="recette-row regime-recipe-row">
                                    <select name="aliment_id[]" class="regime-input regime-input-flex">
                                        <option value="">-- Sélectionner un aliment --</option>
                                        <?php foreach ($aliments as $a): ?>
                                            <option value="<?= $a['id'] ?>" <?= $a['id'] == ($r['aliment_id'] ?? '') ? 'selected' : '' ?>><?= esc($a['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" step="0.1" min="0" max="100" name="pourcentage[]" class="regime-input regime-input-percent" placeholder="%" value="<?= old('pourcentage[]', $r['pourcentage'] ?? '') ?>">
                                    <button type="button" class="regime-button regime-button-ghost remove-recette">Retirer</button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="recette-row regime-recipe-row">
                                <select name="aliment_id[]" class="regime-input regime-input-flex">
                                    <option value="">-- Sélectionner un aliment --</option>
                                    <?php if (! empty($aliments ?? [])): ?>
                                        <?php foreach ($aliments as $a): ?>
                                            <option value="<?= $a['id'] ?>"><?= esc($a['nom']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <input type="number" step="0.1" min="0" max="100" name="pourcentage[]" class="regime-input regime-input-percent" placeholder="%">
                                <button type="button" class="regime-button regime-button-ghost remove-recette">Retirer</button>
                            </div>
                        <?php endif; ?>
                </div>

                <div class="regime-actions-inline">
                    <button type="button" id="add-recette" class="regime-button regime-button-primary">Ajouter un composant</button>
                </div>
            </div>
        </section>

        <section class="regime-card regime-card-actions">
            <div class="regime-card-body regime-card-footer">
                <button type="submit" class="regime-button regime-button-primary"><?= ! empty($regimeData) ? 'Mettre à jour' : 'Créer le régime' ?></button>
                <a href="/admin/regimes" class="regime-button regime-button-secondary">Annuler</a>
            </div>
        </section>

    </form>

<script>
document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'add-recette') {
        const container = document.getElementById('recettes-container');
        const row = document.createElement('div');
        row.className = 'recette-row regime-recipe-row';
        row.innerHTML = `
            <select name="aliment_id[]" class="regime-input regime-input-flex">
                <option value="">-- Sélectionner un aliment --</option>
                <?= $alimentOptions ?>
            </select>
            <input type="number" step="0.1" min="0" max="100" name="pourcentage[]" class="regime-input regime-input-percent" placeholder="%">
            <button type="button" class="regime-button regime-button-ghost remove-recette">Retirer</button>
        `;
        container.appendChild(row);
    }

    if (e.target && e.target.classList && e.target.classList.contains('remove-recette')) {
        const row = e.target.closest('.recette-row');
        if (row) row.remove();
    }
});
</script>

</div>

<?= $this->endSection() ?>
