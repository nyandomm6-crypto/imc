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

				<?php $error = session()->getFlashdata('error'); ?>
				<?php if ($error): ?>
					<div class="auth-error">
						<?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
					</div>
				<?php endif; ?>

				<form method="post" action="<?= site_url('inscription/etape-2') ?>" class="form-grid">
					<?= csrf_field() ?>

					<div class="field">
						<label for="objectif">Objectif principal</label>
						<select id="objectif" name="objectif" required>
							<option value="">-- Choisir --</option>
							<option value="perte">Perte de poids</option>
							<option value="maintien">Maintien</option>
							<option value="prise">Prise de masse</option>
						</select>
					</div>

					<div class="field">
						<label for="niveau">Niveau d'activite</label>
						<select id="niveau" name="niveau" required>
							<option value="">-- Choisir --</option>
							<option value="faible">Faible</option>
							<option value="modere">Modere</option>
							<option value="actif">Actif</option>
						</select>
					</div>

					<div class="field">
						<label for="poids_kg">Poids (kg)</label>
						<input
							id="poids_kg"
							name="poids_kg"
							type="number"
							step="0.1"
							min="1"
							value="<?= esc((string) (old('poids_kg') ?? '')) ?>">
					</div>

					<div class="field">
						<label for="taille_m">Taille (m)</label>
						<input
							id="taille_m"
							name="taille_m"
							type="number"
							step="0.01"
							min="0.5"
							value="<?= esc((string) (old('taille_m') ?? '')) ?>">
					</div>

					<div class="field">
						<label for="date_mesure">Date de la mesure</label>
						<input
							id="date_mesure"
							name="date_mesure"
							type="date"
							value="<?= esc((string) (old('date_mesure') ?? '')) ?>">
					</div>

					<div class="auth-actions">
						<button type="submit" class="btn-primary" name="action" value="finish">Terminer</button>
						<button type="submit" class="btn-primary" name="action" value="skip">Passer</button>
					</div>
				</form>
			</section>
		</div>
	</div>
</body>
</html>
