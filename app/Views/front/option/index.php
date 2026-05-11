<?= $this->extend('layouts/main') ?>

<?php
/** @var array<int, array<string, mixed>>|null $options */
/** @var array<string, mixed>|null $abonnementActif */
/** @var array<string, mixed>|null $utilisateur */

// Importer le modèle CompteModel pour afficher le solde
$compteModel = new \App\Models\CompteModel();

$options = is_array($options ?? null) ? $options : [];
$abonnementActif = is_array($abonnementActif ?? null) ? $abonnementActif : null;
$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$userId = session()->get('user_id');
$soldeActuel = $userId ? $compteModel->getSolde($userId) : 0;

$pageTitle = 'Acheter une option';
$pageSubtitle = 'Découvrez nos offres exclusives';
$pageIcon = '🛍️';
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p><?= esc($pageSubtitle) ?></p>
    </div>
</div>

<div class="container">
    <!-- Solde actuel -->
    <div class="solde-info">
        <div class="solde-label">💰 Solde disponible</div>
        <div class="solde-amount"><?= number_format($soldeActuel, 2, ',', ' ') ?> €</div>
        <a href="/porte-monnaie" class="solde-link">Gérer mon porte-monnaie →</a>
    </div>

    <!-- Messages de session -->
    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span><?= esc($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠</span>
            <span><?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Abonnement actif -->
    <?php if ($abonnementActif): ?>
        <div class="info-box info-active">
            <div class="info-icon">✓</div>
            <div class="info-content">
                <div class="info-title">Abonnement actif</div>
                <div class="info-text">
                    Vous êtes abonné à <strong><?= esc($abonnementActif['nom'] ?? 'Abonnement') ?></strong>
                    jusqu'au <?= isset($abonnementActif['date_fin']) ? date('d/m/Y', strtotime($abonnementActif['date_fin'])) : 'indéterminé' ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Grille des options -->
    <div class="options-grid">
        <?php if (empty($options)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div class="empty-title">Aucune option disponible</div>
                <div class="empty-text">Revenez plus tard pour découvrir nos nouvelles offres.</div>
            </div>
        <?php else: ?>
            <?php foreach ($options as $option): ?>
                <?php
                    $prix = (float)($option['prix'] ?? 0);
                    $canBuy = $soldeActuel >= $prix && (!$abonnementActif || $abonnementActif['option_id'] != $option['id']);
                ?>
                <div class="option-card <?= !$canBuy && $soldeActuel < $prix ? 'card-disabled' : '' ?>">
                    <div class="option-header">
                        <div class="option-badge">Option</div>
                        <?php if ($abonnementActif && $abonnementActif['option_id'] == $option['id']): ?>
                            <div class="option-badge badge-active">Actuel</div>
                        <?php elseif ($soldeActuel < $prix): ?>
                            <div class="option-badge badge-insufficient">Solde insuffisant</div>
                        <?php endif; ?>
                    </div>

                    <div class="option-title">
                        <?= esc($option['nom'] ?? 'Option') ?>
                    </div>

                    <div class="option-price">
                        <span class="price-amount"><?= number_format($prix, 2, ',', ' ') ?></span>
                        <span class="price-currency">€</span>
                    </div>

                    <?php if (isset($option['description']) && $option['description']): ?>
                        <div class="option-description">
                            <?= esc($option['description']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="option-features">
                        <?php if (isset($option['features']) && is_array($option['features'])): ?>
                            <?php foreach ($option['features'] as $feature): ?>
                                <div class="feature-item">
                                    <span class="feature-icon">✓</span>
                                    <span><?= esc($feature) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="option-actions">
                        <form method="post" action="<?= site_url('option/acheter') ?>" style="width: 100%;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="option_id" value="<?= $option['id'] ?? '' ?>">
                            
                            <?php if ($abonnementActif && $abonnementActif['option_id'] == $option['id']): ?>
                                <button type="button" class="btn-primary" disabled>
                                    Abonnement actif
                                </button>
                            <?php elseif ($soldeActuel < $prix): ?>
                                <button type="button" class="btn-primary" disabled title="Solde insuffisant">
                                    Solde insuffisant
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn-primary">
                                    Acheter maintenant
                                </button>
                            <?php endif; ?>
                        </form>
                        
                        <a href="<?= site_url('option/detail/' . ($option['id'] ?? '')) ?>" class="btn-secondary">
                            Détails →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.solde-info {
    background: linear-gradient(135deg, var(--green) 0%, rgba(34, 197, 94, 0.1) 100%);
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.solde-label {
    font-size: 13px;
    color: var(--muted);
    font-weight: 600;
}

.solde-amount {
    font-size: 32px;
    font-weight: 700;
    color: var(--green);
}

.solde-link {
    color: var(--accent2);
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s;
}

.solde-link:hover {
    text-decoration: underline;
}

.page-header {
    background: linear-gradient(135deg, var(--accent2) 0%, rgba(124, 110, 245, 0.1) 100%);
    padding: 32px 24px;
    border-radius: 12px;
    margin-bottom: 32px;
    text-align: center;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--text);
}

.page-header p {
    font-size: 14px;
    color: var(--muted);
    margin: 0;
}

.alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    color: var(--green);
    border-left: 3px solid var(--green);
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: var(--red);
    border-left: 3px solid var(--red);
}

.alert-icon {
    font-weight: 700;
}

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 28px;
    background: var(--bg2);
    border: 1px solid var(--border);
}

.info-active {
    background: rgba(34, 197, 94, 0.08);
    border-color: rgba(34, 197, 94, 0.2);
}

.info-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text);
}

