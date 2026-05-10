# 🗺️ CARTE DE NAVIGATION - Système d'Offres & Abonnements

## 🖥️ INTERFACE ADMINISTRATEUR

### 📦 Gestion des Offres
```
/admin/offres                    - Liste de toutes les offres
/admin/offres/create             - Créer une nouvelle offre
/admin/offres/edit/:id           - Éditer une offre
```

**Fonctionnalités:**
- Créer/Éditer/Supprimer offres
- Gérer types (régime, sport, combo)
- Définir prix normal et prix Gold
- Ajouter descriptions

---

### 📋 Gestion des Demandes d'Offres
```
/admin/offres/demandes/all       - Voir toutes les demandes
/admin/offres/demandes/:id       - Détail d'une demande
```

**Fonctionnalités:**
- Voir demandes en attente
- Voir régime/sport suggérés
- Accepter avec prix calculé
- Rejeter demande

---

### ⭐ Gestion des Abonnements - Options
```
/admin/abonnements/options                      - Liste options
/admin/abonnements/options/create               - Créer option
/admin/abonnements/options/edit/:id             - Éditer option
```

**Fonctionnalités:**
- Créer/Éditer/Supprimer options d'abonnement
- Définir prix mensuel
- Ajouter descriptions (Gold, Diamond, etc)

---

### 👥 Gestion des Abonnements - Utilisateurs
```
/admin/abonnements/utilisateurs                 - Voir tous utilisateurs
/admin/abonnements/utilisateurs/assign/:id      - Assigner abonnement
```

**Fonctionnalités:**
- Voir abonnements actuels
- Attribuer Gold/Diamond à user
- Définir date d'expiration
- Voir statut activation

---

### 📊 Statistiques & Dashboard
```
/admin/abonnements/stats         - Voir statistiques système
```

**Affiche:**
- Total utilisateurs
- Utilisateurs Gold (%)
- Total offres
- Demandes en attente
- Demandes acceptées
- Revenu total

---

## 👤 INTERFACE UTILISATEUR

### 🎯 Accès aux Offres

#### Page Principale - Toutes les Offres
```
/offres
```

**Affichage:**
- Grille de 6 offres disponibles
- Type, description, prix
- Prix Gold (si utilisateur Gold)
- Liste des demandes de l'utilisateur
- Bouton "Demander cette offre"

---

#### Suggestions Personnalisées
```
/offres/suggestions
```

**Affichage:**
- Objectif actif de l'utilisateur
- 3 offres recommandées
- Première offre marquée "RECOMMANDÉE"
- Algorithme intelligent par objectif:
  - Prise de masse → Régime+Sport prioritaire
  - Perte de poids → Sport prioritaire
  - Maintien → Équilibré

---

#### Détail d'une Demande
```
/offres/detail/:id
```

**Affichage:**
- Informations complètes
- Régime/Sport suggérés
- Prix payé (si acceptée)
- Statut (demande/acceptée/rejetée)
- Boutons action (si en attente)

---

## 🔄 WORKFLOW COMPLET

### User Flow
```
1. User va à /offres
   ↓ Voir toutes offres avec prix

2. Cliquer "✨ Voir suggestions"
   ↓ Aller à /offres/suggestions

3. Vérifier offres recommandées
   ↓ Basées sur son objectif actif

4. Cliquer "Demander cette offre"
   ↓ Régime/sport auto-sélectionnés

5. Demande créée (statut='demande')
   ↓ Attendre validation admin

6. Admin accepte
   ↓ Voir détail à /offres/detail/:id
   ↓ Statut devient 'acceptée'
   ↓ Prix sauvegardé
```

### Admin Flow
```
1. Admin va à /admin/offres/demandes/all
   ↓ Voir toutes les demandes en attente

2. Cliquer "Voir" sur une demande
   ↓ Aller à /admin/offres/demandes/:id

3. Voir détails complètes
   ↓ Utilisateur, offre, régime, sport

4. Cliquer "✓ Accepter" ou "✕ Rejeter"
   ↓ Prix calculé automatiquement (avec Gold si applicable)
   ↓ Demande mise à jour
   ↓ Retour à liste demandes
```

