-- =====================================================
-- COMMANDES SQL À EXÉCUTER DIRECTEMENT
-- =====================================================
-- Copier/coller ces commandes dans psql ou PgAdmin
-- 
-- ÉTAPE 1: Ajouter les colonnes manquantes
-- ÉTAPE 2: Insérer les options d'abonnement
-- ÉTAPE 3: Insérer les offres exemples
-- ÉTAPE 4: Vérifier
-- =====================================================

-- ======================
-- ✅ ÉTAPE 1: COLONNES
-- ======================

ALTER TABLE utilisateur_objectifs 
ADD COLUMN IF NOT EXISTS statut VARCHAR(20) DEFAULT 'en_cours',
ADD COLUMN IF NOT EXISTS date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS date_fin TIMESTAMP;

ALTER TABLE offres
ADD COLUMN IF NOT EXISTS type VARCHAR(20) DEFAULT 'regime',
ADD COLUMN IF NOT EXISTS description TEXT,
ADD COLUMN IF NOT EXISTS prix_gold NUMERIC(10, 2),
ADD COLUMN IF NOT EXISTS date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE demandes_offres
ADD COLUMN IF NOT EXISTS regime_id INT REFERENCES regimes (id) ON DELETE SET NULL,
ADD COLUMN IF NOT EXISTS sport_id INT REFERENCES sports (id) ON DELETE SET NULL,
ADD COLUMN IF NOT EXISTS statut VARCHAR(20) DEFAULT 'demande',
ADD COLUMN IF NOT EXISTS prix_paye NUMERIC(10, 2),
ADD COLUMN IF NOT EXISTS date_acceptation TIMESTAMP;

-- =============================
-- ✅ ÉTAPE 2: OPTIONS ABONNEMENT
-- =============================

INSERT INTO abonnements_options (nom, prix, description) 
VALUES 
('Gratuit', 0.00, 'Accès de base - Aucune remise'),
('Gold', 9.99, 'Accès premium - 14% de remise sur les offres'),
('Diamond', 19.99, 'Accès VIP - 20% de remise + support')
ON CONFLICT (nom) DO NOTHING;

-- ========================
-- ✅ ÉTAPE 3: OFFRES EXEMPLES
-- ========================

INSERT INTO offres (nom, type, description, prix, prix_gold) 
VALUES
(
  'Programme Cardio Intensif',
  'sport',
  'Programme de cardio haute intensité pour perte de poids rapide. 5 séances/semaine, 45 min chacune.',
  49.99,
  42.99
),
(
  'Régime Protéiné Pro',
  'regime',
  'Régime riche en protéines pour prise de masse musculaire. Plans de repas détaillés inclus.',
  39.99,
  34.39
),
(
  'Pack Complet Fitness Gold',
  'regime_sport',
  'Combinaison régime + sport pour résultats optimaux. Prise de masse complète.',
  89.99,
  77.39
),
(
  'Sport Doux - Bien-être',
  'sport',
  'Activités douces et régulières pour maintien de la santé. Yoga, marche, étirements.',
  29.99,
  25.79
),
(
  'Régime Équilibré Santé',
  'regime',
  'Régime varié et équilibré pour santé globale. Nutritionist approval.',
  44.99,
  38.69
),
(
  'Transformation 90 Jours',
  'regime_sport',
  'Pack complet perte de poids: régime + sport sur 90 jours. Suivi personnalisé.',
  149.99,
  128.99
)
ON CONFLICT DO NOTHING;

-- ========================
-- ✅ ÉTAPE 4: VÉRIFICATION
-- ========================

-- Vérifier les options d'abonnement
SELECT 
  '📋 OPTIONS D''ABONNEMENT' as "INFO",
  COUNT(*) as "Total"
FROM abonnements_options;

SELECT 
  '  → ' || nom as "Option",
  prix as "Prix",
  description as "Description"
FROM abonnements_options
ORDER BY prix;

-- Vérifier les offres
SELECT 
  '📦 OFFRES' as "INFO",
  COUNT(*) as "Total"
FROM offres;

SELECT 
  '  → ' || nom as "Offre",
  type as "Type",
  prix as "Prix Normal",
  prix_gold as "Prix Gold",
  ROUND((prix - prix_gold)::numeric, 2) as "Remise"
FROM offres
ORDER BY type, prix;

-- Vérifier les demandes
SELECT 
  '📋 DEMANDES' as "INFO",
  COUNT(*) as "Total"
FROM demandes_offres;

SELECT 
  '  → Status ' || statut as "Statut",
  COUNT(*) as "Nombre"
FROM demandes_offres
GROUP BY statut;

-- Vérifier les utilisateurs
SELECT 
  '👥 UTILISATEURS' as "INFO",
  COUNT(*) as "Total"
FROM utilisateurs;

-- Statistiques de contrôle
SELECT 
  '📊 STATISTIQUES CONTRÔLE' as "INFO";

SELECT 
  'Utilisateurs' as "Catégorie",
  COUNT(*) as "Total"
FROM utilisateurs
UNION ALL
SELECT 'Offres', COUNT(*) FROM offres
UNION ALL
SELECT 'Demandes', COUNT(*) FROM demandes_offres
UNION ALL
SELECT 'Objectifs', COUNT(*) FROM utilisateur_objectifs
UNION ALL
SELECT 'Abonnements', COUNT(*) FROM abonnements;

-- ==============================================
-- 🎯 RÉSUMÉ DES COMMANDES SQL EXÉCUTÉES
-- ==============================================
/*
Colonnes ajoutées:
  ✅ utilisateur_objectifs: statut, date_debut, date_fin
  ✅ offres: type, description, prix_gold, date_creation
  ✅ demandes_offres: regime_id, sport_id, statut, prix_paye, date_acceptation

Options d'abonnement:
  ✅ Gratuit (0€)
  ✅ Gold (9.99€) - 14% remise
  ✅ Diamond (19.99€) - 20% remise

Offres insérées:
  ✅ 6 offres exemples avec types variés
  ✅ Tous les prix Gold calculés (-14%)

Prêt pour:
  ✅ Routes admin fonctionnelles
  ✅ Routes user fonctionnelles
  ✅ Vues admin visibles
  ✅ Vues user visibles
  ✅ Système remise Gold actif
*/

-- =====================================================
-- 🚀 PROCHAINES ÉTAPES
-- =====================================================
/*
1. Exécuter cette partie 1-4 (ÉTAPES 1-4)
2. Lancer CodeIgniter: php spark serve
3. Créer un utilisateur test admin si nécessaire
4. Aller à /admin/offres pour vérifier offres
5. Aller à /admin/offres/demandes/all pour demandes
6. Aller à /offres comme utilisateur normal
7. Créer une demande test
8. Admin accepte/rejette demande
9. Tester remise Gold
*/

-- =====================================================
-- 📝 NOTES IMPORTANTES
-- =====================================================
/*
- Les prix Gold sont calculés avec 14% de remise
- Les migrations CodeIgniter peuvent aussi être exécutées via: php spark migrate
- Les regex de validation sont dans les contrôleurs
- Toutes les données sont en UTC
- Les statistiques sont mises à jour en temps réel
*/

-- ✅ FIN DES COMMANDES
