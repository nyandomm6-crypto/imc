<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
// ── Valeurs par défaut (évite Undefined variable)
$total_users        = $total_users        ?? 0;
$total_regimes      = $total_regimes      ?? 0;
$total_codes_actifs = $total_codes_actifs ?? 0;
$chart_labels       = $chart_labels       ?? [];
$chart_data         = $chart_data         ?? [];
$objectif_labels    = $objectif_labels    ?? [];
$objectif_data      = $objectif_data      ?? [];
$top_users          = is_array($top_users     ?? null) ? $top_users     : [];
$codes_recents      = is_array($codes_recents ?? null) ? $codes_recents : [];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

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
    --accent2:  #a898ff;
    --green:    #2dd4a0;
    --green-bg: rgba(45,212,160,0.1);
    --amber:    #f5a623;
    --amber-bg: rgba(245,166,35,0.1);
    --red:      #f56060;
    --red-bg:   rgba(245,96,96,0.1);
    --blue:     #5ba3f5;
    --blue-bg:  rgba(91,163,245,0.1);
    --radius:   14px;
    --radius-sm:8px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: var(--bg);
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    color: var(--text);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ── PAGE ── */
.page { padding: 24px 28px; display: flex; flex-direction: column; gap: 22px; }

.page-header { display: flex; align-items: center; justify-content: space-between; }

.page-title {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: var(--text);
}
.page-sub { font-size: 12px; color: var(--muted); margin-top: 3px; }

.header-date {
    font-size: 11px;
    color: var(--muted);
    background: var(--bg2);
    border: 1px solid var(--border);
    padding: 6px 14px;
    border-radius: 20px;
    font-family: 'JetBrains Mono', monospace;
}

/* ── MÉTRIQUES ── */
.metrics { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 14px; }

.metric {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
    cursor: default;
}
.metric:hover { border-color: var(--border2); transform: translateY(-1px); }

.metric::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    border-radius: var(--radius) var(--radius) 0 0;
}
.m-blue::after   { background: var(--blue); }
.m-green::after  { background: var(--green); }
.m-amber::after  { background: var(--amber); }

.metric-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.metric-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
}
.m-blue  .metric-icon { background: var(--blue-bg); }
.m-green .metric-icon { background: var(--green-bg); }
.m-amber .metric-icon { background: var(--amber-bg); }

.metric-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
}

.metric-val {
    font-size: 40px;
    font-weight: 700;
    letter-spacing: -1.5px;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.m-blue  .metric-val { color: var(--blue); }
.m-green .metric-val { color: var(--green); }
.m-amber .metric-val { color: var(--amber); }

.metric-footer {
    margin-top: 8px;
    font-size: 11px;
    color: var(--muted);
}

/* ── CARDS ── */
.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: border-color 0.2s;
}
.card:hover { border-color: var(--border2); }

.card-head {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: -0.2px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ct-icon {
    width: 24px; height: 24px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.card-badge {
    font-size: 10px;
    font-weight: 500;
    padding: 2px 9px;
    border-radius: 20px;
    background: var(--bg3);
    color: var(--muted2);
    border: 1px solid var(--border);
}

.card-body { padding: 18px; }

/* ── CHARTS GRID ── */
.charts-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 18px; }

.chart-wrap {
    position: relative;
    background: var(--bg3);
    border-radius: 10px;
    padding: 16px;
}

/* ── TABLES ── */
.tables-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 18px; }

.tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.tbl thead tr {
    border-bottom: 1px solid var(--border2);
}

.tbl th {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
    padding: 0 0 10px;
    text-align: left;
}

.tbl td {
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}

.tbl tr:last-child td { border-bottom: none; }

.tbl tbody tr { transition: background 0.15s; }
.tbl tbody tr:hover td { background: rgba(255,255,255,0.02); }

/* ── BADGE STATUS ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 500;
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
}
.badge-green  { background: var(--green-bg);  color: var(--green); }
.badge-blue   { background: var(--blue-bg);   color: var(--blue); }
.badge-red    { background: var(--red-bg);    color: var(--red); }
.badge-muted  { background: var(--bg3);       color: var(--muted); border: 1px solid var(--border); }

/* ── SOLDE ── */
.solde-val {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    color: var(--green);
}

/* ── USER PILL ── */
.user-pill {
    display: flex;
    align-items: center;
    gap: 8px;
}
.user-av {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #4f46e5);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    letter-spacing: 0.3px;
}

/* ── CODE ── */
.code-mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    font-weight: 500;
    color: var(--accent2);
    background: rgba(124,110,245,0.08);
    padding: 2px 8px;
    border-radius: 5px;
}

