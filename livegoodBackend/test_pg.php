<?php
try {
    $pdo = new PDO("pgsql:host=127.0.0.1;port=5433;user=postgres;password=postgres");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'livegood_bd'");
    if (!$stmt->fetch()) {
        $pdo->exec("CREATE DATABASE livegood_bd");
        echo "Database livegood_bd created successfully.\n";
    } else {
        echo "Database livegood_bd already exists.\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
