<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>

    <?php $genres = $genres ?? []; ?>

    <h1>Inscription</h1>

    <?php $error = session()->getFlashdata('error'); ?>
    <?php if ($error): ?>
        <p style="color: #c00;">
            <?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
        </p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('inscription') ?>">
        <?= csrf_field() ?>

        <div>
            <label for="nom">Nom</label>
            <input
                id="nom"
                name="nom"
                type="text"
                required
                value="<?= esc((string) (old('nom') ?? '')) ?>">
        </div>

        <div>
            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                required
                autocomplete="email"
                value="<?= esc((string) (old('email') ?? '')) ?>">
        </div>

        <div>
            <label for="date_naissance">Date de naissance</label>
            <input
                id="date_naissance"
                name="date_naissance"
                type="date"
                required
                value="<?= esc((string) (old('date_naissance') ?? '')) ?>">
        </div>

        <div>
            <label for="genre_id">Genre</label>
            <select id="genre_id" name="genre_id" required>
                <option value="">-- Choisir --</option>

                <?php foreach ($genres as $genre): ?>
                    <option
                        value="<?= esc((string) ($genre['id'] ?? '')) ?>"
                        <?= old('genre_id') == ($genre['id'] ?? null) ? 'selected' : '' ?>>
                        <?= esc((string) ($genre['nom'] ?? '')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div>
            <label for="mot_de_passe">Mot de passe</label>
            <input
                id="mot_de_passe"
                name="mot_de_passe"
                type="password"
                required
                autocomplete="new-password">
        </div>

        <div>
            <label for="confirmation_mot_de_passe">
                Confirmation mot de passe
            </label>

            <input
                id="confirmation_mot_de_passe"
                name="confirmation_mot_de_passe"
                type="password"
                required
                autocomplete="new-password">
        </div>

        <button type="submit">S'inscrire</button>

    </form>
    <a href="<?= site_url('login') ?>">Déjà inscrit ? Se connecter</a>

</body>

</html>