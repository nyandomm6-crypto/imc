<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inscription - Etape 2</title>
	<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>
<body>
	<div class="auth-shell">
		<div class="auth-card">
			<aside class="auth-side">
				<div>
					<div class="auth-logo"><span>💪</span> FitIMC</div>
					<div class="auth-kicker">Etape 2</div>
					<h2>Encore une etape avant votre tableau de bord.</h2>
					<p>Completez vos informations pour des recommandations plus precises.</p>
				</div>
				<div class="auth-points">
					<div class="auth-point">🎯 Objectifs personnalises.</div>
					<div class="auth-point">📊 Suivi adapte a votre profil.</div>
					<div class="auth-point">✨ Conseils evolutifs et ciblés.</div>
				</div>
			</aside>

			<section class="auth-form">
				<div class="auth-head">
					<h1>Inscription - Etape 2</h1>
					<span>Ajoutez vos preferences pour continuer.</span>
				</div>

				<div class="auth-error" style="display:none" aria-hidden="true"></div>

				<div class="auth-actions">
					<a class="btn-primary" href="<?= site_url('login') ?>">Retour a la connexion</a>
					<a class="btn-secondary" href="<?= site_url('dashboard') ?>">Passer</a>
				</div>
			</section>
		</div>
	</div>
</body>
</html>
