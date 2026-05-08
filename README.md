# TODO LIST — Application Fitness IMC (CodeIgniter + PostgreSQL)

---

## 📁 STRUCTURE DU PROJET

```
fitness_imc/
├── app/
│   ├── Controllers/
│   │   ├── front/
│   │   │   ├── AuthController.php
│   │   │   ├── ProfilController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── RegimeController.php
│   │   │   ├── SportController.php
│   │   │   ├── PorteMonnaieController.php
│   │   │   └── AbonnementController.php
│   │   └── back/
│   │       ├── AdminAuthController.php
│   │       ├── AdminDashboardController.php
│   │       ├── AdminRegimeController.php
│   │       ├── AdminSportController.php
│   │       ├── AdminCodeController.php
│   │       ├── AdminAlimentController.php
│   │       └── AdminUtilisateurController.php
│   ├── Models/
│   │   ├── UtilisateurModel.php
│   │   ├── MesureModel.php
│   │   ├── ImcModel.php
│   │   ├── ObjectifModel.php
│   │   ├── RegimeModel.php
│   │   ├── AlimentModel.php
│   │   ├── SportModel.php
│   │   ├── CodePromoModel.php
│   │   ├── CompteModel.php
│   │   ├── TransactionModel.php
│   │   ├── AbonnementModel.php
│   │   └── OffreModel.php
│   ├── Views/
│   │   ├── front/
│   │   │   ├── auth/
│   │   │   │   ├── inscription_etape1.php
│   │   │   │   ├── inscription_etape2.php
│   │   │   │   └── login.php
│   │   │   ├── profil/
│   │   │   │   ├── index.php
│   │   │   │   └── objectifs.php
│   │   │   ├── dashboard/
│   │   │   │   └── index.php
│   │   │   ├── regime/
│   │   │   │   ├── liste.php
│   │   │   │   └── detail.php
│   │   │   ├── sport/
│   │   │   │   └── liste.php
│   │   │   └── abonnement/
│   │   │       └── gold.php
│   │   └── back/
│   │       ├── auth/
│   │       │   └── login.php
│   │       ├── dashboard/
│   │       │   └── index.php
│   │       ├── regime/
│   │       │   ├── liste.php
│   │       │   ├── create.php
│   │       │   └── edit.php
│   │       ├── sport/
│   │       │   ├── liste.php
│   │       │   ├── create.php
│   │       │   └── edit.php
│   │       └── codes/
│   │           └── liste.php
│   └── Config/
│       └── Routes.php
└── public/
    ├── css/
    ├── js/
    └── assets/
```

---

## ✅ TODO LIST GLOBALE

---

### 🔧 CONFIGURATION & SETUP

- [ ] Installer CodeIgniter 4
- [ ] Configurer `.env` (base de données PostgreSQL, base URL)
- [ ] Créer la base de données `imc_db` (exécuter le script SQL fourni)
- [ ] Configurer `app/Config/Database.php` pour PostgreSQL
- [ ] Installer `dompdf` via Composer pour l'export PDF
- [ ] Définir les routes dans `app/Config/Routes.php`
- [ ] Créer les filtres d'authentification (`AuthFilter.php`, `AdminFilter.php`)

---

### 📦 MODÈLES

---

#### `UtilisateurModel.php`
```
Table : utilisateurs
```
- [ ] `getAll()` — Récupère tous les utilisateurs
- [ ] `getById($id)` — Récupère un utilisateur par ID
- [ ] `getByEmail($email)` — Récupère un utilisateur par email
- [ ] `inscrire($data)` — Insère un nouvel utilisateur (étape 1)
- [ ] `update($id, $data)` — Met à jour le profil
- [ ] `delete($id)` — Supprime un utilisateur

---

#### `MesureModel.php`
```
Table : utilisateur_mesures
```
- [ ] `getLastMesure($utilisateur_id)` — Récupère la dernière mesure
- [ ] `getHistorique($utilisateur_id)` — Récupère toutes les mesures
- [ ] `ajouterMesure($utilisateur_id, $poids, $taille)` — Insère une nouvelle mesure

