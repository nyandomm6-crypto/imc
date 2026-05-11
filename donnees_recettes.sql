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
('Champignons', 22, 3.1, 3.3, 0.3);

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