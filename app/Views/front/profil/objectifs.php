<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🎯</span>
                        Mes objectifs
                    </span>
                    <span class="card-link">Gestion des objectifs</span>
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
                            <div class="metric-label">Objectifs terminés</div>
                        </div>
                        <div class="metric m-amber">
                            <div class="metric-icon">⚠️</div>
                            <div class="metric-val"><?= count(array_filter($objectifsUtilisateur, fn($obj) => $obj['statut'] === 'en_cours')) ?></div>
                            <div class="metric-label">En cours</div>
                        </div>
                        <div class="metric m-purple">
                            <div class="metric-icon">📝</div>
                            <div class="metric-val"><?= count($tousObjectifs) ?></div>
                            <div class="metric-label">Objectifs disponibles</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row2">
        <div class="card">
            <div class="card-head">
                <span class="card-title">
                    <span class="ct-icon" style="background:var(--blue-bg)">📋</span>
                    Objectifs définis
                </span>
                <a href="/profil/objectifs" class="card-link">Actualiser</a>
            </div>
            <div class="card-body">
                <?php if (!empty($objectifsUtilisateur)): ?>
                    <?php foreach ($objectifsUtilisateur as $objectif): ?>
                        <div class="obj-item" style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;border-bottom:1px solid rgba(0,0,0,0.05)">
                            <div>
                                <div style="font-weight:600;"><?= esc($objectif['libelle']) ?></div>
                                <div style="font-size:13px;color:var(--muted)">Cible: <?= esc($objectif['valeur_cible'] ?? 'N/A') ?></div>
                                <div style="font-size:12px;color:var(--muted)">Créé le <?= date('d/m/Y H:i', strtotime($objectif['date_creation'])) ?></div>
                            </div>
                            <div style="text-align:right">
                                <span class="badge badge-<?= $objectif['statut'] === 'en_cours' ? 'primary' : 'success' ?>">
                                    <?= $objectif['statut'] === 'en_cours' ? 'En cours' : 'Terminé' ?>
                                </span>
                                <?php if ($objectif['statut'] === 'en_cours'): ?>
                                    <form method="post" action="/profil/terminer-objectif" style="margin-top:8px;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="utilisateur_objectif_id" value="<?= $objectif['utilisateur_objectif_id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Terminer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:24px;color:var(--muted)">
                        <div style="font-size:24px;margin-bottom:8px;">🎯</div>
                        <div>Aucun objectif défini pour le moment.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="objectif_id">Choisissez un objectif</label>
                                    <select name="objectif_id" id="objectif_id" class="form-control" required>
                                        <option value="">Sélectionnez un objectif</option>
                                        <?php foreach ($tousObjectifs as $objectif): ?>
                                            <option value="<?= $objectif['id'] ?>"><?= esc($objectif['libelle']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valeur_cible">Valeur cible (optionnel)</label>
                                    <input type="text" name="valeur_cible" id="valeur_cible" class="form-control" placeholder="Ex: 70kg, 10km">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block">Créer</button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin:0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Vous devez terminer votre objectif actuel avant d'en créer un nouveau.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>