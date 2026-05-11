<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<string, mixed>|null $mesure */
/** @var array<int, array<string, mixed>>|null $objectifs */
/** @var array<int, array<string, mixed>>|null $regimes */
/** @var array<int, array<string, mixed>>|null $sports */
/** @var array<int, array<string, mixed>>|null $transactions */
/** @var float|int|null $imc */
/** @var int|float|null $imcProgression */
/** @var float|int|null $soldeCompte */
/** @var string|null $categorieImc */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$mesure = is_array($mesure ?? null) ? $mesure : null;
$objectifs = is_array($objectifs ?? null) ? $objectifs : [];
$regimes = is_array($regimes ?? null) ? $regimes : [];
$sports = is_array($sports ?? null) ? $sports : [];
$transactions = is_array($transactions ?? null) ? $transactions : [];

$nom = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$imc = is_numeric($imc ?? null) ? (float) $imc : null;
$categorieImc = $asString($categorieImc ?? null, 'Inconnue');
$imcVal = $imc !== null ? number_format($imc, 1) : '--';
$progression = is_numeric($imcProgression ?? null) ? (int) $imcProgression : 0;
$poids = $asString($mesure['poids_kg'] ?? null, '--');
$taille = $asString($mesure['taille_m'] ?? null, '--');
$solde = number_format((float) ($soldeCompte ?? 0), 2);
$nbObj = count($objectifs);

$catStyle = match(true) {
    $imc !== null && $imc < 18.5 => ['color' => 'var(--blue)',  'bg' => 'var(--blue-bg)'],
    $imc !== null && $imc < 25   => ['color' => 'var(--green)', 'bg' => 'var(--green-bg)'],
    $imc !== null && $imc < 30   => ['color' => 'var(--amber)', 'bg' => 'var(--amber-bg)'],
    $imc !== null                 => ['color' => 'var(--red)',   'bg' => 'var(--red-bg)'],
    default                       => ['color' => 'var(--muted)', 'bg' => 'var(--bg3)'],
};

$sportIcons = ['🏃','🏊','🚴','🧘','🏋️','⛹️','🤸','🥊'];
$regimeIcons = ['🥗','🐟','🥦','🍗','🫐','🥑'];

$pageTitle = 'Tableau de bord';
$pageSubtitle = 'Bonjour, ' . $nom . ' 👋';
$activeNav = 'dashboard';
?>