.info-text {
    font-size: 13px;
    color: var(--muted);
}

.options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.option-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.option-card:hover {
    border-color: var(--accent2);
    box-shadow: 0 8px 24px rgba(124, 110, 245, 0.15);
    transform: translateY(-2px);
}

.option-card.card-disabled {
    opacity: 0.7;
    pointer-events: none;
}

.option-card.card-disabled:hover {
    border-color: var(--border);
    box-shadow: none;
    transform: none;
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    gap: 8px;
    flex-wrap: wrap;
}

.option-badge {
    font-size: 11px;
    font-weight: 600;
    background: var(--bg3);
    color: var(--muted);
    padding: 4px 12px;
    border-radius: 4px;
    text-transform: uppercase;
}

.badge-active {
    background: rgba(34, 197, 94, 0.15);
    color: var(--green);
}

.badge-insufficient {
    background: rgba(239, 68, 68, 0.15);
    color: var(--red);
}

.option-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--text);
}

.option-price {
    display: flex;
    align-items: baseline;
    gap: 4px;
    margin-bottom: 16px;
}

.price-amount {
    font-size: 28px;
    font-weight: 700;
    color: var(--accent2);
}

.price-currency {
    font-size: 16px;
    color: var(--muted);
}

.option-description {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 16px;
    line-height: 1.5;
}

.option-features {
    flex: 1;
    margin-bottom: 16px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text);
    margin-bottom: 8px;
}

.feature-icon {
    color: var(--green);
    font-weight: 700;
    flex-shrink: 0;
}

.option-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: auto;
}

.btn-primary, .btn-secondary {
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: block;
}

.btn-primary {
    background: var(--accent2);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: rgba(124, 110, 245, 0.9);
    transform: scale(1.02);
}

.btn-primary:disabled {
    background: var(--muted);
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-secondary {
    background: transparent;
    color: var(--accent2);
    border: 1px solid var(--accent2);
}

.btn-secondary:hover {
    background: rgba(124, 110, 245, 0.08);
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 24px;
    color: var(--muted);
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text);
}

.empty-text {
    font-size: 14px;
}

@media (max-width: 768px) {
    .options-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        padding: 24px 16px;
    }

    .page-header h1 {
        font-size: 22px;
    }

    .solde-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>

<?= $this->endSection() ?>