---

## 💰 EXEMPLE DE PRIX

### Offre: "Cardio Intensif" (49.99€)

**Utilisateur Normal:**
- Prix affiché: **49.99€**
- Prix payé: **49.99€**

**Utilisateur Gold:**
- Prix normal barré: ~~49.99€~~
- Prix Gold: **42.99€** (-14%)
- Remise: **-7.00€**

---

## 🎨 TYPES D'OFFRES

### Régime 🍽️
- Exemple: "Régime Protéiné"
- Contient: Plan de repas
- Suggestion: Régime seul

### Sport 🏃
- Exemple: "Cardio Intensif"
- Contient: Programme d'entraînement
- Suggestion: Sport seul

### Régime + Sport 🍽️🏃
- Exemple: "Pack Complet Fitness Gold"
- Contient: Régime + Sport
- Suggestion: Les deux combinés

---

## 🔐 ACCÈS REQUIS

### Routes Admin
- **Filtre:** admin (géré par middleware)
- **Accès:** Admin uniquement
- **Protection:** Vérification session + rôle

### Routes User
- **Filtre:** aucun (public)
- **Accès:** Utilisateurs connectés
- **Protection:** Vérification session user

---

## 📱 RESPONSIVE

Tous les interfaces sont:
- ✅ Desktop: Pleine largeur
- ✅ Tablet: Grille 2 colonnes
- ✅ Mobile: Colonne simple
- ✅ Touch-friendly: Boutons grands

---

## 🌐 ACCÈS COMPLET

### Développement Local
```
Admin:    http://localhost:8080/admin/offres
User:     http://localhost:8080/offres
Sugges:   http://localhost:8080/offres/suggestions
```

### Production (exemple)
```
Admin:    https://votresite.com/admin/offres
User:     https://votresite.com/offres
Sugges:   https://votresite.com/offres/suggestions
```

---

## 📋 STRUCTURE URL

```
/admin/
├── offres/
│   ├── [LIST offres]
│   ├── create [CREATE form]
│   ├── store [STORE POST]
│   ├── edit/:id [UPDATE form]
│   ├── update/:id [UPDATE POST]
│   ├── delete/:id [DELETE POST]
│   └── demandes/
│       ├── all [LIST demandes]
│       ├── :id [VIEW detai]
│       ├── accept/:id [ACCEPT POST]
│       └── reject/:id [REJECT POST]
│
└── abonnements/
    ├── options/ [CRUD options]
    ├── utilisateurs/ [CRUD abosCRUD utilisateurs]
    └── stats/ [DASHBOARD]

/
├── offres [LIST + demandes]
├── offres/suggestions [3 suggestions]
├── offres/detail/:id [Détail demande]
├── offres/demand/:id [Créer demande]
├── offres/accept/:id [Accepter]
└── offres/reject/:id [Rejeter]
```

---

## 🎯 ACCÈS RAPIDE

| Page | URL | Rôle | Description |
|------|-----|------|-------------|
| Offres Admin | `/admin/offres` | Admin | Gérer offres |
| Demandes Admin | `/admin/offres/demandes/all` | Admin | Valider demandes |
| Abonnements | `/admin/abonnements/utilisateurs` | Admin | Gold management |
| Statistiques | `/admin/abonnements/stats` | Admin | Dashboard |
| Offres User | `/offres` | User | Voir offres |
| Suggestions | `/offres/suggestions` | User | Recommandations |
| Demande | `/offres/detail/:id` | User | Voir détail |

---

## 🔍 AIDE RAPIDE

**Je veux:**
- Créer une offre → `/admin/offres/create`
- Ajouter Gold à un user → `/admin/abonnements/utilisateurs`
- Accepter une demande → `/admin/offres/demandes/all`
- Voir mes offres → `/offres`
- Voir suggestions → `/offres/suggestions`
- Voir ma demande → `/offres/detail/:id`

---

**Version**: 1.0  
**Date**: 11 mai 2026  
**Mise à jour**: Prêt production ✅
