<?php

require 'vendor/autoload.php';

$config = require 'app/Config/Database.php';
$db = \Config\Database::connect();

$sql = file_get_contents('donnees_recettes.sql');

// Diviser le SQL en requêtes individuelles
$queries = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;

foreach($queries as $query) {
    if(!empty($query)) {
        try {
            $db->query($query);
            echo "✓ Executed: " . substr($query, 0, 50) . "..." . PHP_EOL;
            $successCount++;
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . PHP_EOL;
            $errorCount++;
        }
    }
}

echo PHP_EOL;
echo "Résultats: $successCount réussites, $errorCount erreurs" . PHP_EOL;