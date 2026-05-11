-- Données minimales pour tests
-- 5 utilisateurs (1 admin + 4 users) avec mots de passe bcrypt
-- 15 codes promo

-- Utilisateurs
INSERT INTO utilisateurs (nom, email, date_naissance, genre_id, role_id, mot_de_passe) VALUES
('Admin Super', 'admin@example.com', '1985-03-10', 1, 1, '$2y$10$rfevquzpN.S390chX.I6S.GzArYiJ.6HuTnoFy.nrYswUCcoqcPz2'),
('Jean Martin', 'jean.martin@example.com', '1992-06-15', 1, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Sophie Leroy', 'sophie.leroy@example.com', '1988-09-02', 2, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Marc Petit', 'marc.petit@example.com', '1995-12-20', 1, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Claire Dubois', 'claire.dubois@example.com', '1990-04-11', 2, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC');

-- Codes promo
INSERT INTO codes_promo (code, prix, status, date_expiration) VALUES
('PROMO10', 10.00, 'active', NULL),
('PROMO5', 5.00, 'active', NULL),
('WELCOME', 7.50, 'active', NULL),
('SUMMER21', 15.00, 'active', '2026-09-30'),
('GOLD50', 50.00, 'active', '2026-12-31'),
('SPRING', 8.00, 'active', '2026-06-30'),
('HALLOWEEN', 12.00, 'active', '2026-10-31'),
('BLACKFRI', 20.00, 'active', '2026-11-27'),
('NEWUSER', 3.00, 'active', NULL),
('FREEMONTH', 9.99, 'active', NULL),
('XMAS', 25.00, 'active', '2026-12-25'),
('DISCOUNT15', 15.00, 'active', NULL),
('FLASH5', 5.00, 'active', NULL),
('LOYALTY', 30.00, 'active', NULL),
('TRIAL7', 7.00, 'active', NULL);

-- Note: les IDs pour roles et genres supposent qu'ils existent déjà (roles: 1=admin,2=utilisateur ; genres: 1=homme,2=femme)
-- Exécutez ce fichier après avoir créé les tables (par ex. via psql -U user -d imc_db -f donnees_initiales.sql)
