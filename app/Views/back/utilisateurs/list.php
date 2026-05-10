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
    --accent2:  #a898ff;
    --green:    #2dd4a0;
    --amber:    #f5a623;
    --red:      #f56060;
}

.page { padding: 24px 28px; display: flex; flex-direction: column; gap: 22px; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }

.page-title { font-size: 20px; font-weight: 700; letter-spacing: -0.5px; color: var(--text); }

.page-sub { font-size: 12px; color: var(--muted); margin-top: 3px; }

.btn { padding: 8px 14px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; font-size: 12px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }

.btn-primary { background: var(--accent); color: white; }
.btn-primary:hover { background: var(--accent2); }

.btn-secondary { background: transparent; color: var(--accent); border-color: var(--accent); }
.btn-secondary:hover { background: rgba(124,110,245,0.1); }

.btn-danger { background: var(--red); color: white; }
.btn-danger:hover { opacity: 0.8; }

.btn-sm { padding: 4px 10px; font-size: 11px; }

.card { background: var(--bg2); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }

.card-head { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }

.card-title { font-size: 13px; font-weight: 600; color: var(--text); }

.card-body { padding: 18px; }

.tbl { width: 100%; border-collapse: collapse; font-size: 12px; }

.tbl thead tr { border-bottom: 1px solid var(--border2); }

.tbl th { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); padding: 0 0 10px; text-align: left; }

.tbl td { padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }

.tbl tr:last-child td { border-bottom: none; }

.tbl tbody tr:hover { background: rgba(255,255,255,0.02); }

.action-btns { display: flex; gap: 6px; }

.badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 500; padding: 3px 9px; border-radius: 20px; }

.badge-green { background: rgba(45,212,160,0.1); color: var(--green); }

.badge-red { background: rgba(245,96,96,0.1); color: var(--red); }

.badge-amber { background: rgba(245,166,35,0.1); color: var(--amber); }

.alert { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; }

.alert-success { background: rgba(45,212,160,0.1); border: 1px solid var(--green); color: var(--green); }

.alert-error { background: rgba(245,96,96,0.1); border: 1px solid var(--red); color: var(--red); }

.user-pill { display: flex; align-items: center; gap: 8px; }

.user-av { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), #4f46e5); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0; }
</style>

<div class="page">

    <div class="page-header">
        <div>
            <div class="page-title">👥 Utilisateurs</div>
            <div class="page-sub">Gestion des utilisateurs inscrits</div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-head">
            <span class="card-title">Liste des utilisateurs</span>
            <span style="font-size:11px;color:var(--muted);"><?= count($users) ?> utilisateurs</span>
        </div>
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Genre</th>
                            <th>Rôle</th>
                            <th>Date inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                                $nom = $user['nom'] ?? 'Inconnu';
                                $parts = explode(' ', trim($nom));
                                $av = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                            ?>
                            <tr>
                                <td style="color:var(--muted);font-weight:600;width:28px"><?= $user['id'] ?></td>
                                <td>
                                    <div class="user-pill">
                                        <div class="user-av"><?= $av ?></div>
                                        <span><?= esc($nom) ?></span>
                                    </div>
                                </td>
                                <td style="color:var(--muted2)"><?= esc($user['email']) ?></td>
                                <td><span class="badge badge-green">● <?= esc($user['genre'] ?? 'N/A') ?></span></td>
                                <td><span class="badge badge-amber">● <?= esc($user['role'] ?? 'Utilisateur') ?></span></td>
                                <td style="font-size:11px;color:var(--muted)"><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="/admin/utilisateurs/<?= $user['id'] ?>" class="btn btn-secondary btn-sm">Voir</a>
                                        <form method="POST" action="/admin/utilisateurs/delete/<?= $user['id'] ?>" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align:center;padding:40px;color:var(--muted);font-size:12px">
                    Aucun utilisateur trouvé.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
