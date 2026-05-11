-- =========================================
-- DATABASE
-- =========================================
CREATE DATABASE imc_db;

\c imc_db;

-- =========================================
-- ENUMS
-- =========================================

CREATE TYPE code_status_enum AS ENUM (
    'active',
    'used',
    'expired'
);

CREATE TYPE account_status_enum AS ENUM (
    'active',
    'inactive',
    'suspended'
);

CREATE TYPE transaction_type_enum AS ENUM (
    'income',
    'expense'
);

-- =========================================
-- TABLES DE REFERENCE
-- =========================================

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE genres (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE objectifs (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE regimes (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE sports (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    calories_par_heure NUMERIC(10, 2) NOT NULL
);

CREATE TABLE aliments (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    calories_100g NUMERIC(10, 2) NOT NULL,
    proteines_100g NUMERIC(10, 2) DEFAULT 0,
    glucides_100g NUMERIC(10, 2) DEFAULT 0,
    lipides_100g NUMERIC(10, 2) DEFAULT 0
);

CREATE TABLE imc_categories (
    id SERIAL PRIMARY KEY,
    imc_min NUMERIC(5, 2) NOT NULL,
    imc_max NUMERIC(5, 2) NOT NULL,
    categorie VARCHAR(50) NOT NULL
);

-- =========================================
-- UTILISATEURS
-- =========================================

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    date_naissance DATE NOT NULL,
    genre_id INT REFERENCES genres (id),
    role_id INT REFERENCES roles (id),
    mot_de_passe VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateur_mesures (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    poids_kg NUMERIC(5, 2) NOT NULL,
    taille_m NUMERIC(3, 2) NOT NULL,
    date_mesure TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateur_objectifs (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    objectif_id INT NOT NULL REFERENCES objectifs (id) ON DELETE CASCADE,
    valeur_cible NUMERIC(10, 2),
    statut VARCHAR(20) DEFAULT 'en_cours' CHECK (statut IN ('en_cours', 'termine')),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE historique_imc (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    valeur_imc NUMERIC(5, 2) NOT NULL,
    date_calcul TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- REGIMES ET RECETTES
-- =========================================

CREATE TABLE recettes (
    id SERIAL PRIMARY KEY,
    regime_id INT NOT NULL REFERENCES regimes (id) ON DELETE CASCADE,
    aliment_id INT NOT NULL REFERENCES aliments (id) ON DELETE CASCADE,
    pourcentage NUMERIC(5, 2) NOT NULL
);

-- =========================================
-- CODES PROMO / ABONNEMENT
-- =========================================

CREATE TABLE codes_promo (
    id SERIAL PRIMARY KEY,
    code VARCHAR(100) NOT NULL UNIQUE,
    prix NUMERIC(10, 2) NOT NULL DEFAULT 0,
    status code_status_enum DEFAULT 'active',
    date_expiration TIMESTAMP
);

CREATE TABLE utilisateurs_codes (
    id SERIAL PRIMARY KEY,
    code_id INT NOT NULL REFERENCES codes_promo (id) ON DELETE CASCADE,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    date_utilisation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE abonnements_options (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix NUMERIC(10, 2) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE abonnements (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    option_id INT NOT NULL REFERENCES abonnements_options (id) ON DELETE CASCADE,
    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_fin TIMESTAMP
);

-- =========================================
-- OFFRES
-- =========================================

CREATE TABLE offres (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix NUMERIC(10, 2) NOT NULL
);

CREATE TABLE demandes_offres (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    offre_id INT NOT NULL REFERENCES offres (id) ON DELETE CASCADE,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- COMPTE & FINANCE
-- =========================================

CREATE TABLE comptes (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL UNIQUE REFERENCES utilisateurs (id) ON DELETE CASCADE,
    solde NUMERIC(12, 2) DEFAULT 0,
    status account_status_enum DEFAULT 'active',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    compte_id INT NOT NULL REFERENCES comptes (id) ON DELETE CASCADE,
    type transaction_type_enum NOT NULL,
    montant NUMERIC(12, 2) NOT NULL CHECK (montant > 0),
    description TEXT,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- INDEX
-- =========================================

CREATE INDEX idx_utilisateur_email ON utilisateurs (email);

CREATE INDEX idx_transaction_compte ON transactions (compte_id);

CREATE INDEX idx_historique_imc_utilisateur ON historique_imc (utilisateur_id);

-- =========================================
-- DONNEES INITIALES
-- =========================================

INSERT INTO roles (nom) VALUES ('admin'), ('utilisateur');

INSERT INTO genres (nom) VALUES ('homme'), ('femme');

INSERT INTO
    objectifs (libelle)
VALUES ('prise de masse'),
    ('perte de poids'),
    ('maintien');

INSERT INTO
    imc_categories (imc_min, imc_max, categorie)
VALUES (0, 18.49, 'maigreur'),
    (18.50, 24.99, 'normal'),
    (25.00, 29.99, 'surpoids'),
    (30.00, 100, 'obesite');

--data option
insert into abonnements_options (nom, prix, description) values
('gold', 9.99, 'option gold avec 15% de réduction sur les regimes');

INSERT INTO utilisateurs (nom, email, date_naissance, genre_id, role_id, mot_de_passe)
VALUES ('Alice Dupont', 'alice.dupont@example.com', '1990-01-01', 2, 2, '123456789');

-- =========================================
-- DONNEES ALIMENTS ET RECETTES
-- =========================================

-- Insertion d'aliments de base pour les recettes
INSERT INTO aliments (nom, calories_100g, proteines_100g, glucides_100g, lipides_100g) VALUES
('Blanc de poulet', 165, 31, 0, 3.6),
('Riz complet', 111, 2.7, 23, 0.9),
('Brocolis', 34, 2.8, 7, 0.4),
('Huile d''olive', 884, 0, 0, 100),
('Saumon', 208, 22, 0, 13),
('Patates douces', 86, 1.6, 20, 0.1),
('Avocat', 160, 2, 9, 15),
('Citron', 29, 1.1, 9, 0.3),
('Blanc de dinde', 135, 30, 0, 1),
('Quinoa', 368, 14, 64, 6),
('Thon en conserve', 128, 29, 0, 1),
('Courgettes', 17, 1.2, 3.1, 0.3),
('Poivrons', 31, 1, 6, 0.3),
('Herbes de Provence', 23, 1.8, 3.5, 0.7),
('Cabillaud', 82, 18, 0, 0.7),
('Carottes', 41, 0.9, 10, 0.2),
('Légumes variés (salade)', 25, 1.5, 4.5, 0.3),
('Vinaigre balsamique', 88, 0.5, 17, 0),
('Tomates', 18, 0.9, 3.9, 0.2),
('Concombre', 16, 0.7, 3.6, 0.1),
('Fromage blanc 0%', 58, 10, 3.5, 0.2),
('Pomme', 52, 0.2, 14, 0.2),
('Banane', 89, 1.1, 23, 0.3),
('Amandes', 579, 21, 22, 50),
('Oeuf', 155, 13, 1.1, 11),
('Lait écrémé', 34, 3.4, 5, 0.1),
('Pain complet', 247, 9, 41, 3.5),
('Pâtes complètes', 157, 5.8, 30, 0.9),
('Haricots verts', 31, 1.8, 7, 0.1),
('Champignons', 22, 3.1, 3.3, 0.3),
('Lentilles', 116, 9, 20, 0.4),
('Pois chiches', 164, 7.5, 27, 2.6),
('Épinards', 23, 2.9, 3.6, 0.4),
('Aubergines', 25, 1, 6, 0.2),
('Oignons', 40, 1.1, 9, 0.1),
('Ail', 149, 6.4, 33, 0.5),
('Gingembre', 80, 1.8, 18, 0.8),
('Curcuma', 312, 9.7, 65, 3.2),
('Noix de cajou', 553, 18, 30, 44),
('Graines de chia', 486, 17, 42, 31),
('Flocons d''avoine', 379, 13, 66, 6.9),
('Miel', 304, 0.3, 82, 0),
('Fruits rouges', 57, 0.7, 12, 0.3),
('Kiwi', 61, 1.1, 15, 0.5),
('Mangue', 60, 0.8, 15, 0.4);

-- Insertion de régimes
INSERT INTO regimes (libelle) VALUES
('Régime Prise de Masse'),
('Régime Perte de Poids'),
('Régime Maintien'),
('Régime Végétarien'),
('Régime Cétogène'),
('Régime Méditerranéen'),
('Régime Paléo'),
('Régime Flexitarien');

-- Insertion de recettes pour le régime prise de masse (riche en protéines et calories)
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(1, 1, 25), -- Blanc de poulet
(1, 2, 20), -- Riz complet
(1, 3, 15), -- Brocolis
(1, 4, 5),  -- Huile d'olive
(1, 5, 20), -- Saumon
(1, 6, 15); -- Patates douces

-- Insertion de recettes pour le régime perte de poids (faible calorie)
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(2, 1, 30), -- Blanc de poulet
(2, 3, 25), -- Brocolis
(2, 7, 15), -- Avocat
(2, 8, 5),  -- Citron
(2, 9, 15), -- Blanc de dinde
(2, 10, 10); -- Quinoa

-- Insertion de recettes pour le régime maintien (équilibré)
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(3, 11, 20), -- Thon
(3, 12, 15), -- Courgettes
(3, 13, 15), -- Poivrons
(3, 14, 5),  -- Herbes
(3, 15, 25), -- Cabillaud
(3, 16, 20); -- Carottes

-- Insertion de recettes pour le régime végétarien
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(4, 10, 25), -- Quinoa
(4, 30, 20), -- Lentilles
(4, 31, 15), -- Pois chiches
(4, 32, 15), -- Épinards
(4, 33, 10), -- Aubergines
(4, 34, 10), -- Oignons
(4, 35, 5);  -- Ail

-- Insertion de recettes pour le régime cétogène (riche en graisses, pauvre en glucides)
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(5, 5, 30),  -- Saumon
(5, 7, 25),  -- Avocat
(5, 4, 15),  -- Huile d'olive
(5, 25, 15), -- Oeuf
(5, 36, 10), -- Noix de cajou
(5, 37, 5);  -- Graines de chia

-- Insertion de recettes pour le régime méditerranéen
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(6, 15, 20), -- Cabillaud
(6, 19, 15), -- Tomates
(6, 13, 15), -- Poivrons
(6, 4, 10),  -- Huile d'olive
(6, 32, 10), -- Épinards
(6, 38, 10), -- Flocons d'avoine
(6, 39, 10), -- Miel
(6, 40, 10); -- Fruits rouges

-- Insertion de recettes pour le régime paléo
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(7, 1, 25),  -- Blanc de poulet
(7, 5, 20),  -- Saumon
(7, 6, 15),  -- Patates douces
(7, 7, 15),  -- Avocat
(7, 32, 10), -- Épinards
(7, 36, 10), -- Noix de cajou
(7, 41, 5);  -- Kiwi

-- Insertion de recettes pour le régime flexitarien (végétarien flexible)
INSERT INTO recettes (regime_id, aliment_id, pourcentage) VALUES
(8, 30, 20), -- Lentilles
(8, 31, 15), -- Pois chiches
(8, 10, 15), -- Quinoa
(8, 11, 15), -- Thon
(8, 32, 10), -- Épinards
(8, 38, 10), -- Flocons d'avoine
(8, 40, 10), -- Fruits rouges
(8, 42, 5);  -- Mangue

-- Insertion de sports
INSERT INTO sports (nom, calories_par_heure) VALUES
('Musculation intensive', 400),
('Course à pied', 600),
('Natation', 500),
('Cyclisme', 450),
('HIIT (Entraînement par intervalles)', 700),
('Yoga', 200),
('Marche rapide', 300),
('Danse', 350),
('Escalade', 550),
('Tennis', 480),
('Basketball', 520),
('Football', 580),
('Boxe', 650),
('Pilates', 250),
('Fitness en salle', 420);



INSERT INTO utilisateurs (nom, email, date_naissance, genre_id, role_id, mot_de_passe) VALUES
('Admin Super', 'admin@gmail.com', '1985-03-10', 1, 1, '$2y$10$rfevquzpN.S390chX.I6S.GzArYiJ.6HuTnoFy.nrYswUCcoqcPz2'),
('Jean Martin', 'user1@gmail.com', '1992-06-15', 1, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Sophie Leroy', 'user2@gmail.com', '1988-09-02', 2, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Marc Petit', 'user3@gmail.com', '1995-12-20', 1, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC'),
('Claire Dubois', 'user4@gmail.com', '1990-04-11', 2, 2, '$2y$10$6i49v0LHnP0EMPyoQL17OeoieaV4ha8Ba6MM5FwehaG0Wxlo39jQC');

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
