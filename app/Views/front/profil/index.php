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

<div class="row2">

    <!-- COLONNE GAUCHE -->
    <div class="card">

        <div class="card-head">
            <span class="card-title">
                👤 Informations personnelles
            </span>
        </div>

        <div class="card-body">

            <div class="avatar-bloc">

                <div class="avatar-big">
                    <?= esc($initiales) ?>
                </div>

                <div>
                    <div class="avatar-info-name">
                        <?= esc($nom) ?>
                    </div>

                    <div class="avatar-info-email">
                        <?= esc($asString($utilisateur['email'] ?? null, '—')) ?>
                    </div>
                </div>

            </div>

            <form method="post" action="<?= site_url('profil/update') ?>" class="form-grid">

                <?= csrf_field() ?>

                <div class="section-label">
                    Identité
                </div>

                <div class="field">
                    <label for="nom">Nom complet</label>

                    <input
                        class="code-input"
                        id="nom"
                        name="nom"
                        type="text"
                        required
                        value="<?= esc((string) (old('nom') ?? $utilisateur['nom'] ?? '')) ?>"
                    >
                </div>

                <div class="field">
                    <label for="email">Adresse email</label>

                    <input
                        class="code-input"
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="<?= esc((string) (old('email') ?? $utilisateur['email'] ?? '')) ?>"
                    >
                </div>

                <div class="field">
                    <label for="date_naissance">Date de naissance</label>

                    <input
                        class="code-input"
                        id="date_naissance"
                        name="date_naissance"
                        type="date"
                        required
                        value="<?= esc((string) (old('date_naissance') ?? $utilisateur['date_naissance'] ?? '')) ?>"
                    >
                </div>

                <div class="field">
                    <label for="genre_id">Genre</label>

                    <select class="code-input" id="genre_id" name="genre_id" required>

                        <option value="">— Choisir —</option>

                        <?php foreach ($genres as $genre): ?>

                            <option
                                value="<?= esc((string) ($genre['id'] ?? '')) ?>"
                                <?= (string) (old('genre_id') ?? $utilisateur['genre_id'] ?? '') === (string) ($genre['id'] ?? '') ? 'selected' : '' ?>
                            >
                                <?= esc($asString($genre['nom'] ?? null)) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="section-label">
                    Sécurité
                </div>

                <div class="field">
                    <label for="mot_de_passe">Nouveau mot de passe</label>

                    <div class="pw-wrap">

                        <input
                            class="code-input"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Laisser vide pour ne pas changer"
                        >

                        <button
                            type="button"
                            class="pw-toggle"
                            onclick="togglePw('mot_de_passe')"
                        >
                            👁
                        </button>

                    </div>
                </div>

                <div class="field">
                    <label for="confirmation_mot_de_passe">
                        Confirmer le mot de passe
                    </label>

                    <div class="pw-wrap">

                        <input
                            class="code-input"
                            id="confirmation_mot_de_passe"
                            name="confirmation_mot_de_passe"
                            type="password"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="pw-toggle"
                            onclick="togglePw('confirmation_mot_de_passe')"
                        >
                            👁
                        </button>

                    </div>
                </div>

                <button
                    class="code-btn"
                    type="submit"
                    style="align-self:stretch;text-align:center"
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>
    </div>

    <!-- COLONNE DROITE -->
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">

            <div class="card-head">
                <span class="card-title">
                    📏 Mesures corporelles
                </span>
            </div>

            <div class="card-body">

                <?php if ($imcActuel !== null): ?>

                <div class="imc-preview">

                    <div>
                        <div class="imc-preview-label">
                            IMC actuel
                        </div>

                        <div
                            class="imc-preview-val"
                            style="color:<?= $imcCat['color'] ?>"
                        >
                            <?= number_format($imcActuel, 1) ?>
                        </div>
                    </div>

                    <div style="margin-left:auto;text-align:right">

                        <span
                            class="imc-preview-cat"
                            style="background:<?= $imcCat['bg'] ?>;color:<?= $imcCat['color'] ?>"
                        >
                            <?= $imcCat['label'] ?>
                        </span>

                    </div>

                </div>

                <?php endif; ?>

                <form method="post" action="<?= site_url('profil/mesure') ?>" class="form-grid">

                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="poids_kg">Poids (kg)</label>

                        <input
                            class="code-input"
                            id="poids_kg"
                            name="poids_kg"
                            type="number"
                            step="0.1"
                            min="1"
                            max="300"
                            required
                            value="<?= esc((string) (old('poids_kg') ?? $mesure['poids_kg'] ?? '')) ?>"
                        >
                    </div>

                    <div class="field">
                        <label for="taille_m">Taille (m)</label>

                        <input
                            class="code-input"
                            id="taille_m"
                            name="taille_m"
                            type="number"
                            step="0.01"
                            min="0.5"
                            max="2.5"
                            required
                            value="<?= esc((string) (old('taille_m') ?? $mesure['taille_m'] ?? '')) ?>"
                        >
                    </div>

                    <div
                        id="imc-live"
                        style="display:none;padding:10px;background:var(--bg3)"
                    >
                        IMC calculé :
                        <strong id="imc-live-val">--</strong>
                    </div>

                    <button
                        class="code-btn"
                        type="submit"
                        style="align-self:stretch;text-align:center"
                    >
                        Mettre à jour la mesure
                    </button>

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