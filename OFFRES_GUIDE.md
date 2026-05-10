# 📋 Système d'Offres & Abonnements Gold - Guide d'Installation

## 🚀 Commandes SQL à Exécuter

### 1. Ajouter les colonnes manquantes

```sql
-- Ajouter les colonnes de statut aux objectifs
ALTER TABLE utilisateur_objectifs 
ADD COLUMN IF NOT EXISTS statut VARCHAR(20) DEFAULT 'en_cours',
ADD COLUMN IF NOT EXISTS date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS date_fin TIMESTAMP;

-- Vérifier que les colonnes de la table offres existent
ALTER TABLE offres
ADD COLUMN IF NOT EXISTS type VARCHAR(20) DEFAULT 'regime',
ADD COLUMN IF NOT EXISTS description TEXT,
ADD COLUMN IF NOT EXISTS prix_gold NUMERIC(10, 2),
ADD COLUMN IF NOT EXISTS date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Ajouter les colonnes manquantes à demandes_offres
ALTER TABLE demandes_offres
ADD COLUMN IF NOT EXISTS regime_id INT REFERENCES regimes (id) ON DELETE SET NULL,
ADD COLUMN IF NOT EXISTS sport_id INT REFERENCES sports (id) ON DELETE SET NULL,
ADD COLUMN IF NOT EXISTS statut VARCHAR(20) DEFAULT 'demande',
ADD COLUMN IF NOT EXISTS prix_paye NUMERIC(10, 2),
ADD COLUMN IF NOT EXISTS date_acceptation TIMESTAMP;
```

### 2. Insérer les options d'abonnement

```sql
-- Ajouter les options d'abonnement
INSERT INTO abonnements_options (nom, prix, description) VALUES
('Gratuit', 0.00, 'Accès de base'),
('Gold', 9.99, 'Accès premium avec remise de 14% sur les offres'),
('Diamond', 19.99, 'Accès VIP avec remise 20% et support prioritaire')
ON CONFLICT (nom) DO NOTHING;
```

### 3. Insérer des offres exemples

```sql
-- Ajouter les offres
INSERT INTO offres (nom, type, description, prix, prix_gold) VALUES
('Programme de perte - Cardio', 'sport', 'Programme de cardio intensif pour perte de poids', 49.99, 42.99),
('Régime protéiné', 'regime', 'Régime riche en protéines pour prise de masse', 39.99, 34.39),
('Pack complet - Fitness Gold', 'regime_sport', 'Régime + sport combinés pour résultats optimaux', 89.99, 77.39),
('Sport doux - Bien-être', 'sport', 'Activités douces et régulières pour maintien', 29.99, 25.79),
('Régime équilibré', 'regime', 'Régime varié et équilibré pour santé globale', 44.99, 38.69)
ON CONFLICT DO NOTHING;
```

### 4. Vérifier les données

```sql
-- Vérifier les abonnements
SELECT * FROM abonnements_options ORDER BY prix;

-- Vérifier les offres
SELECT COUNT(*) as total_offres FROM offres;
SELECT nom, type, prix, prix_gold FROM offres;

-- Vérifier les demandes
SELECT COUNT(*) as total_demandes FROM demandes_offres;
```

---

## 🖥️ Interfaces Admin

### Routes Admin pour les Offres
- **GET** `/admin/offres` - Liste des offres
- **GET** `/admin/offres/create` - Formulaire création
- **POST** `/admin/offres/store` - Créer offre
- **GET** `/admin/offres/edit/:id` - Formulaire édition
- **POST** `/admin/offres/update/:id` - Mettre à jour
- **POST** `/admin/offres/delete/:id` - Supprimer
- **GET** `/admin/offres/demandes/all` - Toutes les demandes
- **GET** `/admin/offres/demandes/:id` - Détail demande
- **POST** `/admin/offres/demandes/accept/:id` - Accepter demande
- **POST** `/admin/offres/demandes/reject/:id` - Rejeter demande

### Routes Admin pour les Abonnements
- **GET** `/admin/abonnements/options` - Options d'abonnement
- **GET** `/admin/abonnements/options/create` - Créer option
- **POST** `/admin/abonnements/options/store` - Stocker option
- **GET** `/admin/abonnements/options/edit/:id` - Éditer option
- **POST** `/admin/abonnements/options/update/:id` - Mettre à jour option
- **POST** `/admin/abonnements/options/delete/:id` - Supprimer option
- **GET** `/admin/abonnements/utilisateurs` - Gérer abonnements utilisateurs
- **GET** `/admin/abonnements/utilisateurs/assign/:id` - Assigner abonnement
- **POST** `/admin/abonnements/utilisateurs/store/:id` - Créer abonnement
- **POST** `/admin/abonnements/utilisateurs/cancel/:id` - Annuler abonnement
- **GET** `/admin/abonnements/stats` - Statistiques