/* ── ANIMATIONS ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.metrics .metric     { animation: fadeUp 0.35s ease both; }
.metric:nth-child(1) { animation-delay: 0.04s; }
.metric:nth-child(2) { animation-delay: 0.09s; }
.metric:nth-child(3) { animation-delay: 0.14s; }
.charts-grid, .tables-grid { animation: fadeUp 0.4s ease 0.2s both; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--bg3); border-radius: 3px; }
</style>

<div class="page">

    <!-- EN-TÊTE -->
    <div class="page-header">
        <div>
            <div class="page-title">Dashboard Admin</div>
            <div class="page-sub">Vue d'ensemble de la plateforme</div>
        </div>
        <div class="header-date"><?= date('d/m/Y — H:i') ?></div>
    </div>

    <!-- ── MÉTRIQUES ── -->
    <div class="metrics">

        <div class="metric m-blue">
            <div class="metric-top">
                <div class="metric-icon">👥</div>
                <span class="metric-label">Utilisateurs</span>
            </div>
            <div class="metric-val"><?= (int) $total_users ?></div>
            <div class="metric-footer">Membres inscrits</div>
        </div>

        <div class="metric m-green">
            <div class="metric-top">
                <div class="metric-icon">🥗</div>
                <span class="metric-label">Régimes</span>
            </div>
            <div class="metric-val"><?= (int) $total_regimes ?></div>
            <div class="metric-footer">Plans nutritionnels</div>
        </div>

        <div class="metric m-amber">
            <div class="metric-top">
                <div class="metric-icon">🔑</div>
                <span class="metric-label">Codes actifs</span>
            </div>
            <div class="metric-val"><?= (int) $total_codes_actifs ?></div>
            <div class="metric-footer">Codes utilisables</div>
        </div>

    </div>

    <!-- ── GRAPHES ── -->
    <div class="charts-grid">

        <!-- Ligne : Inscriptions -->
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--blue-bg)">📈</span>
                    Inscriptions par mois
                </span>
                <span class="card-badge"><?= count($chart_labels) ?> mois</span>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="inscriptionsChart" height="220"></canvas>
                </div>
            </div>
        </div>

        <!-- Camembert : Objectifs -->
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--amber-bg)">🎯</span>
                    Répartition des objectifs
                </span>
                <span class="card-badge"><?= array_sum($objectif_data) ?> total</span>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="objectifsChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- ── TABLEAUX ── -->
    <div class="tables-grid">

        <!-- Top 5 utilisateurs -->
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--green-bg)">💰</span>
                    Top 5 — Solde porte-monnaie
                </span>
            </div>
            <div class="card-body">
                <?php if (!empty($top_users)): ?>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilisateur</th>
                            <th>Solde</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_users as $i => $u): ?>
                        <?php
                            $nom = $u['nom'] ?? 'Inconnu';
                            $parts = explode(' ', trim($nom));
                            $av = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                            $statut = $u['status'] ?? 'active';
                        ?>
                        <tr>
                            <td style="color:var(--muted);font-weight:600;width:28px"><?= $i + 1 ?></td>
                            <td>
                                <div class="user-pill">
                                    <div class="user-av"><?= esc($av) ?></div>
                                    <span><?= esc($nom) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="solde-val"><?= number_format((float)($u['solde'] ?? 0), 2) ?> €</span>
                            </td>
                            <td>
                                <?php if ($statut === 'active'): ?>
                                    <span class="badge badge-green">● Actif</span>
                                <?php elseif ($statut === 'suspended'): ?>
                                    <span class="badge badge-red">● Suspendu</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">● Inactif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
                        Aucun utilisateur trouvé.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Codes récents -->
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🔑</span>
                    Codes promo récemment utilisés
                </span>
                <span class="card-badge"><?= count($codes_recents) ?> codes</span>
            </div>
            <div class="card-body">
                <?php if (!empty($codes_recents)): ?>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Utilisateur</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codes_recents as $c): ?>
                        <tr>
                            <td>
                                <span class="code-mono"><?= esc($c['code'] ?? '') ?></span>
                            </td>
                            <td style="color:var(--muted2)"><?= esc($c['nom'] ?? '—') ?></td>
                            <td style="color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:11px">
                                <?php
                                    $d = $c['date_utilisation'] ?? null;
                                    echo $d ? date('d/m/Y H:i', strtotime($d)) : '—';
                                ?>
                            </td>
                            <td>
                                <?php $st = $c['status'] ?? ''; ?>
                                <?php if ($st === 'active'): ?>
                                    <span class="badge badge-green">● Actif</span>
                                <?php elseif ($st === 'used'): ?>
                                    <span class="badge badge-blue">● Utilisé</span>
                                <?php else: ?>
                                    <span class="badge badge-red">● Expiré</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
                        Aucun code utilisé récemment.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div><!-- /page -->

<!-- ── CHART.JS ── -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
Chart.defaults.color          = '#6b6b7e';
Chart.defaults.borderColor    = 'rgba(255,255,255,0.07)';
Chart.defaults.font.family    = 'Inter, sans-serif';
Chart.defaults.font.size      = 12;

/* ── LINE CHART ── */
new Chart(document.getElementById('inscriptionsChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_values($chart_labels)) ?>,
        datasets: [{
            label: 'Inscriptions',
            data:  <?= json_encode(array_values($chart_data)) ?>,
            borderColor:     '#7c6ef5',
            backgroundColor: 'rgba(124,110,245,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#7c6ef5',
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#18181f',
                borderColor:     'rgba(255,255,255,0.1)',
                borderWidth: 1,
                titleColor: '#f0f0f5',
                bodyColor:  '#9090a0',
                padding: 10,
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#6b6b7e' }
            },
            y: {
                grid:  { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#6b6b7e', stepSize: 1 },
                beginAtZero: true
            }
        }
    }
});

/* ── PIE CHART ── */
new Chart(document.getElementById('objectifsChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_values($objectif_labels)) ?>,
        datasets: [{
            data:            <?= json_encode(array_values($objectif_data)) ?>,
            backgroundColor: ['#7c6ef5','#2dd4a0','#f5a623','#5ba3f5','#f56060'],
            borderColor:     '#18181f',
            borderWidth: 3,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#9090a0',
                    padding: 16,
                    usePointStyle: true,
                    pointStyleWidth: 8,
                    font: { size: 11 }
                }
            },
            tooltip: {
                backgroundColor: '#18181f',
                borderColor:     'rgba(255,255,255,0.1)',
                borderWidth: 1,
                titleColor: '#f0f0f5',
                bodyColor:  '#9090a0',
                padding: 10,
            }
        }
    }
});
</script>

<?= $this->endSection() ?>