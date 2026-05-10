<?= $this->extend('layouts/main') ?>

<?php
/** @var array<int, array<string, mixed>>|null $listeObjectifs */

$listeObjectifs = is_array($listeObjectifs ?? null) ? $listeObjectifs : [];

$pageTitle = 'Créer un objectif';
$pageSubtitle = 'Définir votre nouvel objectif de fitness';
$activeNav = 'objectifs';
?>

<?= $this->section('content') ?>

<style>
.form-container {
    max-width: 600px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 28px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.form-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
}

.form-input,
.form-select {
    width: 100%;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    font-size: 14px;
    padding: 12px 14px;
    font-family: 'Inter', sans-serif;
    transition: 0.2s;
    outline: none;
    appearance: none;
}

.form-input:focus,
.form-select:focus {
    border-color: var(--accent);
    background: var(--bg1);
    box-shadow: 0 0 0 3px rgba(107, 95, 224, 0.1);
}

.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b7e' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 40px;
}

.form-hint {
    font-size: 12px;
    color: var(--muted);
    margin-top: 4px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
}

.btn-primary {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
}

.btn-primary:hover {
    background: #6b5fe0;
}

.btn-secondary {
    background: var(--bg3);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
}

.btn-secondary:hover {
    background: var(--bg2);
    border-color: var(--accent);
}

.alert-info {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.3);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #2196f3;
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>

<div class="form-container">
    <div class="alert-info">
        ℹ️ Vous ne pouvez avoir qu'un seul objectif actif à la fois.
    </div>

    <form method="post" action="/profil/objectifs/store">
        <div class="form-group">
            <label class="form-label" for="objectif_id">
                Type d'objectif
            </label>
            <select class="form-select" id="objectif_id" name="objectif_id" required>
                <option value="">-- Sélectionnez un objectif --</option>
                <?php foreach ($listeObjectifs as $objectif): ?>
                    <option value="<?= $objectif['id'] ?>">
                        <?= esc($objectif['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="form-hint">Choisissez le type d'objectif que vous souhaitez poursuivre.</p>
        </div>

        <div class="form-group">
            <label class="form-label" for="valeur_cible">
                Valeur cible (optionnel)
            </label>
            <input 
                type="number" 
                class="form-input" 
                id="valeur_cible" 
                name="valeur_cible"
                step="0.1"
                placeholder="Ex: 75.5"
            >
            <p class="form-hint">Entrez votre poids cible en kilogrammes, ou laissez vide si non applicable.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                ✓ Créer l'objectif
            </button>
            <button type="button" class="btn-secondary" onclick="history.back()">
                ← Annuler
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