---

#### `ImcModel.php`
```
Tables : historique_imc, imc_categories
```
- [ ] `calculerIMC($poids, $taille)` — Calcule l'IMC (poids / taille²)
- [ ] `getCategorie($imc)` — Retourne la catégorie IMC correspondante
- [ ] `sauvegarderIMC($utilisateur_id, $valeur_imc)` — Insère dans `historique_imc`
- [ ] `getHistoriqueIMC($utilisateur_id)` — Récupère l'historique IMC d'un utilisateur
- [ ] `getImcIdeal($genre)` — Retourne l'IMC idéal selon le genre (homme: 22, femme: 21)
- [ ] `calculerPoidsIdeal($taille, $genre)` — Calcule le poids idéal

---

#### `ObjectifModel.php`
```
Tables : objectifs, utilisateur_objectifs
```
- [ ] `getAll()` — Récupère tous les objectifs disponibles
- [ ] `getObjectifsUtilisateur($utilisateur_id)` — Récupère les objectifs d'un utilisateur
- [ ] `setObjectifs($utilisateur_id, $objectif_ids, $valeurs_cibles)` — Associe jusqu'à 3 objectifs
- [ ] `deleteObjectifsUtilisateur($utilisateur_id)` — Supprime tous les objectifs d'un utilisateur

---

#### `RegimeModel.php`
```
Tables : regimes, recettes, aliments
```
- [ ] `getAll()` — Récupère tous les régimes
- [ ] `getById($id)` — Récupère un régime avec ses aliments
- [ ] `getRegimesParObjectif($objectif_id)` — Filtre les régimes selon l'objectif
- [ ] `getCompositionRegime($regime_id)` — Retourne la composition (% viande, poisson, volaille)
- [ ] `create($data)` — Crée un nouveau régime
- [ ] `update($id, $data)` — Met à jour un régime
- [ ] `delete($id)` — Supprime un régime
- [ ] `getPrixAvecDuree($regime_id, $duree_jours)` — Calcule le prix selon la durée
- [ ] `getPrixAvecRemise($prix, $pourcentage)` — Applique une remise (ex: Gold -15%)
- [ ] `ajouterAliment($regime_id, $aliment_id, $pourcentage)` — Ajoute un aliment à un régime
- [ ] `retirerAliment($regime_id, $aliment_id)` — Retire un aliment d'un régime

---

#### `AlimentModel.php`
```
Table : aliments
```
- [ ] `getAll()` — Récupère tous les aliments
- [ ] `getById($id)` — Récupère un aliment par ID
- [ ] `create($data)` — Crée un aliment
- [ ] `update($id, $data)` — Met à jour un aliment
- [ ] `delete($id)` — Supprime un aliment
- [ ] `getAlimentsByCategorie($categorie)` — Filtre par type (viande, poisson, volaille)

---

#### `SportModel.php`
```
Table : sports
```
- [ ] `getAll()` — Récupère tous les sports
- [ ] `getById($id)` — Récupère un sport par ID
- [ ] `create($data)` — Crée un sport (nom + calories/heure)
- [ ] `update($id, $data)` — Met à jour un sport
- [ ] `delete($id)` — Supprime un sport
- [ ] `calculerCaloriesBrulees($sport_id, $duree_heures)` — Calcule les calories brûlées
- [ ] `getSportsRecommandes($objectif_id, $imc)` — Retourne des sports selon l'objectif et IMC

---

#### `CodePromoModel.php`
```
Tables : codes_promo, utilisateurs_codes
```
- [ ] `getAll()` — Récupère tous les codes promo
- [ ] `getByCode($code)` — Recherche un code promo
- [ ] `isValid($code)` — Vérifie si le code est actif et non expiré
- [ ] `create($data)` — Crée un code promo
- [ ] `utiliserCode($code, $utilisateur_id)` — Marque le code comme utilisé + enregistre dans `utilisateurs_codes`
- [ ] `getMontantCode($code)` — Retourne le montant associé au code
- [ ] `expirer($id)` — Passe le code en statut `expired`
- [ ] `validerCode($id)` — Admin valide un code (passe en `active`)

