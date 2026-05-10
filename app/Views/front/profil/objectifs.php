<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<int, array<string, mixed>>|null $objectifs */
/** @var array<string, mixed>|null $objectifEnCours */
/** @var array<int, array<string, mixed>>|null $listeObjectifs */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$objectifs = is_array($objectifs ?? null) ? $objectifs : [];
$objectifEnCours = is_array($objectifEnCours ?? null) ? $objectifEnCours : null;
$listeObjectifs = is_array($listeObjectifs ?? null) ? $listeObjectifs : [];

$nom = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$pageTitle = 'Mes Objectifs';
$pageSubtitle = 'Gérer vos objectifs de fitness';
$activeNav = 'objectifs';
?>

<?= $this->section('content') ?>

<style>
.objectifs-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    max-width: 900px;
}

.objectif-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.2s;
}

.objectif-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(107, 95, 224, 0.1);
}

.objectif-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.objectif-titre {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
}

.objectif-details {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: var(--muted);
}

.objectif-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.status-en-cours {
    background: rgba(107, 95, 224, 0.1);
    color: var(--accent);
}

.status-atteint {
    background: rgba(76, 175, 80, 0.1);
    color: #4caf50;
}

.status-abandonné {
    background: rgba(244, 67, 54, 0.1);
    color: #f44336;
}

.objectif-actions {
    display: flex;
    gap: 8px;
}

.btn-small {
    background: var(--bg3);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
}

.btn-small:hover {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
}

.btn-small.danger {
    background: rgba(244, 67, 54, 0.1);
    color: #f44336;
    border-color: #f44336;
}

.btn-small.danger:hover {
    background: #f44336;
    color: #fff;
    border-color: #f44336;
}

.btn-primary {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    align-self: flex-start;
}

.btn-primary:hover {
    background: #6b5fe0;
}

.btn-primary:disabled {
    background: var(--muted);
    cursor: not-allowed;
    opacity: 0.5;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--muted);
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.alert {
    padding: 14px 16px;
    border-radius: var(--radius-sm);
    margin-bottom: 16px;
    font-size: 13px;
}

.alert-error {
    background: rgba(244, 67, 54, 0.1);
    border: 1px solid rgba(244, 67, 54, 0.3);
    color: #f44336;
}

.alert-success {
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: #4caf50;
}

.warning-box {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #ff9800;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-divider {
    height: 1px;
    background: var(--border);
    margin: 28px 0;
}
</style>

<div class="objectifs-container">
    <!-- Messages d'alerte -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-error">
            <?= esc(session('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <!-- Section Objectif en cours -->
    <div>
        <div class="section-title">
            📍 Objectif en cours
        </div>

        <?php if ($objectifEnCours !== null): ?>
            <div class="objectif-card">
                <div class="objectif-info">
                    <div class="objectif-titre"><?= esc($objectifEnCours['libelle']) ?></div>
                    <div class="objectif-details">
                        <?php if (isset($objectifEnCours['date_debut'])): ?>
                            <span>Démarré le <?= date_format(date_create($objectifEnCours['date_debut']), 'd/m/Y') ?></span>
                        <?php endif; ?>
                        <?php if (($objectifEnCours['valeur_cible'] ?? null) !== null): ?>
                            <span>Objectif: <?= esc($objectifEnCours['valeur_cible']) ?> kg</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="objectif-actions">
                    <form method="post" action="/profil/objectifs/achieve/<?= $objectifEnCours['utilisateur_objectif_id'] ?>" style="display: inline;">
                        <button type="submit" class="btn-small" onclick="return confirm('Êtes-vous sûr d\'avoir atteint cet objectif ?')">
                            ✓ Atteint
                        </button>
                    </form>
                    <form method="post" action="/profil/objectifs/abandon/<?= $objectifEnCours['utilisateur_objectif_id'] ?>" style="display: inline;">
                        <button type="submit" class="btn-small danger" onclick="return confirm('Abandonner cet objectif ?')">
                            ✕ Abandonner
                        </button>
                    </form>
                </div>
            </div>

            <div class="warning-box">
                ⚠ Vous ne pouvez créer un nouveau objectif que après avoir terminé celui-ci.
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">⭕</div>
                <p>Vous n'avez pas d'objectif en cours.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bouton Créer objectif -->
    <div>
        <button onclick="window.location.href='/profil/objectifs/create'" class="btn-primary" <?= $objectifEnCours !== null ? 'disabled' : '' ?>>
            + Créer un nouvel objectif
        </button>
    </div>

    <!-- Section Historique -->
    <div>
        <div class="section-title">
            📊 Historique des objectifs
        </div>

        <?php if (!empty($objectifs)): ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($objectifs as $objectif): ?>
                    <?php if ((string)($objectif['utilisateur_objectif_id'] ?? null) !== (string)($objectifEnCours['utilisateur_objectif_id'] ?? null)): ?>
                        <div class="objectif-card">
                            <div class="objectif-info">
                                <div class="objectif-titre"><?= esc($objectif['libelle']) ?></div>
                                <div class="objectif-details">
                                    <?php if (isset($objectif['date_debut'])): ?>
                                        <span>Démarré le <?= date_format(date_create($objectif['date_debut']), 'd/m/Y') ?></span>
                                    <?php endif; ?>
                                    <?php if (($objectif['date_fin'] ?? null) !== null): ?>
                                        <span>Terminé le <?= date_format(date_create($objectif['date_fin']), 'd/m/Y') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (isset($objectif['statut'])): ?>
                                <div class="objectif-status <?= 'status-' . str_replace('é', 'e', strtolower($objectif['statut'])) ?>">
                                    <?php if ($objectif['statut'] === 'atteint'): ?>
                                        ✓ Atteint
                                    <?php elseif ($objectif['statut'] === 'abandonné'): ?>
                                        ✕ Abandonné
                                    <?php else: ?>
                                        ⊙ En cours
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Aucun objectif dans l'historique.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>