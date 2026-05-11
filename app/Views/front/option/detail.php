<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $option */
/** @var array<string, mixed>|null $abonnementActif */
/** @var array<string, mixed>|null $utilisateur */

// Importer le modèle CompteModel pour afficher le solde
$compteModel = new \App\Models\CompteModel();

$option = is_array($option ?? null) ? $option : [];
$abonnementActif = is_array($abonnementActif ?? null) ? $abonnementActif : null;
$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$userId = session()->get('user_id');
$soldeActuel = $userId ? $compteModel->getSolde($userId) : 0;
$prixOption = (float)($option['prix'] ?? 0);
$soldeInsuffisant = $soldeActuel < $prixOption;

$pageTitle = 'Détails de l\'option';
$pageIcon = '📋';
?>

<?= $this->section('content') ?>

<div class="detail-header">
    <a href="/options" class="back-link">← Retour aux options</a>
</div>

<div class="container">
    <div class="detail-layout">
        <!-- Colonne principale -->
        <div class="detail-main">
            <div class="detail-card">
                <div class="detail-top">
                    <div class="detail-title">
                        <?= esc($option['nom'] ?? 'Option') ?>
                    </div>
                    <?php if ($abonnementActif && $abonnementActif['option_id'] == $option['id']): ?>
                        <div class="detail-badge">Votre abonnement actuel</div>
                    <?php endif; ?>
                </div>

                <div class="detail-price">
                    <span class="price-amount"><?= number_format($prixOption, 2, ',', ' ') ?></span>
                    <span class="price-currency">€</span>
                    <span class="price-period">/mois</span>
                </div>

                <?php if (isset($option['description']) && $option['description']): ?>
                    <div class="detail-description">
                        <?= esc($option['description']) ?>
                    </div>
                <?php endif; ?>

                <!-- Fonctionnalités incluses -->
                <div class="features-section">
                    <h3 class="features-title">✓ Inclus dans cette option</h3>
                    <div class="features-list">
                        <?php if (isset($option['features']) && is_array($option['features'])): ?>
                            <?php foreach ($option['features'] as $feature): ?>
                                <div class="feature-row">
                                    <span class="feature-check">✓</span>
                                    <span class="feature-text"><?= esc($feature) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="feature-row">
                                <span class="feature-check">✓</span>
                                <span class="feature-text">Accès complet aux fonctionnalités premium</span>
                            </div>
                            <div class="feature-row">
                                <span class="feature-check">✓</span>
                                <span class="feature-text">Support client prioritaire</span>
                            </div>
                            <div class="feature-row">
                                <span class="feature-check">✓</span>
                                <span class="feature-text">Mise à jour régulière des contenus</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action d'achat -->
                <div class="detail-actions">
                    <?php if ($abonnementActif && $abonnementActif['option_id'] == $option['id']): ?>
                        <div class="action-info">
                            <span class="info-icon">ℹ</span>
                            <span>Vous êtes déjà abonné à cette option</span>
                        </div>
                    <?php elseif ($soldeInsuffisant): ?>
                        <div class="action-error">
                            <span class="error-icon">⚠</span>
                            <span>
                                Solde insuffisant. Vous avez <strong><?= number_format($soldeActuel, 2, ',', ' ') ?>€</strong> 
                                et cette option coûte <strong><?= number_format($prixOption, 2, ',', ' ') ?>€</strong>.
                            </span>
                        </div>
                        <a href="/porte-monnaie" class="btn-add-funds">
                            + Ajouter des fonds
                        </a>
                    <?php else: ?>
                        <form method="post" action="<?= site_url('option/acheter') ?>" class="purchase-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="option_id" value="<?= $option['id'] ?? '' ?>">
                            <button type="submit" class="btn-buy">
                                Acheter cette option
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Barre latérale -->
        <aside class="detail-sidebar">
            <!-- Résumé du compte -->
            <div class="sidebar-card">
                <div class="sidebar-title">📊 Votre compte</div>
                <div class="sidebar-content">
                    <div class="info-row">
                        <span class="info-label">Utilisateur</span>
                        <span class="info-value"><?= esc($utilisateur['nom'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= esc($utilisateur['email'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>

            <!-- Abonnement actuel -->
            <?php if ($abonnementActif): ?>
                <div class="sidebar-card sidebar-active">
                    <div class="sidebar-title">✓ Abonnement actif</div>
                    <div class="sidebar-content">
                        <div class="info-row">
                            <span class="info-label">Offre</span>
                            <span class="info-value"><?= esc($abonnementActif['nom'] ?? 'N/A') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Prix</span>
                            <span class="info-value"><?= number_format((float)($abonnementActif['prix'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                        <?php if (isset($abonnementActif['date_fin']) && $abonnementActif['date_fin']): ?>
                            <div class="info-row">
                                <span class="info-label">Valide jusqu'au</span>
                                <span class="info-value"><?= date('d/m/Y', strtotime($abonnementActif['date_fin'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="sidebar-card sidebar-empty">
                    <div class="sidebar-title">🎯 Aucun abonnement</div>
                    <div class="sidebar-content">
                        <p style="margin: 0; font-size: 12px; color: var(--muted);">
                            Vous n'avez pas d'abonnement actif. Choisissez une option ci-contre pour commencer.
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- FAQ rapide -->
            <div class="sidebar-card">
                <div class="sidebar-title">❓ Questions fréquentes</div>
                <div class="sidebar-content">
                    <div class="faq-item">
                        <div class="faq-q">Puis-je changer d'offre ?</div>
                        <div class="faq-a">Oui, vous pouvez changer d'offre à tout moment.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-q">Y a-t-il un engagement ?</div>
                        <div class="faq-a">Non, vous pouvez vous désabonner quand vous le souhaitez.</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<style>
.detail-header {
    margin-bottom: 24px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--accent2);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.back-link:hover {
    gap: 12px;
}

.detail-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
}

.detail-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 32px;
}

.detail-top {
    margin-bottom: 24px;
}

.detail-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--text);
}

.detail-badge {
    display: inline-block;
    background: rgba(34, 197, 94, 0.15);
    color: var(--green);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.detail-price {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border);
}

.price-amount {
    font-size: 36px;
    font-weight: 700;
    color: var(--accent2);
}

.price-currency {
    font-size: 20px;
    color: var(--muted);
}

.price-period {
    font-size: 14px;
    color: var(--muted);
    margin-left: 8px;
}

.detail-description {
    font-size: 15px;
    line-height: 1.6;
    color: var(--muted);
    margin-bottom: 32px;
    padding: 16px;
    background: var(--bg3);
    border-radius: 8px;
}

.features-section {
    margin-bottom: 32px;
}

.features-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--text);
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.feature-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.feature-check {
    color: var(--green);
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 2px;
}

.feature-text {
    font-size: 14px;
    color: var(--text);
    line-height: 1.4;
}

.detail-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.purchase-form {
    width: 100%;
}

.btn-buy {
    width: 100%;
    padding: 16px;
    background: var(--accent2);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-buy:hover {
    background: rgba(124, 110, 245, 0.9);
    transform: scale(1.02);
}

.action-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(34, 197, 94, 0.1);
    border-radius: 8px;
    color: var(--green);
    font-size: 14px;
}

.action-error {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 8px;
    color: var(--red);
    font-size: 14px;
    margin-bottom: 12px;
    border-left: 3px solid var(--red);
}

.error-icon {
    font-size: 16px;
    font-weight: 700;
}

.info-icon {
    font-size: 16px;
}

.btn-add-funds {
    display: block;
    width: 100%;
    padding: 16px;
    background: var(--green);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s;
}

.btn-add-funds:hover {
    background: rgba(34, 197, 94, 0.9);
    transform: scale(1.02);
}

/* Sidebar */
.detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
}

.sidebar-active {
    border-color: rgba(34, 197, 94, 0.2);
    background: rgba(34, 197, 94, 0.05);
}

.sidebar-empty {
    border-color: var(--border);
    background: var(--bg3);
}

.sidebar-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text);
}

.sidebar-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
}

.info-label {
    color: var(--muted);
}

.info-value {
    font-weight: 500;
    color: var(--text);
    word-break: break-word;
    text-align: right;
    max-width: 150px;
}

.faq-item {
    margin-bottom: 12px;
}

.faq-q {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 4px;
}

.faq-a {
    font-size: 11px;
    color: var(--muted);
    line-height: 1.4;
}

@media (max-width: 1024px) {
    .detail-layout {
        grid-template-columns: 1fr;
    }

    .detail-card {
        padding: 24px;
    }

    .detail-title {
        font-size: 24px;
    }
}

@media (max-width: 768px) {
    .detail-layout {
        gap: 24px;
    }

    .detail-card {
        padding: 16px;
    }

    .detail-price {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<?= $this->endSection() ?>