---

#### `CompteModel.php`
```
Table : comptes
```
- [ ] `getByUtilisateur($utilisateur_id)` — Récupère le compte d'un utilisateur
- [ ] `creerCompte($utilisateur_id)` — Crée un compte lors de l'inscription
- [ ] `getSolde($utilisateur_id)` — Retourne le solde actuel
- [ ] `crediter($utilisateur_id, $montant)` — Ajoute du solde
- [ ] `debiter($utilisateur_id, $montant)` — Retire du solde (vérifier si solde suffisant)
- [ ] `suspendre($utilisateur_id)` — Met le compte en `suspended`

---

#### `TransactionModel.php`
```
Table : transactions
```
- [ ] `getByCompte($compte_id)` — Historique des transactions d'un compte
- [ ] `ajouterTransaction($compte_id, $type, $montant, $description)` — Insère une transaction
- [ ] `getTotalIncome($compte_id)` — Somme des revenus
- [ ] `getTotalExpense($compte_id)` — Somme des dépenses

---

#### `AbonnementModel.php`
```
Tables : abonnements, abonnements_options
```
- [ ] `getOptions()` — Récupère les options d'abonnement (dont Gold)
- [ ] `getAbonnementActif($utilisateur_id)` — Vérifie si l'utilisateur a un abonnement actif
- [ ] `souscrire($utilisateur_id, $option_id)` — Crée un abonnement
- [ ] `hasGold($utilisateur_id)` — Retourne true si l'utilisateur est abonné Gold
- [ ] `createOption($data)` — Admin : crée une option d'abonnement
- [ ] `updateOption($id, $data)` — Admin : modifie une option

---

#### `OffreModel.php`
```
Tables : offres, demandes_offres
```
- [ ] `getAll()` — Récupère toutes les offres
- [ ] `demanderOffre($utilisateur_id, $offre_id)` — Enregistre une demande d'offre
- [ ] `create($data)` — Admin : crée une offre
- [ ] `delete($id)` — Admin : supprime une offre

---

### 🎨 CONTRÔLEURS FRONT OFFICE

---

#### `AuthController.php`
- [ ] `inscriptionEtape1()` — Affiche le formulaire (nom, email, date_naissance, genre)
- [ ] `traiterEtape1()` — Valide et stocke en session, redirige vers étape 2
- [ ] `inscriptionEtape2()` — Affiche le formulaire santé (taille, poids)
- [ ] `traiterEtape2()` — Insère utilisateur + mesure + calcule IMC + crée compte → redirige vers dashboard
- [ ] `login()` — Affiche le formulaire de connexion
- [ ] `traiterLogin()` — Authentifie l'utilisateur, crée la session
- [ ] `logout()` — Détruit la session et redirige

---

#### `ProfilController.php`
- [ ] `index()` — Affiche le profil complet avec IMC actuel
- [ ] `modifier()` — Affiche le formulaire de modification du profil
- [ ] `sauvegarder()` — Met à jour les informations personnelles
- [ ] `mettreAJourMesure()` — Enregistre une nouvelle mesure et recalcule l'IMC
- [ ] `choisirObjectifs()` — Affiche et traite le formulaire de sélection des 3 objectifs

---

#### `DashboardController.php`
- [ ] `index()` — Affiche le tableau de bord utilisateur :
  - IMC actuel + catégorie
  - Objectifs sélectionnés
  - Régimes suggérés
  - Sports recommandés
  - Solde porte-monnaie
  - Bouton export PDF

---

#### `RegimeController.php`
- [ ] `liste()` — Affiche les régimes filtrés selon l'objectif de l'utilisateur
- [ ] `detail($id)` — Affiche le détail d'un régime (composition, prix, durée)
- [ ] `exporterPDF($id)` — Génère un PDF du régime sélectionné avec les recommandations
- [ ] `appliquerRemiseGold($prix)` — Applique -15% si l'utilisateur est Gold

---

