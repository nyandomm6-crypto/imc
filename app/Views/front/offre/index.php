<?php $this->extend('layouts/main') ?>

<?php
/** @var array<string, mixed>|null $utilisateur */
/** @var array<int, array<string, mixed>>|null $offres */
/** @var array<int, array<string, mixed>>|null $demandesUtilisateur */
/** @var bool $isGold */

$asString = static function ($value, string $fallback = ''): string {
    if (is_string($value)) return $value;
    if (is_int($value) || is_float($value) || is_numeric($value)) return (string) $value;
    return $fallback;
};

$utilisateur = is_array($utilisateur ?? null) ? $utilisateur : [];
$offres = is_array($offres ?? null) ? $offres : [];
$demandesUtilisateur = is_array($demandesUtilisateur ?? null) ? $demandesUtilisateur : [];
$isGold = $isGold ?? false;

$pageTitle = 'Nos Offres';
$pageSubtitle = 'Accédez à des régimes et sports personnalisés';
$activeNav = 'offres';
?>

<?= $this->section('content') ?>

<style>
.offres-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    max-width: 1000px;
}

.btn-suggestions {
    background: linear-gradient(135deg, #6b5fe0 0%, #7c6ff5 100%);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    align-self: flex-start;
    margin-bottom: 20px;
}

.btn-suggestions:hover {
    background: linear-gradient(135deg, #7c6ff5 0%, #8d7dff 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(107, 95, 224, 0.3);
}

.gold-badge {
    display: inline-block;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #333;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-bottom: 12px;
}

.offres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.offre-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: 0.2s;
}

.offre-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(107, 95, 224, 0.1);
}

