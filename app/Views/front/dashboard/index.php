<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap');

:root {
    --bg:        #0a0a0f;
    --bg2:       #111118;
    --bg3:       #18181f;
    --border:    rgba(255,255,255,0.07);
    --border2:   rgba(255,255,255,0.12);
    --text:      #f0f0f5;
    --muted:     #6b6b7e;
    --muted2:    #9090a0;
    --accent:    #7c6ef5;
    --accent2:   #a898ff;
    --green:     #2dd4a0;
    --green-bg:  rgba(45,212,160,0.1);
    --amber:     #f5a623;
    --amber-bg:  rgba(245,166,35,0.1);
    --red:       #f56060;
    --red-bg:    rgba(245,96,96,0.1);
    --blue:      #5ba3f5;
    --blue-bg:   rgba(91,163,245,0.1);
    --gold:      #e8b84b;
    --gold-bg:   rgba(232,184,75,0.12);
    --radius:    14px;
    --radius-sm: 8px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: var(--bg);
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: var(--text);
    min-height: 100vh;
    line-height: 1.5;
}

/* ── SIDEBAR ── */
.layout { display: flex; min-height: 100vh; }

.sidebar {
    width: 220px;
    flex-shrink: 0;
    background: var(--bg2);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    padding: 0;
    position: sticky;
    top: 0;
    height: 100vh;
}

.sidebar-logo {
    padding: 22px 20px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo-icon {
    width: 32px; height: 32px;
    background: var(--accent);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
}

.logo-text {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: var(--text);
}

.sidebar-nav { padding: 14px 10px; flex: 1; }

.nav-section {
    font-size: 10px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    padding: 0 10px;
    margin: 12px 0 6px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: var(--radius-sm);
    color: var(--muted2);
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    transition: all 0.15s;
    margin-bottom: 2px;
    cursor: pointer;
}

.nav-item:hover { background: var(--bg3); color: var(--text); }
.nav-item.active { background: rgba(124,110,245,0.15); color: var(--accent2); }
.nav-item .ni { font-size: 15px; width: 18px; text-align: center; }

.sidebar-user {
    padding: 14px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #4f46e5);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.user-name { font-size: 12px; font-weight: 500; color: var(--text); }
.user-role { font-size: 11px; color: var(--muted); }

.sidebar-user a {
    margin-left: auto;
    color: var(--muted);
    text-decoration: none;
    font-size: 16px;
    transition: color 0.15s;
}
.sidebar-user a:hover { color: var(--red); }

/* ── MAIN ── */
.main {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.topbar {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg2);
    backdrop-filter: blur(10px);
    position: sticky;
    top: 0;
    z-index: 10;
}

.topbar-title {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: -0.3px;
    color: var(--text);
}

.topbar-sub { font-size: 12px; color: var(--muted); margin-top: 1px; }

.btn-gold {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--gold-bg);
    color: var(--gold);
    border: 1px solid rgba(232,184,75,0.3);
    font-size: 12px;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 20px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.btn-gold:hover { background: rgba(232,184,75,0.2); }

/* ── CONTENT ── */
.content { padding: 24px; display: flex; flex-direction: column; gap: 20px; }

/* ── METRICS ── */
.metrics { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; }

.metric {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.2s;
}
.metric:hover { border-color: var(--border2); }

.metric::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    border-radius: var(--radius) var(--radius) 0 0;
}
.metric.m-purple::before { background: var(--accent); }
.metric.m-green::before  { background: var(--green); }
.metric.m-amber::before  { background: var(--amber); }
.metric.m-blue::before   { background: var(--blue); }

.metric-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    margin-bottom: 12px;
}
.m-purple .metric-icon { background: rgba(124,110,245,0.15); }
.m-green  .metric-icon { background: var(--green-bg); }
.m-amber  .metric-icon { background: var(--amber-bg); }
.m-blue   .metric-icon { background: var(--blue-bg); }

