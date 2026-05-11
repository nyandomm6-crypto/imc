<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'FitIMC') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/front-layout.css') ?>">
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

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$nom = $asString($nom ?? ($utilisateur['nom'] ?? null), 'Utilisateur');
$parts = explode(' ', trim($nom));
$initiales = strtoupper(substr($parts[0] ?? 'U', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
$pageTitle = $asString($pageTitle ?? null, 'FitIMC');
$pageSubtitle = $asString($pageSubtitle ?? null, 'Bonjour, ' . $nom . ' 👋');
$activeNav = $asString($activeNav ?? null, '');
if ($activeNav === '') {
    $path = trim(uri_string(), '/');
    $path = preg_replace('#^index\.php/?#', '', $path);
    $activeNav = match (true) {
        $path === '' || $path === 'dashboard' => 'dashboard',
        str_contains($path, 'profil/objectifs') => 'objectifs',
        str_contains($path, 'profil') => 'profil',
        str_contains($path, 'regimes') => 'regimes',
        str_contains($path, 'sports') => 'sports',
        str_contains($path, 'porte-monnaie') => 'porte-monnaie',
        str_contains($path, 'options') => 'options',
        default => 'dashboard',
    };
}
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
            <a class="nav-item<?= $activeNav === 'dashboard' ? ' active' : '' ?>" href="/dashboard">
                <span class="ni">⊞</span> Dashboard
            </a>
            <a class="nav-item<?= $activeNav === 'profil' ? ' active' : '' ?>" href="/profil">
                <span class="ni">◎</span> Mon profil
            </a>
            <a class="nav-item<?= $activeNav === 'objectifs' ? ' active' : '' ?>" href="/profil/objectifs">
                <span class="ni">◈</span> Objectifs
            </a>

            <div class="nav-section">Nutrition</div>
            <a class="nav-item<?= $activeNav === 'regimes' ? ' active' : '' ?>" href="/regimes">
                <span class="ni">🥗</span> Régimes
            </a>
            <a class="nav-item<?= $activeNav === 'sports' ? ' active' : '' ?>" href="/sports">
                <span class="ni">🏃</span> Sports
            </a>

            <div class="nav-section">Compte</div>
            <a class="nav-item<?= $activeNav === 'porte-monnaie' ? ' active' : '' ?>" href="/porte-monnaie">
                <span class="ni">◈</span> Porte-monnaie
            </a>
            <a class="nav-item<?= $activeNav === 'options' ? ' active' : '' ?>" href="/options">
                <span class="ni">🛍️</span> Options
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar"><?= esc($initiales) ?></div>
            <div>
                <div class="user-name"><?= esc(strlen($nom) > 14 ? substr($nom, 0, 14) . '…' : $nom) ?></div>
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
                <div class="topbar-title"><?= esc($pageTitle) ?></div>
                <div class="topbar-sub"><?= esc($pageSubtitle) ?></div>
            </div>
            <a href="/options" class="btn-options">🛍️ Voir les options disponibles</a>
        </div>

        <div class="content">
            <?= $this->renderSection('content') ?>
        </div>
    </div><!-- /main -->
</div><!-- /layout -->

<?= $this->renderSection('scripts') ?>
</body>
</html>