#### `SportController.php`
- [ ] `liste()` — Affiche les activités sportives recommandées avec calories/heure

---

#### `PorteMonnaieController.php`
- [ ] `index()` — Affiche le solde et l'historique des transactions
- [ ] `ajouterCode()` — Formulaire de saisie du code promo
- [ ] `traiterCode()` — Valide le code via AJAX, crédite le compte, enregistre la transaction

---

#### `AbonnementController.php`
- [ ] `gold()` — Affiche la page de l'option Gold (prix, avantages)
- [ ] `souscrireGold()` — Débite le compte et crée l'abonnement Gold
- [ ] `verifierSolde($utilisateur_id, $montant)` — Vérifie que le solde est suffisant

---

### 🛠️ CONTRÔLEURS BACK OFFICE

---

#### `AdminAuthController.php`
- [ ] `login()` — Affiche le formulaire de connexion admin
- [ ] `traiterLogin()` — Authentifie l'admin (role_id = 1), crée la session admin
- [ ] `logout()` — Détruit la session admin

---

#### `AdminDashboardController.php`
- [ ] `index()` — Affiche le tableau de bord admin avec :
  - Nombre total d'utilisateurs
  - Répartition par genre (graphe camembert)
  - Évolution des inscriptions (graphe ligne)
  - Répartition des objectifs (graphe barres)
  - Tableau des abonnements Gold actifs
  - Statistiques des codes promo utilisés
  - Top 5 régimes les plus demandés

---

#### `AdminRegimeController.php`
- [ ] `liste()` — Affiche tous les régimes avec pagination
- [ ] `create()` — Formulaire de création d'un régime
- [ ] `store()` — Insère le régime + composition (% viande, poisson, volaille)
- [ ] `edit($id)` — Formulaire de modification
- [ ] `update($id)` — Met à jour le régime
- [ ] `delete($id)` — Supprime le régime
- [ ] `ajouterPrix($regime_id)` — Ajoute un prix pour une durée donnée
- [ ] `supprimerPrix($prix_id)` — Supprime un prix

---

#### `AdminSportController.php`
- [ ] `liste()` — Affiche tous les sports
- [ ] `create()` — Formulaire de création d'un sport
- [ ] `store()` — Insère le sport (nom + calories/heure)
- [ ] `edit($id)` — Formulaire de modification
- [ ] `update($id)` — Met à jour le sport
- [ ] `delete($id)` — Supprime le sport

---

#### `AdminCodeController.php`
- [ ] `liste()` — Affiche tous les codes promo avec statut
- [ ] `create()` — Formulaire de création d'un code
- [ ] `store()` — Insère le code promo
- [ ] `valider($id)` — Valide un code (active → utilisable)
- [ ] `expirer($id)` — Expire manuellement un code
- [ ] `delete($id)` — Supprime un code
- [ ] `listeUtilisations()` — Affiche l'historique d'utilisation des codes

---

#### `AdminAlimentController.php`
- [ ] `liste()` — Affiche tous les aliments
- [ ] `create()` — Formulaire de création d'un aliment
- [ ] `store()` — Insère l'aliment (nom, calories, macros)
- [ ] `edit($id)` — Formulaire de modification
- [ ] `update($id)` — Met à jour l'aliment
- [ ] `delete($id)` — Supprime l'aliment

---

#### `AdminUtilisateurController.php`
- [ ] `liste()` — Affiche tous les utilisateurs
- [ ] `detail($id)` — Affiche le profil complet d'un utilisateur
- [ ] `suspendre($id)` — Suspend le compte d'un utilisateur
- [ ] `activer($id)` — Réactive un compte suspendu

---

### 🔒 FILTRES & SÉCURITÉ

#### `AuthFilter.php`
- [ ] `before()` — Vérifie que l'utilisateur est connecté (session), sinon redirige vers login

#### `AdminFilter.php`
- [ ] `before()` — Vérifie que l'utilisateur connecté a le rôle `admin`, sinon redirige

---

### 🛣️ ROUTES (`app/Config/Routes.php`)

