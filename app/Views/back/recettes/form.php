<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
:root {
    --bg:       #0a0a0f;
    --bg2:      #111118;
    --bg3:      #18181f;
    --border:   rgba(255,255,255,0.07);
    --text:     #f0f0f5;
    --muted:    #6b6b7e;
    --accent:   #7c6ef5;
    --red:      #f56060;
}

.page { padding: 24px 28px; display: flex; flex-direction: column; gap: 22px; max-width: 600px; }

.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--muted); margin-bottom: 10px; }

.breadcrumb a { color: var(--accent); text-decoration: none; }

.page-title { font-size: 20px; font-weight: 700; color: var(--text); }

.card { background: var(--bg2); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }

.card-body { padding: 20px; }

.form-group { margin-bottom: 18px; }

.form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text); margin-bottom: 6px; }

.form-input, .form-select { width: 100%; padding: 10px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 12px; font-family: inherit; }

.form-input:focus, .form-select:focus { outline: none; border-color: var(--accent); }

.btn { padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 0.2s; }

.btn-primary { background: var(--accent); color: white; }
.btn-primary:hover { opacity: 0.9; }

.btn-secondary { background: transparent; color: var(--accent); border: 1px solid var(--accent); }

.btn-group { display: flex; gap: 10px; }

.alert { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; }

.alert-error { background: rgba(245,96,96,0.1); border: 1px solid var(--red); color: var(--red); }
</style>

<div class="page">

    <div class="breadcrumb">
        <a href="/admin/regimes">🥗 Régimes</a>
        <span>/</span>
        <a href="/admin/recettes/<?= $regime['id'] ?>"><?= esc($regime['libelle']) ?></a>
        <span>/</span>
        <span><?= $recette ? 'Éditer' : 'Ajouter' ?></span>
    </div>

    <div class="page-title"><?= $recette ? 'Éditer recette' : 'Ajouter aliment' ?></div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $recette ? '/admin/recettes/update/' . $regime['id'] . '/' . $recette['id'] : '/admin/recettes/store/' . $regime['id'] ?>">
        <?= csrf_field() ?>

        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Aliment *</label>
                    <select name="aliment_id" class="form-select" required>
                        <option value="">-- Sélectionner un aliment --</option>
                        <?php foreach ($aliments as $aliment): ?>
                            <option value="<?= $aliment['id'] ?>" <?= ($recette && $recette['aliment_id'] == $aliment['id']) ? 'selected' : '' ?>>
                                <?= esc($aliment['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pourcentage (%) *</label>
                    <input type="number" name="pourcentage" class="form-input" step="0.1" min="0" max="100" value="<?= old('pourcentage', $recette['pourcentage'] ?? '') ?>" placeholder="Ex: 25" required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><?= $recette ? 'Mettre à jour' : 'Ajouter' ?></button>
                    <a href="/admin/recettes/<?= $regime['id'] ?>" class="btn btn-secondary">Annuler</a>
                </div>

            </div>
        </div>

    </form>

</div>

<?= $this->endSection() ?>
