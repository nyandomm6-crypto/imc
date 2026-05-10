<?= $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<string, mixed>|null $mesure */
/** @var array<int, array<string, mixed>>|null $objectifs */
/** @var array<int, array<string, mixed>>|null $regimes */
/** @var array<int, array<string, mixed>>|null $sports */
/** @var array<int, array<string, mixed>>|null $transactions */
/** @var float|int|null $imc */
/** @var int|float|null $imcProgression */
/** @var float|int|null $soldeCompte */
/** @var string|null $categorieImc */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$imcTexte = 'N/A';
if ($imc !== null) {
	$imcTexte = number_format((float) $imc, 2, ',', ' ');
}

$taille = 'N/A';
if (isset($mesure['taille_m'])) {
	$taille = number_format((float) $mesure['taille_m'], 2, ',', ' ');
}

$poids = 'N/A';
if (isset($mesure['poids_kg'])) {
	$poids = number_format((float) $mesure['poids_kg'], 2, ',', ' ');
}

$imcClass = 'chip';
$imcIcon  = '✦';
if ($imc !== null) {
	if ($imc < 18.5)       { $imcClass = 'chip chip-warn'; $imcIcon = '▽'; }
	elseif ($imc < 25)     { $imcClass = 'chip';           $imcIcon = '✔'; }
	elseif ($imc < 30)     { $imcClass = 'chip chip-warn'; $imcIcon = '△'; }
	else                   { $imcClass = 'chip chip-warn'; $imcIcon = '▲'; }
}

$nom = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$imc = is_numeric($imc ?? null) ? (float) $imc : null;
$categorieImc = $asString($categorieImc ?? null, 'Inconnue');
$imcVal = $imc !== null ? number_format($imc, 1) : '--';
$progression = is_numeric($imcProgression ?? null) ? (int) $imcProgression : 0;
$poids = $asString($mesure['poids_kg'] ?? null, '--');
$taille = $asString($mesure['taille_m'] ?? null, '--');
$solde = number_format((float) ($soldeCompte ?? 0), 2);
$nbObj = count($objectifs);

$catStyle = match(true) {
    $imc !== null && $imc < 18.5 => ['color' => 'var(--blue)',  'bg' => 'var(--blue-bg)'],
    $imc !== null && $imc < 25   => ['color' => 'var(--green)', 'bg' => 'var(--green-bg)'],
    $imc !== null && $imc < 30   => ['color' => 'var(--amber)', 'bg' => 'var(--amber-bg)'],
    $imc !== null                 => ['color' => 'var(--red)',   'bg' => 'var(--red-bg)'],
    default                       => ['color' => 'var(--muted)', 'bg' => 'var(--bg3)'],
};

$sportIcons = ['🏃','🏊','🚴','🧘','🏋️','⛹️','🤸','🥊'];
$regimeIcons = ['🥗','🐟','🥦','🍗','🫐','🥑'];