```php
// FRONT OFFICE
$routes->get('/', 'front\AuthController::login');
$routes->get('/inscription/etape1', 'front\AuthController::inscriptionEtape1');
$routes->post('/inscription/etape1', 'front\AuthController::traiterEtape1');
$routes->get('/inscription/etape2', 'front\AuthController::inscriptionEtape2');
$routes->post('/inscription/etape2', 'front\AuthController::traiterEtape2');
$routes->get('/login', 'front\AuthController::login');
$routes->post('/login', 'front\AuthController::traiterLogin');
$routes->get('/logout', 'front\AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'front\DashboardController::index');
    $routes->get('/profil', 'front\ProfilController::index');
    $routes->get('/profil/objectifs', 'front\ProfilController::choisirObjectifs');
    $routes->post('/profil/objectifs', 'front\ProfilController::choisirObjectifs');
    $routes->get('/regimes', 'front\RegimeController::liste');
    $routes->get('/regimes/(:num)', 'front\RegimeController::detail/$1');
    $routes->get('/regimes/(:num)/pdf', 'front\RegimeController::exporterPDF/$1');
    $routes->get('/sports', 'front\SportController::liste');
    $routes->get('/porte-monnaie', 'front\PorteMonnaieController::index');
    $routes->post('/porte-monnaie/code', 'front\PorteMonnaieController::traiterCode');
    $routes->get('/gold', 'front\AbonnementController::gold');
    $routes->post('/gold/souscrire', 'front\AbonnementController::souscrireGold');
});

// BACK OFFICE
$routes->get('/admin', 'back\AdminAuthController::login');
$routes->post('/admin/login', 'back\AdminAuthController::traiterLogin');
$routes->group('/admin', ['filter' => 'admin'], function($routes) {
    $routes->get('/dashboard', 'back\AdminDashboardController::index');
    $routes->resource('regimes', ['controller' => 'back\AdminRegimeController']);
    $routes->resource('sports', ['controller' => 'back\AdminSportController']);
    $routes->resource('codes', ['controller' => 'back\AdminCodeController']);
    $routes->resource('aliments', ['controller' => 'back\AdminAlimentController']);
    $routes->get('utilisateurs', 'back\AdminUtilisateurController::liste');
    $routes->get('utilisateurs/(:num)', 'back\AdminUtilisateurController::detail/$1');
});
```

---

### 🖼️ VUES FRONT OFFICE

#### `front/auth/inscription_etape1.php`
- [ ] Formulaire : nom, email, date_naissance, genre (radio)
- [ ] Validation côté client (JS)
- [ ] Barre de progression Étape 1/2

#### `front/auth/inscription_etape2.php`
- [ ] Formulaire : taille (m), poids (kg)
- [ ] Affichage prévisualisé de l'IMC en temps réel (JS)
- [ ] Barre de progression Étape 2/2

#### `front/auth/login.php`
- [ ] Formulaire email + mot de passe
- [ ] Lien vers inscription

#### `front/dashboard/index.php`
- [ ] Carte IMC avec jauge visuelle (JS/Chart.js)
- [ ] Section objectifs actifs
- [ ] Section régimes suggérés (3 cartes max)
- [ ] Section sports recommandés
- [ ] Solde porte-monnaie
- [ ] Bouton export PDF global

#### `front/profil/objectifs.php`
- [ ] 3 cartes cliquables (prise de masse, perte de poids, IMC idéal)
- [ ] Maximum 3 sélections
- [ ] Champ optionnel valeur cible (ex: poids cible en kg)

#### `front/regime/detail.php`
- [ ] Composition (% viande, poisson, volaille) avec graphe camembert
- [ ] Tableau des prix selon durée
- [ ] Prix barré + prix avec remise Gold si applicable
- [ ] Bouton export PDF

#### `front/abonnement/gold.php`
- [ ] Présentation des avantages Gold
- [ ] Prix de l'abonnement
- [ ] Bouton payer avec le porte-monnaie

---

### 🖼️ VUES BACK OFFICE

