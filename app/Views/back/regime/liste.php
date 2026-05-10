<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des régimes</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            background: #2ecc71;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn:hover {
            background: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .actions a, .actions button {
            margin: 0 5px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
        }

        .edit {
            color: #3498db;
        }

        .delete {
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <h1>🥗 Liste des régimes</h1>

        <!-- IMPORTANT : route resource -->
        <a href="<?= base_url('admin/regimes/new') ?>" class="btn">
            ➕ Ajouter
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Nombre d'aliments</th>
                <th>Prix de base</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($regimes)): ?>
                <?php foreach ($regimes as $r): ?>
                    <tr>
                        <td><?= esc($r['libelle']) ?></td>
                        <td><?= $r['nb_aliments'] ?></td>
                        <td><?= number_format($r['prix_base'], 0, ',', ' ') ?> Ar</td>
                        <td class="actions">

                            <!-- EDIT -->
                            <a href="<?= base_url('admin/regimes/'.$r['id'].'/edit') ?>" class="edit">
                                ✏️
                            </a>

                            <!-- DELETE (méthode DELETE) -->
                            <form action="<?= base_url('admin/regimes/'.$r['id']) ?>" method="post" style="display:inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="delete">🗑️</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun régime trouvé</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>