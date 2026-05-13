<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- ALERT INFO -->
    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-blue" style="margin:15px 0;">
            ℹ️ <?= esc(session()->getFlashdata('info')) ?>
        </div>
    <?php endif; ?>


    <!-- METRICS -->
    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🎯</span>
                Mes objectifs
            </span>
        </div>

        <div class="card-body">
            <div class="metrics">
                <div class="metric m-blue">
                    <div class="metric-icon">🎯</div>
                    <div class="metric-val"><?= count($objectifsUtilisateur) ?></div>
                    <div class="metric-label">Objectifs actifs</div>
                </div>

                <div class="metric m-green">
                    <div class="metric-icon">✅</div>
                    <div class="metric-val"><?= count(array_filter($objectifsUtilisateur, fn($obj) => $obj['statut'] !== 'en_cours')) ?></div>
                    <div class="metric-label">Terminés</div>
                </div>

                <div class="metric m-amber">
                    <div class="metric-icon">⚠️</div>
                    <div class="metric-val"><?= count(array_filter($objectifsUtilisateur, fn($obj) => $obj['statut'] === 'en_cours')) ?></div>
                    <div class="metric-label">En cours</div>
                </div>

                <div class="metric m-purple">
                    <div class="metric-icon">📝</div>
                    <div class="metric-val"><?= count($tousObjectifs) ?></div>
                    <div class="metric-label">Disponibles</div>
                </div>
            </div>
        </div>
    </div>


    <!-- OBJECTIFS LIST -->
    <div class="row2">

        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--blue-bg)">📋</span>
                    Objectifs définis
                </span>
            </div>

            <div class="card-body">

                <?php if (!empty($objectifsUtilisateur)): ?>
                    <?php foreach ($objectifsUtilisateur as $objectif): ?>
                        <div style="display:flex;justify-content:space-between;padding:14px;border-bottom:1px solid rgba(0,0,0,0.05)">
                            <div>
                                <div style="font-weight:600;"><?= esc($objectif['libelle']) ?></div>
                                <div style="font-size:12px;color:var(--muted)">
                                    Cible: <?= esc($objectif['valeur_cible'] ?? 'N/A') ?>
                                </div>
                            </div>

                            <div style="text-align:right">
                                <span class="badge badge-<?= $objectif['statut'] === 'en_cours' ? 'primary' : 'success' ?>">
                                    <?= $objectif['statut'] === 'en_cours' ? 'En cours' : 'Terminé' ?>
                                </span>

                                <?php if ($objectif['statut'] === 'en_cours'): ?>
                                    <form method="post" action="/profil/terminer-objectif">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="utilisateur_objectif_id" value="<?= $objectif['utilisateur_objectif_id'] ?>">
                                        <button class="btn btn-success btn-sm" style="margin-top:6px;">Terminer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:20px;color:var(--muted)">
                        🎯 Aucun objectif défini
                    </div>
                <?php endif; ?>

            </div>
        </div>


        <!-- AJOUT OBJECTIF -->
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--green-bg)">➕</span>
                    Ajouter un objectif
                </span>
            </div>

            <div class="card-body">

                <?php if ($peutCreerObjectif): ?>

                    <form method="post" action="/profil/creer-objectif">
                        <?= csrf_field() ?>

                        <div class="form-group" style="margin-bottom:15px;">
                            <label>Objectif</label>
                            <select name="objectif_id" class="form-control" required>
                                <option value="">Choisir un objectif</option>
                                <?php foreach ($tousObjectifs as $obj): ?>
                                    <option value="<?= $obj['id'] ?>">
                                        <?= esc($obj['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:15px;">
                            <label>Valeur cible</label>
                            <input type="text"
                                name="valeur_cible"
                                class="form-control"
                                placeholder="Ex: 70kg, 10km">
                        </div>

                        <button type="submit"
                                class="btn btn-primary"
                                style="margin-top:10px;">
                            Créer
                        </button>

                    </form>

                <?php else: ?>

                    <div class="alert alert-warning" style="margin:0;">
                        Terminez votre objectif actuel avant d’en créer un nouveau.
                    </div>

                <?php endif; ?>

            </div>
        </div>


 <!-- SUGGESTION AUTOMATIQUE -->
            <div class="card">

                <div class="card-body">

                    <div class="generate-content" style="text-align:center;padding:10px 5px">

                        <form action="/suggestion/generate" method="post">
                            <?= csrf_field() ?>

                            <button type="submit"
                                style="
                                    background: linear-gradient(135deg, var(--accent), var(--green));
                                    border: none;
                                    padding: 14px 28px;
                                    font-size: 16px;
                                    font-weight: 700;
                                    border-radius: 12px;
                                    color: white;
                                    cursor: pointer;
                                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                                    transform: scale(1);
                                    transition: all 0.2s ease;
                                "
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'"
                            >
                                🚀 Obtenir ma suggestion
                            </button>

                        </form>

                    </div>

                </div>
            </div>

    </div>
</div>

<?= $this->endSection() ?>