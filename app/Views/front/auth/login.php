<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <aside class="auth-side">
                <div>
                    <div class="auth-logo"><span>💪</span> FitIMC</div>
                    <div class="auth-kicker">Tableau de bord</div>
                    <h2>Reprenez votre rythme, suivez vos progres.</h2>
                    <p>Accedez a vos objectifs, vos mesures et vos programmes personnalises.</p>
                </div>
                <div class="auth-points">
                    <div class="auth-point">📈 <b>IMC</b> et progression en un coup d'oeil.</div>
                    <div class="auth-point">🥗 Plans adaptes a vos objectifs.</div>
                    <div class="auth-point">💰 Porte-monnaie et activations rapides.</div>
                </div>
            </aside>

            <section class="auth-form">
                <div class="auth-head">
                    <h1>Connexion</h1>
                    <span>Heureux de vous revoir.</span>
                </div>

                <?php $error = session()->getFlashdata('error'); ?>
                <?php if ($error): ?>
                    <div class="auth-error">
                        <?= esc(is_array($error) ? implode(' ', $error) : (string) $error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('login') ?>" class="form-grid">
                    <?= csrf_field() ?>

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
                        <label for="mot_de_passe">Mot de passe</label>
                        <input
                            id="mot_de_passe"
                            name="mot_de_passe"
                            type="password"
                            required
                            autocomplete="current-password">
                    </div>

                    <div class="auth-actions">
                        <button type="submit" class="btn-primary">Se connecter</button>
                        <div class="auth-foot">
                            <span>Pas de compte ?</span>
                            <a href="<?= site_url('inscription') ?>">S'inscrire</a>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</body>
</html>