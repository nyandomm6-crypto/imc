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
$genres = is_array($genres ?? null) ? $genres : [];
$mesure = is_array($mesure ?? null) ? $mesure : null;

$nom = $asString($utilisateur['nom'] ?? null, 'Utilisateur');
$pageTitle = 'Profil';
$pageSubtitle = 'Mettre a jour vos informations';
$activeNav = 'profil';
?>

<?= $this->section('content') ?>

<?php $error = session()->getFlashdata('error'); ?>
<?php $success = session()->getFlashdata('success'); ?>

<?php if ($error): ?>
	<div class="alert alert-amber">⚠ <?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?></div>
<?php elseif ($success): ?>
	<div class="alert" style="background:var(--green-bg);color:var(--green);border-color:rgba(45,212,160,0.3)">✓ <?= esc((string) $success) ?></div>
<?php endif; ?>

<div class="row2">
	<div class="card">
		<div class="card-head">
			<span class="card-title">
				<span class="ct-icon" style="background:rgba(124,110,245,0.15)">👤</span>
				Informations du profil
			</span>
		</div>
		<div class="card-body">
			<form method="post" action="<?= site_url('profil/update') ?>" class="form-grid">
				<?= csrf_field() ?>

				<div class="field">
					<label for="nom">Nom</label>
					<input class="code-input" id="nom" name="nom" type="text" required value="<?= esc((string) (old('nom') ?? $utilisateur['nom'] ?? '')) ?>">
				</div>

				<div class="field">
					<label for="email">Email</label>
					<input class="code-input" id="email" name="email" type="email" required value="<?= esc((string) (old('email') ?? $utilisateur['email'] ?? '')) ?>">
				</div>

				<div class="field">
					<label for="date_naissance">Date de naissance</label>
					<input class="code-input" id="date_naissance" name="date_naissance" type="date" required value="<?= esc((string) (old('date_naissance') ?? $utilisateur['date_naissance'] ?? '')) ?>">
				</div>

				<div class="field">
					<label for="genre_id">Genre</label>
					<select class="code-input" id="genre_id" name="genre_id" required>
						<option value="">-- Choisir --</option>
						<?php foreach ($genres as $genre): ?>
							<option value="<?= esc((string) ($genre['id'] ?? '')) ?>" <?= (string) (old('genre_id') ?? $utilisateur['genre_id'] ?? '') === (string) ($genre['id'] ?? '') ? 'selected' : '' ?>>
								<?= esc($asString($genre['nom'] ?? null)) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="field">
					<label for="mot_de_passe">Nouveau mot de passe</label>
					<input class="code-input" id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="new-password">
				</div>

				<div class="field">
					<label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
					<input class="code-input" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" type="password" autocomplete="new-password">
				</div>

				<button class="code-btn" type="submit">Mettre a jour</button>
			</form>
		</div>
	</div>

	<div class="card">
		<div class="card-head">
			<span class="card-title">
				<span class="ct-icon" style="background:var(--green-bg)">📏</span>
				Mesure utilisateur
			</span>
		</div>
		<div class="card-body">
			<form method="post" action="<?= site_url('profil/mesure') ?>" class="form-grid">
				<?= csrf_field() ?>

				<div class="field">
					<label for="poids_kg">Poids (kg)</label>
					<input class="code-input" id="poids_kg" name="poids_kg" type="number" step="0.1" min="1" required value="<?= esc((string) (old('poids_kg') ?? $mesure['poids_kg'] ?? '')) ?>">
				</div>

				<div class="field">
					<label for="taille_m">Taille (m)</label>
					<input class="code-input" id="taille_m" name="taille_m" type="number" step="0.01" min="0.5" required value="<?= esc((string) (old('taille_m') ?? $mesure['taille_m'] ?? '')) ?>">
				</div>

				<button class="code-btn" type="submit">Enregistrer la mesure</button>
			</form>
		</div>
	</div>
</div>

<?= $this->endSection() ?>