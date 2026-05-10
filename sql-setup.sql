-- ===============================================
-- COMMANDES SQL POUR INITIALISER LE SYSTÈME
-- ===============================================

-- 1. AJOUTER LES COLONNES MANQUANTES SI NÉCESSAIRE
-- (Vérifier d'abord que ces colonnes n'existent pas)

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

-- 2. INSÉRER DES OFFRES EXEMPLE
INSERT INTO offres (nom, type, description, prix, prix_gold) VALUES
('Programme de perte - Cardio', 'sport', 'Programme de cardio intensif pour perte de poids', 49.99, 42.99),
('Régime protéiné', 'regime', 'Régime riche en protéines pour prise de masse', 39.99, 34.39),
('Pack complet - Fitness Gold', 'regime_sport', 'Régime + sport combinés pour résultats optimaux', 89.99, 77.39),
('Sport doux - Bien-être', 'sport', 'Activités douces et régulières pour maintien', 29.99, 25.79),
('Régime équilibré', 'regime', 'Régime varié et équilibré pour santé globale', 44.99, 38.69)
ON CONFLICT DO NOTHING;

-- 3. INSÉRER DES OPTIONS D'ABONNEMENT
INSERT INTO abonnements_options (nom, prix, description) VALUES
('Gratuit', 0.00, 'Accès de base'),
('Gold', 9.99, 'Accès premium avec remise de 14% sur les offres'),
('Diamond', 19.99, 'Accès VIP avec remise 20% et support prioritaire')
ON CONFLICT DO NOTHING;

-- 4. VÉRIFIER LES DONNÉES EXISTANTES
SELECT 'Offres disponibles:' as info;
SELECT COUNT(*) as total_offres FROM offres;

SELECT 'Utilisateurs:' as info;
SELECT COUNT(*) as total_utilisateurs FROM utilisateurs;

SELECT 'Options d''abonnement:' as info;
SELECT * FROM abonnements_options;

-- 5. STATISTIQUES
SELECT 'Statistiques système:' as info;
SELECT 
    (SELECT COUNT(*) FROM utilisateurs) as total_utilisateurs,
    (SELECT COUNT(*) FROM offres) as total_offres,
    (SELECT COUNT(*) FROM demandes_offres) as total_demandes,
    (SELECT COUNT(*) FROM abonnements) as total_abonnements;
