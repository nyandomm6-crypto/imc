<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<int, array<string, mixed>>|null $genres */
/** @var array<string, mixed>|null $mesure */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$genres      = is_array($genres ?? null) ? $genres : [];
$mesure      = is_array($mesure ?? null) ? $mesure : null;

$nom           = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$pageTitle     = 'Mon Profil';
$pageSubtitle  = 'Gérer vos informations personnelles';
$activeNav     = 'profil';
?>

<?= $this->section('content') ?>

<style>
.form-grid {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.field label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
}

.field .code-input {
    width: 100%;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    font-size: 13px;
    padding: 9px 12px;
    font-family: 'Inter', sans-serif;
    transition: 0.2s;
    outline: none;
    appearance: none;
}

.field .code-input:focus {
    border-color: var(--accent);
    background: var(--bg2);
}

.field .code-input::placeholder {
    color: var(--muted);
}

select.code-input {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b7e' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.code-btn {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 500;
    padding: 10px 18px;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: 0.2s;
    align-self: flex-start;
}

.code-btn:hover {
    background: #6b5fe0;
}

.avatar-bloc {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    background: var(--bg3);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    margin-bottom: 18px;
}

.avatar-big {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #4f46e5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.avatar-info-name {
    font-size: 15px;
    font-weight: 600;
    color: var(--text);
}

.avatar-info-email {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.imc-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: var(--bg3);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    margin-bottom: 18px;
}

.imc-preview-val {
    font-size: 28px;
    font-weight: 700;
    color: var(--green);
}

.imc-preview-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--muted);
}

.imc-preview-cat {
    font-size: 11px;
    font-weight: 500;
    padding: 2px 8px;
    border-radius: 20px;
}

.section-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.pw-wrap {
    position: relative;
}

.pw-wrap .code-input {
    padding-right: 40px;
}

.pw-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
}

.stat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 0;
    border-bottom: 1px solid var(--border);
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-row-label {
    font-size: 12px;
    color: var(--muted);
}

