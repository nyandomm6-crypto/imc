#!/bin/bash

# ============================================
# SCRIPT DE DÉPLOIEMENT - SYSTÈME D'OFFRES
# ============================================
# Exécutez ce script pour déployer complètement le système

echo "🚀 Déploiement du système d'offres et abonnements..."
echo ""

# 1. Exécuter les migrations CodeIgniter
echo "1️⃣ Exécution des migrations..."
php spark migrate
echo "✅ Migrations exécutées"
echo ""

# 2. Afficher les statistiques
echo "2️⃣ Vérification des données..."
psql -U postgres -d imc_db -c "
SELECT 
    'Offres' as type, COUNT(*) as total FROM offres
UNION ALL
SELECT 'Abonnements Options', COUNT(*) FROM abonnements_options
UNION ALL
SELECT 'Demandes d''offres', COUNT(*) FROM demandes_offres;
"
echo "✅ Données vérifiées"
echo ""

echo "════════════════════════════════════════"
echo "✅ DÉPLOIEMENT TERMINÉ !"
echo "════════════════════════════════════════"
echo ""
echo "📍 Accès aux interfaces:"
echo "   🖥️  Admin - Offres:        /admin/offres"
echo "   🖥️  Admin - Demandes:      /admin/offres/demandes/all"
echo "   🖥️  Admin - Abonnements:   /admin/abonnements/utilisateurs"
echo "   🖥️  Admin - Statistiques:  /admin/abonnements/stats"
echo "   👤 User - Offres:         /offres"
echo "   👤 User - Suggestions:    /offres/suggestions"
echo ""
echo "📚 Documentation: OFFRES_GUIDE.md"
echo "🔧 SQL brut: sql-setup.sql"
echo ""