.offre-type {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
    background: rgba(107, 95, 224, 0.1);
    color: var(--accent);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.offre-titre {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}

.offre-description {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.5;
}

.offre-prix {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin: 8px 0;
}

.prix-normal {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
}

.prix-gold {
    font-size: 16px;
    font-weight: 600;
    color: #ffd700;
    text-decoration: line-through;
    color: var(--muted);
}

.remise-gold {
    font-size: 12px;
    color: #4caf50;
    font-weight: 600;
}

.btn-demand {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Inter', sans-serif;
    margin-top: auto;
}

.btn-demand:hover {
    background: #6b5fe0;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert {
    padding: 14px 16px;
    border-radius: var(--radius-sm);
    margin-bottom: 16px;
    font-size: 13px;
}

.alert-success {
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: #4caf50;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--muted);
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 16px;
}
</style>

<div class="offres-container">
    <!-- Messages d'alerte -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <!-- Badge Gold -->
    <?php if ($isGold): ?>
        <div class="gold-badge">
            ★ Abonnement Gold - 14% de remise sur toutes les offres
        </div>
    <?php endif; ?>

    <!-- Bouton Suggestions -->
    <button class="btn-suggestions" onclick="window.location.href='/offres/suggestions'">
        ✨ Voir les suggestions personnalisées
    </button>

    <!-- Offres disponibles -->
    <div>
        <div class="section-title">
            📦 Offres Disponibles
        </div>

        <?php if (!empty($offres)): ?>
            <div class="offres-grid">
                <?php foreach ($offres as $offre): ?>
                    <div class="offre-card">
                        <div class="offre-type">
                            <?php if ($offre['type'] === 'regime'): ?>
                                🍽️ Régime
                            <?php elseif ($offre['type'] === 'sport'): ?>
                                🏃 Sport
                            <?php else: ?>
                                🍽️🏃 Régime + Sport
                            <?php endif; ?>
                        </div>

                        <div class="offre-titre"><?= esc($offre['nom']) ?></div>

                        <?php if (!empty($offre['description'])): ?>
                            <div class="offre-description">
                                <?= esc($offre['description']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="offre-prix">
                            <?php if ($isGold): ?>
                                <span class="prix-gold"><?= number_format($offre['prix'], 2) ?>€</span>
                                <span class="prix-normal"><?= number_format($offre['prix_affiche'], 2) ?>€</span>
                                <span class="remise-gold">-14%</span>
                            <?php else: ?>
                                <span class="prix-normal"><?= number_format($offre['prix_affiche'], 2) ?>€</span>
                            <?php endif; ?>
                        </div>

                        <form method="post" action="/offres/demand/<?= $offre['id'] ?>" style="display: contents;">
                            <button type="submit" class="btn-demand">
                                Demander cette offre
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>Aucune offre disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Demandes en cours -->
    <?php if (!empty($demandesUtilisateur)): ?>
        <div>
            <div class="section-title">
                📋 Vos Demandes
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($demandesUtilisateur as $demande): ?>
                    <div class="offre-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                            <div style="flex: 1;">
                                <div class="offre-type">
                                    <?php if ($demande['statut'] === 'acceptée'): ?>
                                        ✅ Acceptée
                                    <?php elseif ($demande['statut'] === 'rejetée'): ?>
                                        ❌ Rejetée
                                    <?php else: ?>
                                        ⏳ En attente
                                    <?php endif; ?>
                                </div>
                                <div class="offre-titre"><?= esc($demande['nom']) ?></div>
                            </div>
                            <?php if (!empty($demande['prix_paye'])): ?>
                                <div style="text-align: right;">
                                    <div class="prix-normal" style="margin: 0;"><?= number_format($demande['prix_paye'], 2) ?>€</div>
                                    <div style="font-size: 11px; color: var(--muted);">Prix payé</div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($demande['regime_libelle'])): ?>
                            <div class="offre-description">
                                🍽️ Régime: <?= esc($demande['regime_libelle']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($demande['sport_libelle'])): ?>
                            <div class="offre-description">
                                🏃 Sport: <?= esc($demande['sport_libelle']) ?>
                            </div>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--muted); margin-bottom: 12px;">
                            <span>Demande du <?= date_format(date_create($demande['date_demande']), 'd/m/Y H:i') ?></span>
                            <?php if ($demande['statut'] === 'acceptée' && !empty($demande['date_acceptation'])): ?>
                                <span>Acceptée le <?= date_format(date_create($demande['date_acceptation']), 'd/m/Y') ?></span>
                            <?php endif; ?>
                        </div>

                        <button onclick="window.location.href='/offres/detail/<?= $demande['id'] ?>'" 
                                style="background: var(--bg3); color: var(--text); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; margin-top: auto; width: 100%;">
                            Voir détails et gérer
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Abonnements -->
    <div>
        <div class="section-title">
            ⭐ Options d'Abonnement
        </div>

        <div class="offres-grid">
            <!-- GRATUIT -->
            <div class="offre-card" style="border: <?= (!$isGold && empty($abonnementActuel)) ? '2px solid #4caf50' : '1px solid var(--border)' ?>; background: <?= (!$isGold && empty($abonnementActuel)) ? 'rgba(76, 175, 80, 0.05)' : 'var(--bg2)' ?>;">
                <?php if (!$isGold && empty($abonnementActuel)): ?>
                    <div style="background: #4caf50; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; width: fit-content;">
                        ✓ VOTRE ABONNEMENT
                    </div>
                <?php endif; ?>
                <div class="offre-titre">Gratuit</div>
                <div class="offre-description">
                    Accès de base à toutes les offres sans remise
                </div>
                <div class="offre-prix">
                    <span class="prix-normal">0€</span>
                    <span class="remise-gold">Inclus</span>
                </div>
                <?php if ($isGold || (!empty($abonnementActuel) && $abonnementActuel['option_id'] !== 1)): ?>
                    <form method="post" action="/offres/subscribe" style="display: contents;">
                        <input type="hidden" name="option_id" value="1">
                        <button type="submit" class="btn-demand">
                            Passer à Gratuit
                        </button>
                    </form>
                <?php else: ?>
                    <button disabled style="background: #ccc; color: #fff; border: none; border-radius: var(--radius-sm); padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: not-allowed; margin-top: auto;">
                        Actuellement actif
                    </button>
                <?php endif; ?>
            </div>

            <!-- GOLD -->
            <div class="offre-card" style="border: <?= ($isGold || (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 2)) ? '2px solid #ffd700' : '1px solid var(--border)' ?>; background: <?= ($isGold || (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 2)) ? 'rgba(255, 215, 0, 0.05)' : 'var(--bg2)' ?>;">
                <?php if (!($isGold || (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 2))): ?>
                    <div style="background: #ffd700; color: #333; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; width: fit-content;">
                        ★ POPULAIRE
                    </div>
                <?php else: ?>
                    <div style="background: #ffd700; color: #333; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; width: fit-content;">
                        ✓ VOTRE ABONNEMENT
                    </div>
                <?php endif; ?>
                <div class="offre-titre">Gold</div>
                <div class="offre-description">
                    14% de remise sur toutes les offres + support prioritaire
                </div>
                <div class="offre-prix">
                    <span class="prix-normal">9.99€</span>
                    <span class="remise-gold">/mois</span>
                </div>
                <?php if ($isGold || (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 2)): ?>
                    <button disabled style="background: #ccc; color: #fff; border: none; border-radius: var(--radius-sm); padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: not-allowed; margin-top: auto;">
                        Abonnement actif
                    </button>
                <?php else: ?>
                    <form method="post" action="/offres/subscribe" style="display: contents;">
                        <input type="hidden" name="option_id" value="2">
                        <button type="submit" class="btn-demand" style="background: #ffd700; color: #333;">
                            Souscrire à Gold 🌟
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- DIAMOND -->
            <div class="offre-card" style="border: <?= (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 3) ? '2px solid #6b5fe0' : '1px solid var(--border)' ?>; background: <?= (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 3) ? 'rgba(107, 95, 224, 0.05)' : 'var(--bg2)' ?>;">
                <?php if (!(!empty($abonnementActuel) && $abonnementActuel['option_id'] == 3)): ?>
                    <div style="background: #6b5fe0; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; width: fit-content;">
                        💎 VIP
                    </div>
                <?php else: ?>
                    <div style="background: #6b5fe0; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; width: fit-content;">
                        ✓ VOTRE ABONNEMENT
                    </div>
                <?php endif; ?>
                <div class="offre-titre">Diamond</div>
                <div class="offre-description">
                    20% de remise + support 24/7 + coaching personnalisé
                </div>
                <div class="offre-prix">
                    <span class="prix-normal">19.99€</span>
                    <span class="remise-gold">/mois</span>
                </div>
                <?php if (!empty($abonnementActuel) && $abonnementActuel['option_id'] == 3): ?>
                    <button disabled style="background: #ccc; color: #fff; border: none; border-radius: var(--radius-sm); padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: not-allowed; margin-top: auto;">
                        Abonnement actif
                    </button>
                <?php else: ?>
                    <form method="post" action="/offres/subscribe" style="display: contents;">
                        <input type="hidden" name="option_id" value="3">
                        <button type="submit" class="btn-demand" style="background: #6b5fe0;">
                            Souscrire à Diamond 💎
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
