<?php
$config = require 'config.php';

$pdo = createDatabaseConnection($config);

try {
    // Create / Initialize database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['dbname']}");
    $sql = file_get_contents('data/init.sql');
    $pdo->exec($sql);
    echo "Database `{$config['dbname']}` & tables created successfully\n";
} catch (PDOException $e) {
    error_log("Database initialization error: " . $e->getMessage());
    die("Database initialization error. Please check the logs for more details.");
}

function createDatabaseConnection($config) {
    try {
        $pdo = new PDO("mysql:host={$config['host']}", $config['user'], $config['password'], $config['options']);
        echo "Connected successfully\n";
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        die("Database connection error. Please check the logs for more details.");
    }
}
