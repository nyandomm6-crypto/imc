<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #7c6ef5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #7c6ef5;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .user-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #7c6ef5;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .income {
            color: #2dd4a0;
            font-weight: bold;
        }

        .expense {
            color: #f56060;
            font-weight: bold;
        }

        .total-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #7c6ef5;
        }

        .total-section h3 {
            margin: 0 0 10px 0;
            color: #7c6ef5;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historique des Transactions</h1>
        <p>Genere le <?= date('d/m/Y a H:i') ?></p>
    </div>

    <div class="user-info">
        <strong>Utilisateur:</strong> <?= esc($utilisateur['nom'] ?? 'Utilisateur') ?>
    </div>

    <?php if (!empty($transactions)): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalIncome = 0;
                $totalExpense = 0;
                foreach ($transactions as $transaction):
                    if ($transaction['type'] === 'income') {
                        $totalIncome += $transaction['montant'];
                    } else {
                        $totalExpense += $transaction['montant'];
                    }
                ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($transaction['date_transaction'])) ?></td>
                    <td>
                        <span class="<?= $transaction['type'] === 'income' ? 'income' : 'expense' ?>">
                            <?= $transaction['type'] === 'income' ? 'CREDIT' : 'DEBIT' ?>
                        </span>
                    </td>
                    <td><?= esc($transaction['description'] ?: 'Transaction') ?></td>
                    <td class="<?= $transaction['type'] === 'income' ? 'income' : 'expense' ?>">
                        <?= $transaction['type'] === 'income' ? '+' : '-' ?><?= number_format($transaction['montant'], 2, ',', ' ') ?> €
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-section">
            <h3>Résumé des transactions</h3>
            <p><strong>Total crédits:</strong> <span class="income">+<?= number_format($totalIncome, 2, ',', ' ') ?> €</span></p>
            <p><strong>Total débits:</strong> <span class="expense">-<?= number_format($totalExpense, 2, ',', ' ') ?> €</span></p>
            <p><strong>Solde net:</strong> <span style="color: <?= ($totalIncome - $totalExpense) >= 0 ? '#2dd4a0' : '#f56060' ?>; font-weight: bold;">
                <?= number_format($totalIncome - $totalExpense, 2, ',', ' ') ?> €
            </span></p>
        </div>
    <?php else: ?>
        <div class="no-data">
            <p>Aucune transaction trouvée pour cette période.</p>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Document généré automatiquement par FitIMC - <?= date('d/m/Y') ?></p>
    </div>
</body>
</html>