.metric-val {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.5px;
    line-height: 1;
    margin-bottom: 4px;
}
.m-purple .metric-val { color: var(--accent2); }
.m-green  .metric-val { color: var(--green); }
.m-amber  .metric-val { color: var(--amber); }
.m-blue   .metric-val { color: var(--blue); }

.metric-label { font-size: 11px; color: var(--muted); font-weight: 400; }
.metric-badge {
    display: inline-block;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    margin-top: 5px;
    font-weight: 500;
}

/* ── CARDS ── */
.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: border-color 0.2s;
}
.card:hover { border-color: var(--border2); }

.card-head {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-title {
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.2px;
}

.card-title .ct-icon {
    width: 24px; height: 24px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
}

.card-link {
    font-size: 11px;
    color: var(--accent2);
    text-decoration: none;
    font-weight: 500;
    opacity: 0.8;
    transition: opacity 0.15s;
}
.card-link:hover { opacity: 1; }
.card-body { padding: 16px 18px; }

/* ── IMC ── */
.imc-big {
    font-family: 'Syne', sans-serif;
    font-size: 48px;
    font-weight: 700;
    letter-spacing: -2px;
    line-height: 1;
}

.imc-track {
    position: relative;
    height: 6px;
    border-radius: 3px;
    margin: 14px 0 6px;
    background: var(--bg3);
    overflow: visible;
}

.imc-fill {
    position: absolute;
    left: 0; top: 0; bottom: 0;
    border-radius: 3px;
    background: linear-gradient(to right, var(--blue), var(--green), var(--amber), var(--red));
    width: 100%;
}

.imc-needle {
    position: absolute;
    top: -5px;
    width: 4px;
    height: 16px;
    background: var(--text);
    border-radius: 2px;
    transform: translateX(-50%);
    box-shadow: 0 0 8px rgba(255,255,255,0.4);
    transition: left 0.6s cubic-bezier(.34,1.56,.64,1);
}

.imc-zones {
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: var(--muted);
    margin-top: 5px;
}

.imc-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
}

.imc-stat-label { font-size: 10px; color: var(--muted); margin-bottom: 3px; }
.imc-stat-val { font-size: 15px; font-weight: 500; color: var(--text); }

/* ── OBJECTIFS ── */
.obj-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid var(--border);
}
.obj-item:last-child { border-bottom: none; }

.obj-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}

.obj-label { flex: 1; font-size: 12px; color: var(--text); }
.obj-empty { color: var(--muted); font-style: italic; }

.badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 500;
}
.badge-green { background: var(--green-bg); color: var(--green); }
.badge-muted { background: var(--bg3); color: var(--muted); border: 1px solid var(--border); }

/* ── GRILLES ── */
.row2 { display: grid; grid-template-columns: minmax(0,1.4fr) minmax(0,0.6fr); gap: 16px; }
.row3 { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 16px; }

/* ── RÉGIMES ── */
.regime-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.regime-item:last-child { border-bottom: none; }

.ri-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--green-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

.ri-name { font-size: 13px; font-weight: 500; color: var(--text); }
.ri-sub { font-size: 11px; color: var(--muted); margin-top: 1px; }
.ri-price { font-size: 13px; font-weight: 600; color: var(--green); margin-left: auto; white-space: nowrap; }

.pdf-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 500;
    padding: 10px;
    border: 1px dashed var(--border2);
    border-radius: var(--radius-sm);
    color: var(--muted2);
    cursor: pointer;
    background: transparent;
    width: 100%;
    margin-top: 12px;
    text-decoration: none;
    transition: all 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.pdf-btn:hover { border-color: var(--accent); color: var(--accent2); background: rgba(124,110,245,0.05); }

/* ── SPORTS ── */
.sport-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid var(--border);
}
.sport-item:last-child { border-bottom: none; }