<?= $this->section('content') ?>

            <!-- ALERTE COMPTE INACTIF -->
            <?php if (($compteStatut ?? 'inactive') === 'inactive'): ?>
            <div class="alert alert-amber">
                ⚠ Votre compte est inactif — ajoutez un code promo pour l'activer.
            </div>
            <?php endif; ?>

            <!-- MÉTRIQUES -->
            <div class="metrics">
                <div class="metric m-purple">
                    <div class="metric-icon">📊</div>
                    <div class="metric-val"><?= $imcVal ?></div>
                    <div class="metric-label">IMC actuel</div>
                    <span class="metric-badge" style="background:<?= $catStyle['bg'] ?>;color:<?= $catStyle['color'] ?>">
                        <?= esc($categorieImc) ?>
                    </span>
                </div>
                <div class="metric m-green">
                    <div class="metric-icon">⚖️</div>
                    <div class="metric-val"><?= esc($poids) ?></div>
                    <div class="metric-label">Poids (kg) · <?= esc($taille) ?> m</div>
                </div>
                <div class="metric m-amber">
                    <div class="metric-icon">💰</div>
                    <div class="metric-val"><?= $solde ?> <span style="font-size:16px;font-weight:400">€</span></div>
                    <div class="metric-label">Solde porte-monnaie</div>
                </div>
                <div class="metric m-blue">
                    <div class="metric-icon">🎯</div>
                    <div class="metric-val"><?= $nbObj ?><span style="font-size:16px;font-weight:400;color:var(--muted)">/3</span></div>
                    <div class="metric-label">Objectifs actifs</div>
                </div>
            </div>

            <!-- IMC + OBJECTIFS -->
            <div class="row2">

                <!-- Jauge IMC -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:rgba(124,110,245,0.15)">📈</span>
                            Indice de masse corporelle
                        </span>
                        <span style="font-size:11px;color:var(--muted)">Dernière mesure</span>
                    </div>
                    <div class="card-body">
                        <?php if ($imc !== null): ?>
                        <div style="display:flex;align-items:flex-end;gap:12px;margin-bottom:4px">
                            <span class="imc-big" style="color:<?= $catStyle['color'] ?>"><?= $imcVal ?></span>
                            <span class="badge" style="background:<?= $catStyle['bg'] ?>;color:<?= $catStyle['color'] ?>;margin-bottom:8px">
                                <?= esc($categorieImc) ?>
                            </span>
                        </div>
                        <div class="imc-track">
                            <div class="imc-fill"></div>
                            <div class="imc-needle" id="imc-needle" style="left:<?= $progression ?>%"></div>
                        </div>
                        <div class="imc-zones">
                            <span>Maigreur</span>
                            <span>Normal</span>
                            <span>Surpoids</span>
                            <span>Obésité</span>
                        </div>
                        <div class="imc-stats">
                            <div>
                                <div class="imc-stat-label">Poids actuel</div>
                                <div class="imc-stat-val"><?= esc($poids) ?> kg</div>
                            </div>
                            <div>
                                <div class="imc-stat-label">Taille</div>
                                <div class="imc-stat-val"><?= esc($taille) ?> m</div>
                            </div>
                            <div>
                                <div class="imc-stat-label">IMC idéal</div>
                                <div class="imc-stat-val" style="color:var(--green)">22.0</div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div style="text-align:center;padding:24px 0;color:var(--muted)">
                            <div style="font-size:32px;margin-bottom:8px">📏</div>
                            <div style="font-size:13px;margin-bottom:12px">Aucune mesure enregistrée</div>
                            <a href="/profil/mesure" style="color:var(--accent2);font-size:12px;text-decoration:none">
                                + Ajouter ma mesure →
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Objectifs -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:var(--amber-bg)">🎯</span>
                            Mes objectifs
                        </span>
                        <a href="/profil/objectifs" class="card-link">Modifier →</a>
                    </div>
                    <div class="card-body">
                        <?php foreach ($objectifs as $obj): ?>
                        <div class="obj-item">
                            <div class="obj-dot" style="background:var(--green)"></div>
                            <span class="obj-label">
                                <?= esc($asString($obj['libelle'] ?? $obj['objectif_libelle'] ?? null, 'Objectif')) ?>
                            </span>
                            <span class="badge badge-green">Actif</span>
                        </div>
                        <?php endforeach; ?>

                        <?php for ($i = count($objectifs); $i < 3; $i++): ?>
                        <div class="obj-item">
                            <div class="obj-dot" style="background:var(--muted)"></div>
                            <span class="obj-label obj-empty">Emplacement libre</span>
                            <span class="badge badge-muted">Vide</span>
                        </div>
                        <?php endfor; ?>

                        <a href="/profil/objectifs" style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:14px;font-size:11px;color:var(--accent2);text-decoration:none;padding:8px;border:1px dashed rgba(124,110,245,0.3);border-radius:8px;transition:all 0.2s" onmouseover="this.style.background='rgba(124,110,245,0.05)'" onmouseout="this.style.background='transparent'">
                            + Gérer mes objectifs
                        </a>
                    </div>
                </div>

            </div>

            <!-- RÉGIMES + SPORTS -->
            <div class="row3">

                <!-- Régimes -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                            Régimes suggérés
                        </span>
                        <a href="/regimes" class="card-link">Voir tout →</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($regimes)): ?>
                            <?php foreach ($regimes as $i => $regime): ?>
                            <div class="regime-item">
                                <div class="ri-icon"><?= $regimeIcons[$i % count($regimeIcons)] ?></div>
                                <div style="flex:1;min-width:0">
                                    <div class="ri-name">
                                        <?= esc($asString($regime['libelle'] ?? $regime['nom'] ?? null, 'Régime')) ?>
                                    </div>
                                    <div class="ri-sub">
                                        <?php if (isset($regime['duree_jours'])): ?>
                                            <?= esc($asString($regime['duree_jours'])) ?> jours
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (isset($regime['prix'])): ?>
                                <span class="ri-price"><?= number_format((float)$regime['prix'], 2) ?> €</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:20px 0;color:var(--muted);font-size:12px">
                                Aucun régime disponible
                            </div>
                        <?php endif; ?>
                        <a href="/regimes/pdf" class="pdf-btn">
                            📄 Exporter mon plan nutritionnel en PDF
                        </a>
                    </div>
                </div>

                <!-- Sports -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                            Sports recommandés
                        </span>
                        <a href="/sports" class="card-link">Voir tout →</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($sports)): ?>
                            <?php foreach ($sports as $i => $sport): ?>
                            <div class="sport-item">
                                <div class="si-icon"><?= $sportIcons[$i % count($sportIcons)] ?></div>
                                <div style="flex:1">
                                    <div style="font-size:13px;font-weight:500">
                                        <?= esc($asString($sport['nom'] ?? null)) ?>
                                    </div>
                                    <div style="font-size:11px;color:var(--muted)">Activité cardio</div>
                                </div>
                                <span class="si-cal">
                                    <?= esc($asString($sport['calories_par_heure'] ?? null)) ?> kcal/h
                                </span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:20px 0;color:var(--muted);font-size:12px">
                                Aucun sport disponible
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- PORTE-MONNAIE -->
            <div class="card">
                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:var(--amber-bg)">💰</span>
                        Porte-monnaie
                    </span>
                    <a href="/porte-monnaie" class="card-link">Historique complet →</a>
                </div>
                <div class="card-body">
                    <div class="wallet-grid">
                        <div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:2px">Solde disponible</div>
                            <div class="wallet-solde"><?= $solde ?> €</div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:8px">Entrer un code promo</div>
                            <div class="code-wrap">
                                <input type="text" id="codePromotion" placeholder="ex: PROMO10" class="code-input">
                                <button onclick="validerCode()" class="code-btn">Valider</button>
                            </div>
                            <div id="code-msg" style="font-size:11px;margin-top:8px;min-height:16px"></div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.06em">
                                Dernières transactions
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
                                        Aucune transaction récente.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function validerCode() {
    const code = document.getElementById('codePromotion').value.trim();
    const msg  = document.getElementById('code-msg');
    const idUser = <?= (int) ($utilisateur['id'] ?? 0) ?>;

    if (!code || !idUser) {
        msg.textContent = '⚠ Veuillez entrer un code.';
        msg.style.color = 'var(--amber)';
        return;
    }

    msg.textContent = 'Vérification…';
    msg.style.color = 'var(--muted2)';

    fetch('/api/code-promo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id_user: idUser,
            code: code,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            msg.textContent = '✓ ' + data.message;
            msg.style.color = 'var(--green)';
            // Mettre à jour le solde affiché
            document.querySelectorAll('.wallet-solde, .metric.m-amber .metric-val').forEach(el => {
                el.textContent = parseFloat(data.nouveau_solde).toFixed(2) + ' €';
            });
            // Ajouter la transaction dans la liste
            const txList = document.getElementById('tx-list');
            const item = document.createElement('div');
            item.className = 'tx-item';
            const now = new Date();
            const dateLabel = now.toLocaleDateString('fr-FR');
            item.innerHTML = `<span style="font-size:12px;color:var(--text);flex:1">Code ${code}</span><span style="color:var(--muted);font-size:11px">${dateLabel}</span><span class="tx-amount tx-plus">+${parseFloat(data.montant ?? 0).toFixed(2)} €</span>`;
            txList.prepend(item);
        } else {
            msg.textContent = '✗ ' + data.message;
            msg.style.color = 'var(--red)';
        }
        document.getElementById('codePromotion').value = '';
    })
    .catch(() => {
        msg.textContent = '✗ Erreur réseau, réessayez.';
        msg.style.color = 'var(--red)';
    });
}

// Animation entrée aiguille IMC
window.addEventListener('load', () => {
    const needle = document.getElementById('imc-needle');
    if (needle) {
        needle.style.left = '0%';
        setTimeout(() => { needle.style.left = '<?= $progression ?>%'; }, 300);
    }
});
</script>
<?= $this->endSection() ?>