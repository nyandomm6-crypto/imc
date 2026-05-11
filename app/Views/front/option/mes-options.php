<?= $this->extend('layouts/main') ?>

<?php
/** @var array<int, array<string, mixed>>|null $options */
/** @var array<string, mixed>|null $utilisateur */

$options = is_array($options ?? null) ? $options : [];
$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];

$pageTitle = 'Mes options';
$pageSubtitle = 'Gérez vos abonnements actifs';
$pageIcon = '📦';
?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="header-content">
        <h1><?= esc($pageTitle) ?></h1>
        <p><?= esc($pageSubtitle) ?></p>
    </div>
    <a href="/options" class="header-btn">+ Acheter une nouvelle option</a>
</div>

<div class="container">
    <!-- Options actives -->
    <div class="options-section">
        <h2 class="section-title">🟢 Options actives</h2>
        
        <?php $activeOptions = array_filter($options, fn($opt) => 
            !isset($opt['date_fin']) || strtotime($opt['date_fin']) > time()
        ); ?>

        <?php if (empty($activeOptions)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div class="empty-title">Aucune option active</div>
                <div class="empty-text">Vous ne disposez d'aucune option active pour le moment.</div>
                <a href="/options" class="btn-primary" style="margin-top: 12px; display: inline-block;">
                    Découvrir les offres →
                </a>
            </div>
        <?php else: ?>
            <div class="options-table">
                <div class="table-header">
                    <div class="table-col col-name">Nom</div>
                    <div class="table-col col-price">Prix</div>
                    <div class="table-col col-start">Date de début</div>
                    <div class="table-col col-end">Date d'expiration</div>
                    <div class="table-col col-status">Statut</div>
                </div>
                
                <?php foreach ($activeOptions as $option): ?>
                    <div class="table-row">
                        <div class="table-col col-name">
                            <div class="option-name"><?= esc($option['nom'] ?? 'Option') ?></div>
                        </div>
                        <div class="table-col col-price">
                            <span class="price"><?= number_format((float)($option['prix'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="table-col col-start">
                            <?= isset($option['date_debut']) ? date('d/m/Y', strtotime($option['date_debut'])) : '—' ?>
                        </div>
                        <div class="table-col col-end">
                            <?= isset($option['date_fin']) && $option['date_fin'] ? date('d/m/Y', strtotime($option['date_fin'])) : 'Illimité' ?>
                        </div>
                        <div class="table-col col-status">
                            <span class="badge badge-active">Actif</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Options expirées -->
    <?php $expiredOptions = array_filter($options, fn($opt) => 
        isset($opt['date_fin']) && strtotime($opt['date_fin']) <= time()
    ); ?>

    <?php if (!empty($expiredOptions)): ?>
        <div class="options-section">
            <h2 class="section-title">⚫ Options expirées</h2>
            
            <div class="options-table">
                <div class="table-header">
                    <div class="table-col col-name">Nom</div>
                    <div class="table-col col-price">Prix</div>
                    <div class="table-col col-start">Date de début</div>
                    <div class="table-col col-end">Date d'expiration</div>
                    <div class="table-col col-status">Statut</div>
                </div>
                
                <?php foreach ($expiredOptions as $option): ?>
                    <div class="table-row expired">
                        <div class="table-col col-name">
                            <div class="option-name"><?= esc($option['nom'] ?? 'Option') ?></div>
                        </div>
                        <div class="table-col col-price">
                            <span class="price"><?= number_format((float)($option['prix'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="table-col col-start">
                            <?= isset($option['date_debut']) ? date('d/m/Y', strtotime($option['date_debut'])) : '—' ?>
                        </div>
                        <div class="table-col col-end">
                            <?= isset($option['date_fin']) && $option['date_fin'] ? date('d/m/Y', strtotime($option['date_fin'])) : '—' ?>
                        </div>
                        <div class="table-col col-status">
                            <span class="badge badge-expired">Expiré</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, var(--accent2) 0%, rgba(124, 110, 245, 0.1) 100%);
    padding: 32px 24px;
    border-radius: 12px;
    margin-bottom: 32px;
}

.header-content h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--text);
}

.header-content p {
    font-size: 14px;
    color: var(--muted);
    margin: 0;
}

.header-btn {
    background: var(--accent2);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
}

.header-btn:hover {
    background: rgba(124, 110, 245, 0.9);
    transform: scale(1.02);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--text);
}

.options-section {
    margin-bottom: 32px;
}

.options-table {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 1fr 120px 140px 140px 100px;
    gap: 16px;
    padding: 16px;
    background: var(--bg3);
    border-bottom: 1px solid var(--border);
    font-weight: 600;
    font-size: 12px;
    color: var(--muted);
    text-transform: uppercase;
}

.table-row {
    display: grid;
    grid-template-columns: 1fr 120px 140px 140px 100px;
    gap: 16px;
    padding: 16px;
    border-bottom: 1px solid var(--border);
    align-items: center;
    transition: background 0.2s;
}

.table-row:hover {
    background: var(--bg3);
}

.table-row.expired {
    opacity: 0.6;
}

.table-col {
    overflow: hidden;
    text-overflow: ellipsis;
}

.col-name { text-align: left; }
.col-price { text-align: center; }
.col-start { text-align: center; }
.col-end { text-align: center; }
.col-status { text-align: center; }

.option-name {
    font-weight: 600;
    color: var(--text);
    font-size: 14px;
}

.price {
    font-weight: 600;
    color: var(--accent2);
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.badge-active {
    background: rgba(34, 197, 94, 0.15);
    color: var(--green);
}

.badge-expired {
    background: rgba(107, 114, 128, 0.15);
    color: var(--muted);
}

.empty-state {
    text-align: center;
    padding: 60px 24px;
    background: var(--bg2);
    border: 1px dashed var(--border);
    border-radius: 12px;
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
    margin-bottom: 12px;
}

.btn-primary {
    background: var(--accent2);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: rgba(124, 110, 245, 0.9);
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .header-content h1 {
        font-size: 22px;
    }

    .table-header,
    .table-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .col-name {
        grid-column: 1 / -1;
    }
}
</style>

<?= $this->endSection() ?>