.si-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(124,110,245,0.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

.si-cal {
    font-size: 12px;
    font-weight: 600;
    color: var(--accent2);
    margin-left: auto;
    background: rgba(124,110,245,0.1);
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

/* ── PORTE-MONNAIE ── */
.wallet-grid { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,2fr); gap: 24px; }

.wallet-solde {
    font-family: 'Syne', sans-serif;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -1px;
    color: var(--green);
    margin: 6px 0 16px;
}

.code-wrap { display: flex; gap: 8px; }

.code-input {
    flex: 1;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    font-size: 12px;
    padding: 8px 12px;
    font-family: 'DM Sans', sans-serif;
    transition: border-color 0.2s;
    outline: none;
}
.code-input:focus { border-color: var(--accent); }
.code-input::placeholder { color: var(--muted); }

.code-btn {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 500;
    padding: 8px 16px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.2s;
    white-space: nowrap;
}
.code-btn:hover { background: #6b5fe0; }

.tx-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
}
.tx-item:last-child { border-bottom: none; }

.tx-amount {
    font-size: 12px;
    font-weight: 600;
    margin-left: auto;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
}
.tx-plus { background: var(--green-bg); color: var(--green); }
.tx-minus { background: var(--red-bg); color: var(--red); }

/* ── ALERT ── */
.alert {
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 12px;
    border: 1px solid;
    display: flex;
    align-items: center;
    gap: 8px;
}
.alert-amber { background: var(--amber-bg); color: var(--amber); border-color: rgba(245,166,35,0.3); }

/* ── ANIMATIONS ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

.metrics .metric    { animation: fadeUp 0.4s ease both; }
.metric:nth-child(1){ animation-delay: 0.05s; }
.metric:nth-child(2){ animation-delay: 0.10s; }
.metric:nth-child(3){ animation-delay: 0.15s; }
.metric:nth-child(4){ animation-delay: 0.20s; }
.row2, .row3, .card { animation: fadeUp 0.4s ease 0.2s both; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--bg3); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--muted); }
</style>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<string, mixed>|null $mesure */
/** @var array<int, array<string, mixed>>|null $objectifs */
/** @var array<int, array<string, mixed>>|null $regimes */
/** @var array<int, array<string, mixed>>|null $sports */
/** @var float|int|null $imc */
/** @var int|float|null $imcProgression */
/** @var float|int|null $soldeCompte */
/** @var string|null $categorieImc */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur    = is_array($utilisateur ?? null) ? $utilisateur : [];
$mesure         = is_array($mesure ?? null) ? $mesure : null;
$objectifs      = is_array($objectifs ?? null) ? $objectifs : [];
$regimes        = is_array($regimes ?? null) ? $regimes : [];
$sports         = is_array($sports ?? null) ? $sports : [];

$nom       = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$parts     = explode(' ', trim($nom));
$initiales = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

$imc            = is_numeric($imc ?? null) ? (float) $imc : null;
$categorieImc   = $asString($categorieImc ?? null, 'Inconnue');
$imcVal         = $imc !== null ? number_format($imc, 1) : '--';
$progression    = is_numeric($imcProgression ?? null) ? (int) $imcProgression : 0;
$poids          = $asString($mesure['poids_kg'] ?? null, '--');
$taille         = $asString($mesure['taille_m'] ?? null, '--');
$solde          = number_format((float) ($soldeCompte ?? 0), 2);
$nbObj          = count($objectifs);

$catStyle = match(true) {
    $imc !== null && $imc < 18.5 => ['color' => 'var(--blue)',  'bg' => 'var(--blue-bg)'],
    $imc !== null && $imc < 25   => ['color' => 'var(--green)', 'bg' => 'var(--green-bg)'],
    $imc !== null && $imc < 30   => ['color' => 'var(--amber)', 'bg' => 'var(--amber-bg)'],
    $imc !== null                 => ['color' => 'var(--red)',   'bg' => 'var(--red-bg)'],
    default                       => ['color' => 'var(--muted)', 'bg' => 'var(--bg3)'],
};

$sportIcons = ['🏃','🏊','🚴','🧘','🏋️','⛹️','🤸','🥊'];
$regimeIcons = ['🥗','🐟','🥦','🍗','🫐','🥑'];
?>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">💪</div>
            <span class="logo-text">FitIMC</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <a class="nav-item active" href="/dashboard">
                <span class="ni">⊞</span> Dashboard
            </a>
            <a class="nav-item" href="/profil">
                <span class="ni">◎</span> Mon profil
            </a>
            <a class="nav-item" href="/profil/objectifs">
                <span class="ni">◈</span> Objectifs
            </a>

            <div class="nav-section">Nutrition</div>
            <a class="nav-item" href="/regimes">
                <span class="ni">🥗</span> Régimes
            </a>
            <a class="nav-item" href="/sports">
                <span class="ni">🏃</span> Sports
            </a>

            <div class="nav-section">Compte</div>
            <a class="nav-item" href="/porte-monnaie">
                <span class="ni">◈</span> Porte-monnaie
            </a>
            <a class="nav-item" href="/gold">
                <span class="ni">★</span> Passer Gold
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar"><?= esc($initiales) ?></div>
            <div>
                <div class="user-name"><?= esc(strlen($nom) > 14 ? substr($nom,0,14).'…' : $nom) ?></div>
                <div class="user-role">Membre</div>
            </div>
            <a href="/logout" title="Déconnexion">⏻</a>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">Tableau de bord</div>
                <div class="topbar-sub">Bonjour, <?= esc($nom) ?> 👋</div>
            </div>
            <a href="/gold" class="btn-gold">★ Option Gold — 15% de remise</a>
        </div>

        <div class="content">

            <!-- ALERTE COMPTE INACTIF -->
            <?php if (($compteStatut ?? 'inactive') === 'inactive'): ?>
            <div class="alert alert-amber">
                ⚠ Votre compte est inactif — ajoutez un code promo pour l'activer.
            </div>
            <?php endif; ?>

            <!-- MÉTRIQUES -->
            <div class="metrics">
                <div class="metric m-purple">
                    <div class="metric-icon">📊</div>
                    <div class="metric-val"><?= $imcVal ?></div>
                    <div class="metric-label">IMC actuel</div>
                    <span class="metric-badge" style="background:<?= $catStyle['bg'] ?>;color:<?= $catStyle['color'] ?>">
                        <?= esc($categorieImc) ?>
                    </span>
                </div>
                <div class="metric m-green">
                    <div class="metric-icon">⚖️</div>
                    <div class="metric-val"><?= esc($poids) ?></div>
                    <div class="metric-label">Poids (kg) · <?= esc($taille) ?> m</div>
                </div>
                <div class="metric m-amber">
                    <div class="metric-icon">💰</div>
                    <div class="metric-val"><?= $solde ?> <span style="font-size:16px;font-weight:400">€</span></div>
                    <div class="metric-label">Solde porte-monnaie</div>
                </div>
                <div class="metric m-blue">
                    <div class="metric-icon">🎯</div>
                    <div class="metric-val"><?= $nbObj ?><span style="font-size:16px;font-weight:400;color:var(--muted)">/3</span></div>
                    <div class="metric-label">Objectifs actifs</div>
                </div>
            </div>

            <!-- IMC + OBJECTIFS -->
            <div class="row2">

                <!-- Jauge IMC -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:rgba(124,110,245,0.15)">📈</span>
                            Indice de masse corporelle
                        </span>
                        <span style="font-size:11px;color:var(--muted)">Dernière mesure</span>
                    </div>
                    <div class="card-body">
                        <?php if ($imc !== null): ?>
                        <div style="display:flex;align-items:flex-end;gap:12px;margin-bottom:4px">
                            <span class="imc-big" style="color:<?= $catStyle['color'] ?>"><?= $imcVal ?></span>
                            <span class="badge" style="background:<?= $catStyle['bg'] ?>;color:<?= $catStyle['color'] ?>;margin-bottom:8px">
                                <?= esc($categorieImc) ?>
                            </span>
                        </div>
                        <div class="imc-track">
                            <div class="imc-fill"></div>
                            <div class="imc-needle" id="imc-needle" style="left:<?= $progression ?>%"></div>
                        </div>
                        <div class="imc-zones">
                            <span>Maigreur</span>
                            <span>Normal</span>
                            <span>Surpoids</span>
                            <span>Obésité</span>
                        </div>
                        <div class="imc-stats">
                            <div>
                                <div class="imc-stat-label">Poids actuel</div>
                                <div class="imc-stat-val"><?= esc($poids) ?> kg</div>
                            </div>
                            <div>
                                <div class="imc-stat-label">Taille</div>
                                <div class="imc-stat-val"><?= esc($taille) ?> m</div>
                            </div>
                            <div>
                                <div class="imc-stat-label">IMC idéal</div>
                                <div class="imc-stat-val" style="color:var(--green)">22.0</div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div style="text-align:center;padding:24px 0;color:var(--muted)">
                            <div style="font-size:32px;margin-bottom:8px">📏</div>
                            <div style="font-size:13px;margin-bottom:12px">Aucune mesure enregistrée</div>
                            <a href="/profil/mesure" style="color:var(--accent2);font-size:12px;text-decoration:none">
                                + Ajouter ma mesure →
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Objectifs -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:var(--amber-bg)">🎯</span>
                            Mes objectifs
                        </span>
                        <a href="/profil/objectifs" class="card-link">Modifier →</a>
                    </div>
                    <div class="card-body">
                        <?php foreach ($objectifs as $obj): ?>
                        <div class="obj-item">
                            <div class="obj-dot" style="background:var(--green)"></div>
                            <span class="obj-label">
                                <?= esc($asString($obj['libelle'] ?? $obj['objectif_libelle'] ?? null, 'Objectif')) ?>
                            </span>
                            <span class="badge badge-green">Actif</span>
                        </div>
                        <?php endforeach; ?>

                        <?php for ($i = count($objectifs); $i < 3; $i++): ?>
                        <div class="obj-item">
                            <div class="obj-dot" style="background:var(--muted)"></div>
                            <span class="obj-label obj-empty">Emplacement libre</span>
                            <span class="badge badge-muted">Vide</span>
                        </div>
                        <?php endfor; ?>

                        <a href="/profil/objectifs" style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:14px;font-size:11px;color:var(--accent2);text-decoration:none;padding:8px;border:1px dashed rgba(124,110,245,0.3);border-radius:8px;transition:all 0.2s" onmouseover="this.style.background='rgba(124,110,245,0.05)'" onmouseout="this.style.background='transparent'">
                            + Gérer mes objectifs
                        </a>
                    </div>
                </div>

            </div>

            <!-- RÉGIMES + SPORTS -->
            <div class="row3">

                <!-- Régimes -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:var(--green-bg)">🥗</span>
                            Régimes suggérés
                        </span>
                        <a href="/regimes" class="card-link">Voir tout →</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($regimes)): ?>
                            <?php foreach ($regimes as $i => $regime): ?>
                            <div class="regime-item">
                                <div class="ri-icon"><?= $regimeIcons[$i % count($regimeIcons)] ?></div>
                                <div style="flex:1;min-width:0">
                                    <div class="ri-name">
                                        <?= esc($asString($regime['libelle'] ?? $regime['nom'] ?? null, 'Régime')) ?>
                                    </div>
                                    <div class="ri-sub">
                                        <?php if (isset($regime['duree_jours'])): ?>
                                            <?= esc($asString($regime['duree_jours'])) ?> jours
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (isset($regime['prix'])): ?>
                                <span class="ri-price"><?= number_format((float)$regime['prix'], 2) ?> €</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:20px 0;color:var(--muted);font-size:12px">
                                Aucun régime disponible
                            </div>
                        <?php endif; ?>
                        <a href="/regimes/pdf" class="pdf-btn">
                            📄 Exporter mon plan nutritionnel en PDF
                        </a>
                    </div>
                </div>

                <!-- Sports -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-title">
                            <span class="ct-icon" style="background:rgba(124,110,245,0.12)">🏃</span>
                            Sports recommandés
                        </span>
                        <a href="/sports" class="card-link">Voir tout →</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($sports)): ?>
                            <?php foreach ($sports as $i => $sport): ?>
                            <div class="sport-item">
                                <div class="si-icon"><?= $sportIcons[$i % count($sportIcons)] ?></div>
                                <div style="flex:1">
                                    <div style="font-size:13px;font-weight:500">
                                        <?= esc($asString($sport['nom'] ?? null)) ?>
                                    </div>
                                    <div style="font-size:11px;color:var(--muted)">Activité cardio</div>
                                </div>
                                <span class="si-cal">
                                    <?= esc($asString($sport['calories_par_heure'] ?? null)) ?> kcal/h
                                </span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:20px 0;color:var(--muted);font-size:12px">
                                Aucun sport disponible
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- PORTE-MONNAIE -->
            <div class="card">
                <div class="card-head">
                    <span class="card-title">
                        <span class="ct-icon" style="background:var(--amber-bg)">💰</span>
                        Porte-monnaie
                    </span>
                    <a href="/porte-monnaie" class="card-link">Historique complet →</a>
                </div>
                <div class="card-body">
                    <div class="wallet-grid">
                        <div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:2px">Solde disponible</div>
                            <div class="wallet-solde"><?= $solde ?> €</div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:8px">Entrer un code promo</div>
                            <div class="code-wrap">
                                <input type="text" id="code-input" placeholder="ex: PROMO10" class="code-input">
                                <button onclick="validerCode()" class="code-btn">Valider</button>
                            </div>
                            <div id="code-msg" style="font-size:11px;margin-top:8px;min-height:16px"></div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--muted);margin-bottom:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.06em">
                                Dernières transactions
                            </div>
                            <div id="tx-list">
                                <div class="tx-item" style="color:var(--muted);font-size:12px">
                                    Aucune transaction récente.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->