$pageTitle = 'Tableau de bord';
$pageSubtitle = 'Bonjour, ' . $nom . ' 👋';
$activeNav = 'dashboard';
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Tableau de bord nutrition personnalise — IMC, regimes et sports recommandes.">
	<title>NutriDash · Tableau de bord</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<main class="container">

		<section class="hero">
			<div>
				<p class="eyebrow">Tableau de bord nutrition</p>
				<?php
				$utilisateurNom = 'utilisateur';
				if (isset($utilisateur['nom'])) {
					$utilisateurNom = $utilisateur['nom'];
				}
				?>
				<h1>Bonjour, <em><?= $escape($utilisateurNom) ?></em></h1>
				<p class="lead">
					Voici vos indicateurs de sante, vos objectifs actifs et vos recommandations
					personnalisees en matière de regime et d'activite physique.
				</p>

				<div class="stats-row">
					<div class="stat">
						<span class="stat-val"><?= $taille ?> m</span>
						<span class="stat-lbl">Taille</span>
					</div>
					<div class="stat">
						<span class="stat-val"><?= $poids ?> kg</span>
						<span class="stat-lbl">Poids</span>
					</div>
					<div class="stat">
						<span class="stat-val"><?= count($objectifs) ?></span>
						<span class="stat-lbl">Objectifs actifs</span>
					</div>
				</div>
			</div>

			<div class="actions">
				<a class="button secondary" href="/">← Retour accueil</a>
				<a class="button primary"    href="/profil">Mon profil</a>
			</div>
		</section>

		<!-- Suggestion personalisee -->
		<?php if (isset($suggestion) && $suggestion !== null) : ?>
		<article class="card full" aria-label="Suggestion personnalisee">
			<div class="section-label">Suggestion personnalisee</div>
			<div class="list">
				<div class="item">
					<div class="item-icon">💡</div>
					<div class="item-body">
						<?php
						if (isset($suggestion['regime']) && $suggestion['regime'] !== null && isset($suggestion['regime']['libelle'])) {
							echo '<p class="item-title">' . $escape($suggestion['regime']['libelle']) . '</p>';
						} else {
							echo '<p class="item-title">Regime recommande</p>';
						}

						if (isset($suggestion['prix'])) {
							$prixAff = number_format((float) $suggestion['prix'], 2, ',', ' ');
							echo '<p class="item-desc">Prix pour la duree proposee&nbsp;: ' . $prixAff . ' FCFA</p>';
						} else {
							echo '<p class="item-desc">Prix non disponible</p>';
						}

						if (isset($suggestion['estimation']) && $suggestion['estimation'] === true) {
							if (isset($suggestion['raison'])) {
								echo '<p class="muted">Note&nbsp;: prix estime — ' . $escape($suggestion['raison']) . '</p>';
							} else {
								echo '<p class="muted">Note&nbsp;: prix estime</p>';
							}
						} else {
							if (isset($suggestion['raison'])) {
								echo '<p class="muted">' . $escape($suggestion['raison']) . '</p>';
							} else {
								echo '<p class="muted">Information complementaire non disponible</p>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</article>
		<?php endif; ?>

		<section class="grid" aria-label="Vue d'ensemble">

			<!-- IMC -->
			<article class="card metric" aria-label="IMC actuel">
				<h2>IMC actuel</h2>
				<p class="metric-value"><?= $imcTexte ?></p>
				<div class="<?= $imcClass ?>"><?= $imcIcon ?> <?= $escape($categorieImc) ?></div>
				<div class="progress" role="progressbar" aria-valuenow="<?= (int) $imcProgression ?>" aria-valuemin="0" aria-valuemax="100">
					<span style="width:<?= (int) $imcProgression ?>%;"></span>
				</div>
				<p class="muted" style="margin-top:14px;">
					Indice de masse corporelle calcule à partir de votre taille et poids actuels.
				</p>
			</article>

			<!-- Portefeuille -->
			<article class="card metric" aria-label="Solde porte-monnaie">
				<h2>Solde porte-monnaie</h2>
				<p class="metric-value">
					<?= number_format((float) $soldeCompte, 2, ',', ' ') ?>
					<small>FCFA</small>
				</p>
				<div class="chip chip-neutral"><?= $escape($compteStatut) ?></div>
				<p class="muted">Accedez aux transactions et codes promo depuis le porte-monnaie.</p>
			</article>

			<!-- Objectifs selectionnes -->
			<article class="card wide" aria-label="Objectifs selectionnes">
				<div class="section-label">Objectifs selectionnes</div>
				<div class="list">
					<?php if (!empty($objectifs)) : ?>
						<?php foreach ($objectifs as $objectif) : ?>
							<div class="item">
								<div class="item-icon">🎯</div>
								<div class="item-body">
									<p class="item-title">
										<?php
										if (isset($objectif['libelle'])) {
											echo $escape($objectif['libelle']);
										} else {
											echo 'Objectif';
										}
										?>
									</p>
									<p class="item-desc">
										<?php
										if (isset($objectif['valeur_cible']) && $objectif['valeur_cible'] !== null) {
											echo 'Cible&nbsp;: ' . $escape($objectif['valeur_cible']);
										} else {
											echo 'Objectif actif — aucune valeur cible precisee.';
										}
										?>
									</p>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="item">
							<div class="item-icon">⊕</div>
							<div class="item-body">
								<p class="item-title">Aucun objectif selectionne</p>
								<p class="item-desc">Rendez-vous dans votre profil pour definir vos priorites.</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</article>

			<!-- Regimes suggeres -->
			<article class="card full" aria-label="Regimes suggeres">
				<div class="section-label">Regimes suggeres</div>
				<div class="list">
					<?php if (!empty($regimes)) : ?>
						<?php foreach ($regimes as $regime) : ?>
							<div class="item">
								<?php
								if (isset($regime['libelle'])) {
									$regimeLibelle = $regime['libelle'];
								} else {
									$regimeLibelle = '';
								}
								?>
								<div class="item-icon"><?= $getRegimeEmoji($regimeLibelle) ?></div>
								<div class="item-body">
									<p class="item-title">
										<?php
										if (isset($regime['libelle'])) {
											echo $escape($regime['libelle']);
										} else {
											echo 'Regime';
										}
										?>
									</p>
									<p class="item-desc">
										<?php
										if (isset($regime['description'])) {
											echo $escape($regime['description']);
										} else {
											echo 'Suggestion personnalisee basee sur votre profil et votre IMC.';
										}
										?>
									</p>
								</div>
								<?php if (isset($regime['niveau'])) : ?>
									<span class="item-badge"><?= $escape($regime['niveau']) ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="item">
							<div class="item-icon">🥦</div>
							<div class="item-body">
								<p class="item-title">Aucun regime disponible</p>
								<p class="item-desc">Ajoutez des regimes dans la base de donnees pour les voir apparaître ici.</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</article>

			<!-- Sports recommandes -->
			<article class="card full" aria-label="Sports recommandes">
				<div class="section-label">Sports recommandes</div>
				<div class="list">
					<?php if (!empty($sports)) : ?>
						<?php foreach ($sports as $sport) : ?>
							<div class="item">
								<?php
								if (isset($sport['nom'])) {
									$sportNom = $sport['nom'];
								} else {
									$sportNom = '';
								}
								?>
								<div class="item-icon"><?= $getSportEmoji($sportNom) ?></div>
								<div class="item-body">
									<p class="item-title"><?php
										if (isset($sport['nom'])) {
											echo $escape($sport['nom']);
										} else {
											echo 'Sport';
										}
										?></p>
									<p class="item-desc">
										<?php
										if (isset($sport['description'])) {
											echo $escape($sport['description']);
										} else {
											echo 'Activite recommandee en coherence avec vos objectifs.';
										}
										?>
									</p>
								</div>
								<span class="item-badge">
									<?php
									if (isset($sport['calories_par_heure'])) {
										echo $escape($sport['calories_par_heure']);
									} else {
										echo '—';
									}
									?> kcal/h
								</span>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="item">
							<div class="item-icon">🏅</div>
							<div class="item-body">
								<p class="item-title">Aucun sport disponible</p>
								<p class="item-desc">Ajoutez des sports dans la base de donnees pour alimenter cette section.</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</article>

		</section>
	</main>
</body>
</html>
