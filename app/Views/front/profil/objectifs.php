<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Mes Objectifs</h4>
                </div>
                <div class="card-body">
                    <!-- Affichage des objectifs existants -->
                    <?php if (!empty($objectifsUtilisateur)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Objectif</th>
                                        <th>Valeur cible</th>
                                        <th>Statut</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($objectifsUtilisateur as $objectif): ?>
                                        <tr>
                                            <td><?= esc($objectif['libelle']) ?></td>
                                            <td><?= esc($objectif['valeur_cible'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge badge-<?= $objectif['statut'] === 'en_cours' ? 'primary' : 'success' ?>">
                                                    <?= $objectif['statut'] === 'en_cours' ? 'En cours' : 'Terminé' ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($objectif['date_creation'])) ?></td>
                                            <td>
                                                <?php if ($objectif['statut'] === 'en_cours'): ?>
                                                    <form method="post" action="/profil/terminer-objectif" style="display: inline;">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="utilisateur_objectif_id" value="<?= $objectif['utilisateur_objectif_id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir terminer cet objectif ?')">
                                                            Terminer
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucun objectif défini pour le moment.</p>
                    <?php endif; ?>

                    <hr>

                    <!-- Formulaire de création d'objectif -->
                    <h5>Créer un nouvel objectif</h5>
                    <?php if ($peutCreerObjectif): ?>
                        <form method="post" action="/profil/creer-objectif">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="objectif_id">Objectif</label>
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">Créer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Vous devez terminer votre objectif actuel avant d'en créer un nouveau.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>