#### `back/dashboard/index.php`
- [ ] Widget : total utilisateurs, total régimes, total codes actifs
- [ ] Graphe ligne : inscriptions par mois (Chart.js)
- [ ] Graphe camembert : répartition des objectifs
- [ ] Tableau : top 5 utilisateurs par solde
- [ ] Tableau : codes promo récemment utilisés

#### `back/regime/liste.php`
- [ ] Tableau avec nom, nombre d'aliments, prix de base, actions
- [ ] Bouton Ajouter

#### `back/regime/create.php` & `edit.php`
- [ ] Champs : nom du régime, description, durée en jours, variation de poids attendue (+/-)
- [ ] Section composition : sliders ou champs % viande / poisson / volaille (total = 100%)
- [ ] Section prix par durée (ajout dynamique JS)

#### `back/codes/liste.php`
- [ ] Tableau des codes avec statut coloré (actif = vert, utilisé = gris, expiré = rouge)
- [ ] Bouton valider / expirer par AJAX
- [ ] Bouton créer un nouveau code

---

### 📄 EXPORT PDF

#### `RegimeController::exporterPDF($id)`
- [ ] Générer un PDF via `dompdf` contenant :
  - Informations utilisateur (nom, IMC, objectifs)
  - Détail du régime choisi (composition, durée, prix)
  - Sports recommandés avec calories/heure
  - Disclaimer nutritionnel

---

### 🔄 AJAX / JAVASCRIPT

- [ ] **Validation code promo** : `POST /porte-monnaie/code` → retourne JSON `{success, message, nouveau_solde}`
- [ ] **Calcul IMC en temps réel** : `inscription_etape2.php` → calcul JS sur saisie taille/poids
- [ ] **Prévisualisation remise Gold** : page régime → JS met à jour le prix affiché
- [ ] **Sélection objectifs** : limite JS à 3 cases cochées max
- [ ] **Composition régime** : sliders % sur la page admin → vérifie que total = 100%
- [ ] **Validation/expiration code** : `back/codes/liste.php` → boutons AJAX sans rechargement

---

### 🗃️ DONNÉES INITIALES (Seeds)

- [ ] **5 utilisateurs** avec mesures, objectifs et comptes
- [ ] **15 codes promo** (mix actif/utilisé/expiré)
- [ ] **5 régimes** (Cétogène, Méditerranéen, Végétarien, Hyperprotéiné, Équilibré)
- [ ] **5 activités sportives** (Course, Natation, Vélo, Yoga, Musculation)
- [ ] **Aliments** (viandes, poissons, volailles + légumes)
- [ ] **1 option abonnement Gold** (prix proposé : 29.99€, accès à vie)

---

### 🧪 TESTS À EFFECTUER

- [ ] Inscription en 2 étapes : données session correctement transmises
- [ ] Calcul IMC correct et catégorie affichée
- [ ] Code promo invalide / déjà utilisé / expiré → message d'erreur AJAX
- [ ] Option Gold → remise de 15% appliquée sur les régimes
- [ ] Export PDF généré correctement
- [ ] Admin : CRUD régimes avec composition (total % = 100)
- [ ] Admin : CRUD sports
- [ ] Admin : validation code promo
- [ ] Filtres d'authentification : pages protégées inaccessibles sans connexion

---

### 📋 RÉCAPITULATIF DES PRIORITÉS

| Priorité | Tâche |
|----------|-------|
| 🔴 Critique | Authentification front + back |
| 🔴 Critique | Inscription 2 étapes + calcul IMC |
| 🔴 Critique | Modèles de base (Utilisateur, Mesure, IMC) |
| 🟠 Haute | Sélection objectifs + suggestions régimes/sports |
| 🟠 Haute | CRUD régimes + sports (back office) |
| 🟠 Haute | Porte-monnaie + codes promo |
| 🟡 Moyenne | Option Gold + remise 15% |
| 🟡 Moyenne | Export PDF |
| 🟡 Moyenne | Dashboard admin avec graphes |
| 🟢 Faible | Seeds / données initiales |
| 🟢 Faible | Statistiques avancées back office |