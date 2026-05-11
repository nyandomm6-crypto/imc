<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var float|int|null $soldeCompte */
/** @var array<int, array<string, mixed>>|null $transactions */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$transactions = is_array($transactions ?? null) ? $transactions : [];
$nom = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$solde = number_format((float) ($soldeCompte ?? 0), 2);

$pageTitle = 'Porte-monnaie';
$pageSubtitle = 'Historique complet des transactions';
$activeNav = 'porte-monnaie';
?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-head">
        <span class="card-title">
            <span class="ct-icon" style="background:var(--amber-bg)">💰</span>
            Porte-monnaie
        </span>
        <div style="display:flex;gap:8px">
            <a href="<?= site_url('export/transactions') ?>" class="btn btn-secondary btn-sm" target="_blank" style="font-size:11px;padding:6px 12px">
                📄 Exporter PDF
            </a>
            <span style="font-size:11px;color:var(--muted)">Solde actuel</span>
        </div>
    </div>
    <div class="card-body">
        <div class="wallet-grid">
            <div>
                <div style="font-size:11px;color:var(--muted);margin-bottom:2px">Solde disponible</div>
                <div class="wallet-solde"><?= $solde ?> €</div>
                <div style="font-size:11px;color:var(--muted)">Utilisateur: <?= esc($nom) ?></div>
            </div>
            <div>
                <div style="font-size:11px;color:var(--muted);margin-bottom:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.06em">
                    Toutes les transactions
                </div>
                <div id="tx-list">
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $tx): ?>
                            <?php
                                $txType = $tx['type'] ?? '';
                                $txClass = $txType === 'income' ? 'tx-plus' : 'tx-minus';
                                $txSign = $txType === 'income' ? '+' : '-';
                                $txDesc = $tx['description'] ?? 'Transaction';
                                $txAmount = number_format((float) ($tx['montant'] ?? 0), 2);
                                $txDate = $tx['date_transaction'] ?? '';
                                $txDateLabel = $txDate ? date('d/m/Y', strtotime($txDate)) : '';
                            ?>
                            <div class="tx-item" style="font-size:12px">
                                <span style="color:var(--text);flex:1">
                                    <?= esc($asString($txDesc, 'Transaction')) ?>
                                </span>
                                <?php if ($txDateLabel !== ''): ?>
                                    <span style="color:var(--muted);font-size:11px">
                                        <?= esc($txDateLabel) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="tx-amount <?= $txClass ?>">
                                    <?= $txSign ?><?= $txAmount ?> €
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="tx-item" style="color:var(--muted);font-size:12px">
                            Aucune transaction.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
