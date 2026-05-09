<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Connexion</title>
</head>
<body>
	<main>
		<h1>Connexion</h1>

		<?php $error = session()->getFlashdata('error'); ?>
		<?php if ($error): ?>
			<p style="color: #c00;">
				<?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
			</p>
		<?php endif; ?>

		<form method="post" action="<?= site_url('login') ?>">
			<?= csrf_field() ?>

			<div>
				<label for="email">Email</label>
				<input
					id="email"
					name="email"
					type="email"
					required
					autocomplete="email"
					value="<?= esc((string) (old('email') ?? '')) ?>"
				>
			</div>

			<div>
				<label for="mot_de_passe">Mot de passe</label>
				<input
					id="mot_de_passe"
					name="mot_de_passe"
					type="password"
					required
					autocomplete="current-password"
				>
			</div>

			<button type="submit">Se connecter</button>
		</form>
       <a href="<?= site_url('inscription') ?>">S'inscrire</a>
	</main>
</body>
</html>
