<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>
<body>
    <?php $genres = $genres ?? []; ?>

    <div class="auth-shell">
        <div class="auth-card">
            <aside class="auth-side">
                <div>
                    <div class="auth-logo"><span>💪</span> FitIMC</div>
                    <div class="auth-kicker">Inscription</div>
                    <h2>Commencez votre parcours sante.</h2>
                    <p>Créez votre compte pour suivre vos mesures et recevoir des conseils adaptés.</p>
                </div>
                <div class="auth-points">
                    <div class="auth-point">✅ Objectifs clairs et mesurables.</div>
                    <div class="auth-point">⚡ Tableaux de bord rapides a consulter.</div>
                    <div class="auth-point">🔒 Donnees protegees et securisees.</div>
                </div>
            </aside>

            <section class="auth-form">
                <div class="auth-head">
                    <h1>Inscription</h1>
                    <span>Quelques informations pour demarrer.</span>
                </div>

                <?php $error = session()->getFlashdata('error'); ?>
                <?php if ($error): ?>
                    <div class="auth-error">
                        <?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('inscription') ?>" class="form-grid">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="nom">Nom</label>
                        <input
                            id="nom"
                            name="nom"
                            type="text"
                            required
                            value="<?= esc((string) (old('nom') ?? '')) ?>">
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autocomplete="email"
                            value="<?= esc((string) (old('email') ?? '')) ?>">
                    </div>

                    <div class="field">
                        <label for="date_naissance">Date de naissance</label>
                        <input
                            id="date_naissance"
                            name="date_naissance"
                            type="date"
                            required
                            value="<?= esc((string) (old('date_naissance') ?? '')) ?>">
                    </div>

                    <div class="field">
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

                    <div class="field">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input
                            id="mot_de_passe"
                            name="mot_de_passe"
                            type="password"
                            required
                            autocomplete="new-password">
                    </div>

                    <div class="field">
                        <label for="confirmation_mot_de_passe">Confirmation mot de passe</label>
                        <input
                            id="confirmation_mot_de_passe"
                            name="confirmation_mot_de_passe"
                            type="password"
                            required
                            autocomplete="new-password">
                    </div>

                    <div class="auth-actions">
                        <button type="submit" class="btn-primary">S'inscrire</button>
                        <div class="auth-foot">
                            <span>Deja inscrit ?</span>
                            <a href="<?= site_url('login') ?>">Se connecter</a>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</body>

</html>