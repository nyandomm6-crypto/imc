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
    valeur_cible NUMERIC(10, 2)
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