.stat-row-val {
    font-size: 13px;
    font-weight: 500;
    color: var(--text);
}
<style>
/* Modern profile redesign: glass cards, gradient header, large avatar */
.profile-wrap { display: grid; grid-template-columns: 1fr 360px; gap: 22px; align-items: start; }
.card { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius: 16px; padding: 18px; border: 1px solid rgba(255,255,255,0.04); box-shadow: 0 6px 18px rgba(7,6,23,0.6); }
.card-head { display:flex;align-items:center;gap:10px;margin-bottom:12px }
.card-title { font-weight:700;color:var(--text);font-size:15px }
.profile-header { display:flex;align-items:center;gap:16px;padding:18px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#4f46e5);color:white }
.profile-header .avatar-large { width:84px;height:84px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800;box-shadow:0 8px 24px rgba(79,70,229,0.28) }
.profile-meta { display:flex;flex-direction:column }
.profile-name { font-size:20px;font-weight:800 }
.profile-email { font-size:13px;opacity:0.9 }
.muted { color:var(--muted);font-size:12px }
.form-grid { display:flex;flex-direction:column;gap:12px }
.field label { font-size:12px;color:var(--muted);font-weight:600 }
.code-input { width:100%; padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,0.04); background:rgba(255,255,255,0.02); color:var(--text); }
.code-input:focus { outline:none; box-shadow:0 6px 18px rgba(0,0,0,0.4); border-color: rgba(124,110,245,0.9); }
.btn { padding:10px 14px;border-radius:12px;border:none;cursor:pointer }
.btn-primary { background:linear-gradient(90deg,var(--accent),#6b5fe0); color:white;font-weight:700 }
.btn-ghost { background:transparent;border:1px solid rgba(255,255,255,0.04);color:var(--text) }
.stats { display:flex;flex-direction:column;gap:8px }
.stat { display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:10px;background:rgba(0,0,0,0.03) }
.imc-badge { padding:8px 12px;border-radius:999px;font-weight:700 }
.small { font-size:12px }

@media (max-width: 900px) { .profile-wrap { grid-template-columns: 1fr; } }
</style>
<?php
$error   = session()->getFlashdata('error');
$success = session()->getFlashdata('success');

$parts = explode(' ', trim($nom));

$initiales = strtoupper(
    substr($parts[0] ?? 'U', 0, 1) .
    (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
);

$poidsActuel  = (float) ($mesure['poids_kg'] ?? 0);
$tailleActuel = (float) ($mesure['taille_m'] ?? 0);

$imcActuel = ($tailleActuel > 0)
    ? round($poidsActuel / ($tailleActuel ** 2), 1)
    : null;

$imcCat = match (true) {
    $imcActuel === null => [
        'label' => '--',
        'color' => 'var(--muted)',
        'bg'    => 'var(--bg3)'
    ],

    $imcActuel < 18.5 => [
        'label' => 'Maigreur',
        'color' => 'var(--blue)',
        'bg'    => 'var(--blue-bg)'
    ],

    $imcActuel < 25 => [
        'label' => 'Normal',
        'color' => 'var(--green)',
        'bg'    => 'var(--green-bg)'
    ],

    $imcActuel < 30 => [
        'label' => 'Surpoids',
        'color' => 'var(--amber)',
        'bg'    => 'var(--amber-bg)'
    ],

    default => [
        'label' => 'Obésité',
        'color' => 'var(--red)',
        'bg'    => 'var(--red-bg)'
    ],
};
?>
?>

<?php if ($error): ?>

<div class="alert alert-amber">
    ⚠ <?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
</div>

<?php endif; ?>

<?php if ($success): ?>

<div class="alert" style="background:var(--green-bg);color:var(--green)">
    ✓ <?= esc((string) $success) ?>
</div>

<?php endif; ?>

<div class="profile-wrap">

    <div>

        <div class="card">
            <div class="profile-header">
                <div class="avatar-large"><?= esc($initiales) ?></div>
                <div class="profile-meta">
                    <div class="profile-name"><?= esc($nom) ?></div>
                    <div class="profile-email"><?= esc($asString($utilisateur['email'] ?? null, '—')) ?></div>
                    <div class="muted small">Membre depuis: <?= esc($asString($utilisateur['date_creation'] ?? '—')) ?></div>
                </div>
            </div>

            <div style="margin-top:16px">
                <form method="post" action="<?= site_url('profil/update') ?>" class="form-grid">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="nom">Nom complet</label>
                        <input class="code-input" id="nom" name="nom" type="text" required value="<?= esc((string) (old('nom') ?? $utilisateur['nom'] ?? '')) ?>">
                    </div>

                    <div class="field">
                        <label for="email">Adresse email</label>
                        <input class="code-input" id="email" name="email" type="email" required value="<?= esc((string) (old('email') ?? $utilisateur['email'] ?? '')) ?>">
                    </div>

                    <div style="display:flex;gap:10px">
                        <div style="flex:1" class="field">
                            <label for="date_naissance">Date de naissance</label>
                            <input class="code-input" id="date_naissance" name="date_naissance" type="date" required value="<?= esc((string) (old('date_naissance') ?? $utilisateur['date_naissance'] ?? '')) ?>">
                        </div>
                        <div style="width:140px" class="field">
                            <label for="genre_id">Genre</label>
                            <select class="code-input" id="genre_id" name="genre_id" required>
                                <option value="">—</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= esc((string) ($genre['id'] ?? '')) ?>" <?= (string) (old('genre_id') ?? $utilisateur['genre_id'] ?? '') === (string) ($genre['id'] ?? '') ? 'selected' : '' ?>><?= esc($asString($genre['nom'] ?? null)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="mot_de_passe">Nouveau mot de passe</label>
                        <div style="position:relative">
                            <input class="code-input" id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="new-password" placeholder="Laisser vide pour ne pas changer">
                            <button type="button" class="pw-toggle" onclick="togglePw('mot_de_passe')" style="position:absolute;right:10px;top:8px">👁</button>
                        </div>
                    </div>

                    <div class="field">
                        <label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
                        <div style="position:relative">
                            <input class="code-input" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" type="password" autocomplete="new-password">
                            <button type="button" class="pw-toggle" onclick="togglePw('confirmation_mot_de_passe')" style="position:absolute;right:10px;top:8px">👁</button>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;margin-top:6px">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                        <a class="btn btn-ghost" href="/">Retour</a>
                    </div>
                </form>
            </div>
        </div>

        <div style="height:18px"></div>

        <div class="card">
            <div class="card-head"><span class="card-title">🔒 Sécurité</span></div>
            <div style="margin-top:8px" class="small muted">Modifiez votre mot de passe ici ou utilisez la réinitialisation si besoin.</div>
        </div>

    </div>

    <div>
        <div class="card">
            <div class="card-head"><span class="card-title">📏 Mesures corporelles</span></div>
            <div style="margin-top:12px">
                <?php if ($imcActuel !== null): ?>
                    <div class="stat" style="align-items:center;gap:12px">
                        <div>
                            <div class="small muted">IMC actuel</div>
                            <div style="font-weight:800;font-size:20px;color:<?= $imcCat['color'] ?>"><?= number_format($imcActuel, 1) ?></div>
                        </div>
                        <div><span class="imc-badge" style="background:<?= $imcCat['bg'] ?>;color:<?= $imcCat['color'] ?>"><?= $imcCat['label'] ?></span></div>
                    </div>
                <?php endif; ?>

                <div style="height:12px"></div>

                <form method="post" action="<?= site_url('profil/mesure') ?>" class="form-grid">
                    <?= csrf_field() ?>
                    <div class="field"><label for="poids_kg">Poids (kg)</label><input class="code-input" id="poids_kg" name="poids_kg" type="number" step="0.1" min="1" max="300" required value="<?= esc((string) (old('poids_kg') ?? $mesure['poids_kg'] ?? '')) ?>"></div>
                    <div class="field"><label for="taille_m">Taille (m)</label><input class="code-input" id="taille_m" name="taille_m" type="number" step="0.01" min="0.5" max="2.5" required value="<?= esc((string) (old('taille_m') ?? $mesure['taille_m'] ?? '')) ?>"></div>
                    <div id="imc-live" style="display:none;padding:10px;background:rgba(0,0,0,0.03);border-radius:10px">IMC calculé : <strong id="imc-live-val">--</strong></div>
                    <button class="btn btn-primary" type="submit">Mettre à jour la mesure</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function togglePw(id)
{
    const input = document.getElementById(id);

    input.type = input.type === 'password'
        ? 'text'
        : 'password';
}

const poidsInput  = document.getElementById('poids_kg');
const tailleInput = document.getElementById('taille_m');

const imcLive    = document.getElementById('imc-live');
const imcLiveVal = document.getElementById('imc-live-val');

function updateIMC()
{
    const p = parseFloat(poidsInput.value);
    const t = parseFloat(tailleInput.value);

    if (p > 0 && t > 0)
    {
        const imc = (p / (t * t)).toFixed(1);

        imcLiveVal.textContent = imc;
        imcLive.style.display = 'block';
    }
    else
    {
        imcLive.style.display = 'none';
    }
}

poidsInput.addEventListener('input', updateIMC);
tailleInput.addEventListener('input', updateIMC);
</script>

<?= $this->endSection() ?>