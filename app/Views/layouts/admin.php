<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'FitIMC Admin') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin-layout.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var string|null $nom */
/** @var string|null $pageTitle */
/** @var string|null $pageSubtitle */
/** @var string|null $activeNav */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) {
        return $value;
    }
    if (is_int($value) || is_float($value) || is_numeric($value)) {
        return (string) $value;
    }
    return $fallback;
};

// ── Même logique que main.php ──
// Priorité : variable $nom passée → $utilisateur['nom'] → session → fallback
$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];

$nom = $asString(
    $nom
    ?? ($utilisateur['nom'] ?? null)
    ?? session()->get('user_nom')
    ?? null,
    'Administrateur'
);

$parts     = explode(' ', trim($nom));
$initiales = strtoupper(
    substr($parts[0] ?? 'A', 0, 1) .
    (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
);

$pageTitle    = $asString($pageTitle    ?? null, 'Dashboard Admin');
$pageSubtitle = $asString($pageSubtitle ?? null, 'Vue d\'ensemble — ' . date('d/m/Y'));
$activeNav    = $asString($activeNav    ?? null, '');

if ($activeNav === '') {
    $path      = trim(uri_string(), '/');
    $path      = (string) preg_replace('#^index\.php/?#', '', $path);
    $activeNav = match (true) {
        str_contains($path, 'admin/dashboard') || $path === 'admin' => 'dashboard',
        str_contains($path, 'admin/utilisateurs')                    => 'utilisateurs',
        str_contains($path, 'admin/regimes')                         => 'regimes',
        str_contains($path, 'admin/aliments')                        => 'aliments',
        str_contains($path, 'admin/sports')                          => 'sports',
        str_contains($path, 'admin/codes')                           => 'codes',
        str_contains($path, 'admin/abonnements')                     => 'abonnements',
        str_contains($path, 'admin/offres')                          => 'offres',
        default                                                       => 'dashboard',
    };
}
?>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">💪</div>
            <span class="logo-text">FitIMC</span>
            <span class="logo-badge">Admin</span>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section">Principal</div>
            <a class="nav-item<?= $activeNav === 'dashboard'     ? ' active' : '' ?>"
               href="<?= base_url('admin/dashboard') ?>">
                <span class="ni">⊞</span> Dashboard
            </a>
            <a class="nav-item<?= $activeNav === 'utilisateurs'  ? ' active' : '' ?>"
               href="<?= base_url('admin/utilisateurs') ?>">
                <span class="ni">👥</span> Utilisateurs
            </a>

            <div class="nav-section">Nutrition</div>
            <a class="nav-item<?= $activeNav === 'regimes'       ? ' active' : '' ?>"
               href="<?= base_url('admin/regimes') ?>">
                <span class="ni">🥗</span> Régimes
            </a>
            <a class="nav-item<?= $activeNav === 'aliments'      ? ' active' : '' ?>"
               href="<?= base_url('admin/aliments') ?>">
                <span class="ni">🍎</span> Aliments
            </a>
            <a class="nav-item<?= $activeNav === 'sports'        ? ' active' : '' ?>"
               href="<?= base_url('admin/sports') ?>">
                <span class="ni">🏃</span> Sports
            </a>

            <div class="nav-section">Financier</div>
            <a class="nav-item<?= $activeNav === 'codes'         ? ' active' : '' ?>"
               href="<?= base_url('admin/codes') ?>">
                <span class="ni">🔑</span> Codes promo
            </a>

        </nav>

        <div class="sidebar-user">
            <div class="user-avatar"><?= esc($initiales) ?></div>
            <div>
                <div class="user-name">
                    <?= esc(strlen($nom) > 14 ? substr($nom, 0, 14) . '…' : $nom) ?>
                </div>
                <div class="user-role">Administrateur</div>
            </div>
            <a href="<?= base_url('/logout') ?>" title="Déconnexion">⏻</a>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title"><?= esc($pageTitle) ?></div>
                <div class="topbar-sub"><?= esc($pageSubtitle) ?></div>
            </div>
            <a href="<?= base_url('/') ?>" class="btn-front" target="_blank">
                ↗ Voir le site
            </a>
        </div>

        <div class="content">

            <?= $this->renderSection('content') ?>

        </div>
    </div><!-- /main -->
</div><!-- /layout -->

<?= $this->renderSection('scripts') ?>
</body>
</html>