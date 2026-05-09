INSERT INTO utilisateurs (nom, email, date_naissance, genre_id, role_id, mot_de_passe, date_creation)
VALUES 
('Jean Martin', 'jean1@mail.com', '1995-01-01', 1, 2, '123', '2025-01-10 10:00:00'),
('Paul Durand', 'paul@mail.com', '1992-02-01', 1, 2, '123', '2025-01-15 10:00:00'),
('Sarah Lee', 'sarah@mail.com', '1998-03-01', 2, 2, '123', '2025-02-05 10:00:00'),
('Kevin Rakoto', 'kevin@mail.com', '1990-04-01', 1, 2, '123', '2025-02-20 10:00:00'),
('Alice Dupont', 'alice2@mail.com', '1991-05-01', 2, 2, '123', '2025-03-01 10:00:00'),
('John Doe', 'john@mail.com', '1993-06-01', 1, 2, '123', '2025-03-10 10:00:00'),
('Emma Stone', 'emma@mail.com', '1994-07-01', 2, 2, '123', '2025-04-02 10:00:00'),
('Lucas Morel', 'lucas@mail.com', '1996-08-01', 1, 2, '123', '2025-04-18 10:00:00'),
('Nina Lopez', 'nina@mail.com', '1997-09-01', 2, 2, '123', '2025-05-01 10:00:00'),
('Tom Hardy', 'tom@mail.com', '1990-10-01', 1, 2, '123', '2025-05-05 10:00:00');


INSERT INTO utilisateur_objectifs (utilisateur_id, objectif_id, valeur_cible)
VALUES
(1, 2, 70),
(2, 2, 68),
(3, 1, 75),
(4, 1, 80),
(5, 3, 65),
(6, 2, 72),
(7, 1, 78),
(8, 2, 69),
(9, 3, 60),
(10, 1, 82);


INSERT INTO utilisateurs (nom, email, date_naissance, genre_id, role_id, mot_de_passe, date_creation)
VALUES
('User Extra 1', 'extra1@mail.com', '1992-01-01', 1, 2, '123', '2025-06-10 10:00:00'),
('User Extra 2', 'extra2@mail.com', '1993-02-01', 2, 2, '123', '2025-06-15 10:00:00'),

('User Extra 3', 'extra3@mail.com', '1994-03-01', 1, 2, '123', '2025-07-01 10:00:00'),
('User Extra 4', 'extra4@mail.com', '1995-04-01', 2, 2, '123', '2025-07-20 10:00:00'),

('User Extra 5', 'extra5@mail.com', '1996-05-01', 1, 2, '123', '2025-08-05 10:00:00'),
('User Extra 6', 'extra6@mail.com', '1997-06-01', 2, 2, '123', '2025-08-18 10:00:00');


INSERT INTO utilisateur_objectifs (utilisateur_id, objectif_id, valeur_cible)
VALUES
(11, 2, 70),
(12, 1, 80),
(13, 3, 65),
(14, 2, 68),
(15, 1, 78),
(16, 3, 62);


INSERT INTO comptes (utilisateur_id, solde, status)
VALUES
(13, 7500, 'active'),
(14, 4200, 'active'),
(15, 9800, 'active'),
(16, 3100, 'active'),
(11, 6600, 'active'),
(12, 1200, 'inactive');









INSERT INTO utilisateurs_codes (code_id, utilisateur_id, date_utilisation)
VALUES
(1, 1, '2025-05-01 10:00:00'),
(2, 2, '2025-05-02 11:00:00'),
(3, 3, '2025-05-03 12:00:00'),
(1, 4, '2025-05-04 13:00:00'),
(2, 5, '2025-05-05 14:00:00'),
(3, 6, '2025-05-06 15:00:00'),
(1, 7, '2025-05-07 16:00:00'),
(2, 8, '2025-05-08 17:00:00');


INSERT INTO codes_promo (code, status, date_expiration)
VALUES
('WELCOME10', 'used', '2025-12-31'),
('VIP20', 'active', '2025-12-31'),
('SUMMER30', 'expired', '2025-06-30');


INSERT INTO utilisateurs_codes (code_id, utilisateur_id, date_utilisation)
VALUES
(1, 1, '2025-05-01 10:00:00'),
(2, 2, '2025-05-02 11:00:00'),
(3, 3, '2025-05-03 12:00:00'),
(1, 4, '2025-05-04 13:00:00'),
(2, 5, '2025-05-05 14:00:00'),
(3, 6, '2025-05-06 15:00:00'),
(1, 7, '2025-05-07 16:00:00'),
(2, 8, '2025-05-08 17:00:00');