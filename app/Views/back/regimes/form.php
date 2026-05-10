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

.form-input { width: 100%; padding: 10px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 12px; font-family: inherit; }

.form-input:focus { outline: none; border-color: var(--accent); }

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
        <span><?= $regime ? 'Éditer' : 'Créer' ?></span>
    </div>

    <div class="page-title"><?= $regime ? 'Éditer régime' : 'Créer régime' ?></div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $regime ? '/admin/regimes/update/' . $regime['id'] : '/admin/regimes/store' ?>">
        <?= csrf_field() ?>

        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Libellé du régime *</label>
                    <input type="text" name="libelle" class="form-input" value="<?= old('libelle', $regime['libelle'] ?? '') ?>" placeholder="Ex: Régime Kéto, Régime Méditerranéen..." required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><?= $regime ? 'Mettre à jour' : 'Créer' ?></button>
                    <a href="/admin/regimes" class="btn btn-secondary">Annuler</a>
                </div>

            </div>
        </div>

    </form>

</div>

<?= $this->endSection() ?>