---

## 👥 Interfaces Utilisateur

### Routes Utilisateur pour les Offres
- **GET** `/offres` - Liste toutes les offres
  - Affiche les offres disponibles
  - Montre les demandes de l'utilisateur
  - Applique remise Gold si applicable
  
- **GET** `/offres/suggestions` - Suggestions personnalisées
  - 3 offres recommandées selon l'objectif actif
  - Algorithme intelligent (prise/perte/maintien)
  - Marque la première comme "RECOMMANDÉE"
  
- **POST** `/offres/demand/:id` - Créer une demande
  - Auto-suggestion de régime/sport
  - Crée demande avec statut 'demande'
  
- **GET** `/offres/detail/:id` - Détail d'une demande
  - Affiche régime/sport suggérés
  - Boutons d'acceptation/rejet si statut 'demande'
  
- **POST** `/offres/accept/:id` - Accepter demande
  - Calcule prix avec remise Gold si applicable
  - Mise à jour statut à 'acceptée'
  
- **POST** `/offres/reject/:id` - Rejeter demande
  - Mise à jour statut à 'rejetée'

---

## ⚙️ Fonctionnalités Clés

### Système de Remise Gold
- **Prix normal** : affiché à tous les utilisateurs
- **Prix Gold** : -14% du prix normal pour utilisateurs Gold
- **Calcul automatique** : `prix_gold = prix_normal * 0.86`
- **Exemple** : 
  - Prix normal: 49.99€
  - Prix Gold: 42.99€ (remise de 7€)

### Algorithme de Suggestions
1. **Prise de masse** :
   - 1ère priorité: offres regime_sport
   - 2ème priorité: offres regime
   - 3ème priorité: offres sport

2. **Perte de poids** :
   - 1ère priorité: offres sport
   - 2ème priorité: offres regime_sport
   - 3ème priorité: offres regime

3. **Maintien** :
   - Ordre par défaut: regime_sport → regime → sport

### Workflow des Demandes
```
Demande créée (statut='demande')
        ↓
Admin accepte ou rejette (statut='acceptée' ou 'rejetée')
        ↓
Prix calculé et sauvegardé
```

---

## 📊 Statistiques Admin

Le dashboard affiche:
- Total utilisateurs
- Utilisateurs Gold (avec %)
- Total offres disponibles
- Demandes en attente
- Demandes acceptées
- Revenu total généré

---

## 🔒 Contrôles de Sécurité

✅ Vérification que l'utilisateur possède la demande  
✅ Filtre admin pour accès routes admin  
✅ Validation des données en entrée  
✅ Gestion d'erreurs appropriée  
✅ Confirmation avant suppression  

---

## 💾 Migrations CodeIgniter

Pour appliquer les migrations automatiquement:
```bash
php spark migrate
```

Les migrations créées:
- `2026-05-11-100000_AddObjectifFields.php`
- `2026-05-11-110000_UpdateOffresTable.php`

---

## 🧪 Tests Manuels

### Test 1: Créer une offre (Admin)
1. Aller à `/admin/offres`
2. Cliquer "+ Nouvelle Offre"
3. Remplir: Nom, Type, Prix, Prix Gold
4. Soumettre

### Test 2: Créer option abonnement (Admin)
1. Aller à `/admin/abonnements/options`
2. Cliquer "+ Nouvelle Option"
3. Remplir: Nom, Prix
4. Soumettre

### Test 3: Assigner Gold à utilisateur (Admin)
1. Aller à `/admin/abonnements/utilisateurs`
2. Cliquer "📝 Gérer" sur un utilisateur
3. Sélectionner "Gold"
4. Soumettre

### Test 4: Voir suggestions (User)
1. S'assurer d'avoir un objectif actif
2. Aller à `/offres`
3. Cliquer "✨ Voir les suggestions"
4. Vérifier prix Gold si applicable

### Test 5: Créer demande (User)
1. Cliquer "Demander cette offre"
2. Vérifier créa tion dans `/admin/offres/demandes/all`
3. Admin accepte la demande
4. Vérifier prix paye

---

## 📝 Notes

- Les prix Gold sont calculés avec 14% de remise
- Les régime/sport sont auto-suggérés selon l'objectif
- Admin peut accepter/rejeter via le détail de demande
- Statistics mises à jour en temps réel
- Toutes les dates sont en UTC

---

**Version**: 1.0  
**Date**: 11 mai 2026  
**Statut**: ✅ Prêt pour production