</div><!-- /layout -->

<script>
function validerCode() {
    const code = document.getElementById('code-input').value.trim();
    const msg  = document.getElementById('code-msg');

    if (!code) {
        msg.textContent = '⚠ Veuillez entrer un code.';
        msg.style.color = 'var(--amber)';
        return;
    }

    msg.textContent = 'Vérification…';
    msg.style.color = 'var(--muted2)';

    fetch('/porte-monnaie/code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            code: code,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            msg.textContent = '✓ ' + data.message;
            msg.style.color = 'var(--green)';
            // Mettre à jour le solde affiché
            document.querySelectorAll('.wallet-solde, .metric.m-amber .metric-val').forEach(el => {
                el.textContent = parseFloat(data.nouveau_solde).toFixed(2) + ' €';
            });
            // Ajouter la transaction dans la liste
            const txList = document.getElementById('tx-list');
            const item = document.createElement('div');
            item.className = 'tx-item';
            item.innerHTML = `<span style="font-size:12px;color:var(--text);flex:1">Code ${code}</span><span class="tx-amount tx-plus">+${parseFloat(data.montant ?? 0).toFixed(2)} €</span>`;
            txList.prepend(item);
        } else {
            msg.textContent = '✗ ' + data.message;
            msg.style.color = 'var(--red)';
        }
        document.getElementById('code-input').value = '';
    })
    .catch(() => {
        msg.textContent = '✗ Erreur réseau, réessayez.';
        msg.style.color = 'var(--red)';
    });
}

// Animation entrée aiguille IMC
window.addEventListener('load', () => {
    const needle = document.getElementById('imc-needle');
    if (needle) {
        needle.style.left = '0%';
        setTimeout(() => { needle.style.left = '<?= $progression ?>%'; }, 300);
    }
});
</script>

<?= $this->endSection() ?>