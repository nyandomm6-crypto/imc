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
('Concombre', 16, 0.7, 3.6, 0.1);

-- Insertion de régimes
INSERT INTO regimes (libelle) VALUES
('Régime Prise de Masse'),
('Régime Perte de Poids'),
('Régime Maintien');

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

-- Insertion de sports
INSERT INTO sports (nom, calories_par_heure) VALUES
('Musculation intensive', 400),
('Course à pied', 600),
('Natation', 500),
('Cyclisme', 450),
('HIIT (Entraînement par intervalles)', 700),
('Yoga', 200),
('Marche rapide', 300),
('Danse', 350);