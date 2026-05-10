<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
:root {
    --bg:       #0a0a0f;
    --bg2:      #111118;
    --bg3:      #18181f;
    --border:   rgba(255,255,255,0.07);
    --border2:  rgba(255,255,255,0.13);
    --text:     #f0f0f5;
    --muted:    #6b6b7e;
    --muted2:   #9090a0;
    --accent:   #7c6ef5;
    --green:    #2dd4a0;
    --amber:    #f5a623;
    --red:      #f56060;
}

.page { padding: 24px 28px; display: flex; flex-direction: column; gap: 22px; }

.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--muted); margin-bottom: 10px; }

.breadcrumb a { color: var(--accent); text-decoration: none; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }

.page-title { font-size: 20px; font-weight: 700; letter-spacing: -0.5px; color: var(--text); }

.btn { padding: 8px 14px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }

.btn-secondary { background: transparent; color: var(--accent); border-color: var(--accent); }

.card { background: var(--bg2); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 20px; }

.card-head { padding: 14px 18px; border-bottom: 1px solid var(--border); }

.card-title { font-size: 13px; font-weight: 600; color: var(--text); }

.card-body { padding: 18px; }

.stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }

.stat-card { background: var(--bg3); border: 1px solid var(--border); border-radius: 10px; padding: 14px; text-align: center; }

.stat-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }

.stat-val { font-size: 24px; font-weight: 700; color: var(--accent); }

.metric-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); }

.metric-row:last-child { border-bottom: none; }

.metric-label { font-size: 12px; color: var(--muted); }

.metric-val { font-size: 14px; font-weight: 600; color: var(--text); }

.tbl { width: 100%; border-collapse: collapse; font-size: 12px; }

.tbl thead tr { border-bottom: 1px solid var(--border2); }

.tbl th { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); padding: 0 0 10px; text-align: left; }

.tbl td { padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }

.tbl tr:last-child td { border-bottom: none; }

.badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }

.badge-green { background: rgba(45,212,160,0.1); color: var(--green); }

.badge-amber { background: rgba(245,166,35,0.1); color: var(--amber); }

.badge-red { background: rgba(245,96,96,0.1); color: var(--red); }

.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
</style>

<div class="page">

    <div class="breadcrumb">
        <a href="/admin/utilisateurs">👥 Utilisateurs</a>
        <span>/</span>
        <span><?= esc($user['nom'] ?? 'Utilisateur') ?></span>
    </div>

    <div class="page-header">
        <div>
            <div class="page-title"><?= esc($user['nom'] ?? 'Utilisateur') ?></div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Email: <?= esc($user['email'] ?? '—') ?></div>
        </div>
        <a href="/admin/utilisateurs" class="btn btn-secondary">← Retour</a>
    </div>

    <!-- STATS -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Solde porte-monnaie</div>
            <div class="stat-val" style="color:var(--green)"><?= number_format((float)$solde, 2) ?> €</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dernier poids</div>
            <div class="stat-val"><?= $lastMesure['poids_kg'] ?? '—' ?> kg</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Genre</div>
            <div class="stat-val" style="font-size:14px"><?= esc($user['genre'] ?? 'N/A') ?></div>
        </div>
    </div>

    <!-- INFO USER -->
    <div class="grid-2">

        <div class="card">
            <div class="card-head">
                <span class="card-title">Informations personnelles</span>
            </div>
            <div class="card-body">
                <div class="metric-row">
                    <span class="metric-label">Nom complet</span>
                    <span class="metric-val"><?= esc($user['nom'] ?? '—') ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Email</span>
                    <span class="metric-val" style="font-size:11px"><?= esc($user['email'] ?? '—') ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Date naissance</span>
                    <span class="metric-val"><?= date('d/m/Y', strtotime($user['date_naissance'])) ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Rôle</span>
                    <span class="badge badge-amber">● <?= esc($user['role'] ?? 'Utilisateur') ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Inscrit depuis</span>
                    <span class="metric-val"><?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <span class="card-title">Dernière mesure</span>
            </div>
            <div class="card-body">
                <?php if ($lastMesure): ?>
                    <div class="metric-row">
                        <span class="metric-label">Poids</span>
                        <span class="metric-val"><?= number_format((float)$lastMesure['poids_kg'], 1) ?> kg</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">Taille</span>
                        <span class="metric-val"><?= number_format((float)$lastMesure['taille_m'], 2) ?> m</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">Date</span>
                        <span class="metric-val"><?= date('d/m/Y H:i', strtotime($lastMesure['date_mesure'])) ?></span>
                    </div>
                <?php else: ?>
                    <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
                        Aucune mesure enregistrée.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- HISTORIQUE IMC -->
    <div class="card">
        <div class="card-head">
            <span class="card-title">📊 Historique IMC (10 derniers)</span>
        </div>
        <div class="card-body">
            <?php if (!empty($imcHistory)): ?>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>IMC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($imcHistory as $imc): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($imc['date_calcul'])) ?></td>
                                <td><strong><?= number_format((float)$imc['valeur_imc'], 2) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
                    Aucun historique IMC trouvé.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TRANSACTIONS -->
    <div class="card">
        <div class="card-head">
            <span class="card-title">💳 Transactions (10 dernières)</span>
        </div>
        <div class="card-body">
            <?php if (!empty($transactions)): ?>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $trans): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($trans['date_transaction'])) ?></td>
                                <td>
                                    <?php if ($trans['type'] === 'income'): ?>
                                        <span class="badge badge-green">● Revenu</span>
                                    <?php else: ?>
                                        <span class="badge badge-red">● Dépense</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:<?= $trans['type'] === 'income' ? 'var(--green)' : 'var(--red)' ?>;font-weight:600"><?= number_format((float)$trans['montant'], 2) ?> €</td>
                                <td style="color:var(--muted2);font-size:11px"><?= esc($trans['description'] ?? '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
                    Aucune transaction trouvée.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
