<?php
try {
    // Connexion au serveur PGSQL (sans base de données spécifiée pour pouvoir la créer)
    $dsn = "pgsql:host=127.0.0.1;port=5433;user=postgres;password=postgres";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la base existe
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'livegood_bd'");
    if (!$stmt->fetch()) {
        $pdo->exec("CREATE DATABASE livegood_bd");
        echo "Base de données 'livegood_bd' créée avec succès.\n";
    } else {
        echo "La base de données 'livegood_bd' existe déjà.\n";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
