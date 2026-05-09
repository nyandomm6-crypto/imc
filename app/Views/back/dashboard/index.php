<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 30px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h2 {
            margin: 0;
            font-size: 18px;
            color: #555;
        }

        .card .value {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
            color: #2c3e50;
        }

        .users { border-left: 5px solid #3498db; }
        .regimes { border-left: 5px solid #2ecc71; }
        .codes { border-left: 5px solid #e67e22; }

        .charts {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        canvas {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        /* TABLE */
        .table-container {
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 12px;
            background: #2ecc71;
            color: white;
        }
    </style>
</head>

<body>

<h1>📊 Dashboard Admin</h1>

<!-- CARDS -->
<div class="container">

    <div class="card users">
        <h2>👥 Utilisateurs</h2>
        <div class="value"><?= $total_users ?></div>
    </div>

    <div class="card regimes">
        <h2>🥗 Régimes</h2>
        <div class="value"><?= $total_regimes ?></div>
    </div>

    <div class="card codes">
        <h2>🔑 Codes actifs</h2>
        <div class="value"><?= $total_codes_actifs ?></div>
    </div>

</div>

<!-- CHARTS -->
<div class="charts">

    <!-- LINE CHART -->
    <div>
        <h3>📈 Inscriptions par mois</h3>
        <canvas id="inscriptionsChart"></canvas>
    </div>

    <!-- PIE CHART -->
    <div>
        <h3>🎯 Répartition des objectifs</h3>
        <canvas id="objectifsChart"></canvas>
    </div>

</div>

<!-- TABLE TOP USERS -->
<div class="table-container">
    <h3>💰 Top 5 utilisateurs par solde</h3>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Solde</th>
                <th>Statut</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($top_users as $u): ?>
                <tr>
                    <td><?= esc($u['nom']) ?></td>
                    <td><?= number_format($u['solde'], 2) ?> €</td>
                    <td><span class="badge">actif</span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="table-container" style="margin-top:50px;">
    <h3>🔑 Codes promo récemment utilisés</h3>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Utilisateur</th>
                <th>Date utilisation</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($codes_recents as $c): ?>
                <tr>
                    <td><strong><?= esc($c['code']) ?></strong></td>
                    <td><?= esc($c['nom']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($c['date_utilisation'])) ?></td>
                    <td>
                        <?php if ($c['status'] === 'active'): ?>
                            🟢 actif
                        <?php elseif ($c['status'] === 'used'): ?>
                            🔵 utilisé
                        <?php else: ?>
                            🔴 expiré
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* =========================
   LINE CHART
========================= */
const ctx = document.getElementById('inscriptionsChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Inscriptions',
            data: <?= json_encode($chart_data) ?>,
            borderWidth: 2,
            fill: false,
            tension: 0.3
        }]
    }
});

/* =========================
   PIE CHART
========================= */
const ctx2 = document.getElementById('objectifsChart');

new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?= json_encode($objectif_labels) ?>,
        datasets: [{
            data: <?= json_encode($objectif_data) ?>
        }]
    }
});
</script>

</body>
</html>