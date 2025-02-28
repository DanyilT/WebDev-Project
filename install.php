<?php
$config = require 'config.php';
global $sql;

try {
    // Create connection
    $pdo = new PDO("mysql:host=$config[host]", $config['user'], $config['password'], $config['options']);
    echo "Connected successfully\n";

    // Create / Initialize database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $config[dbname]");
    //$pdo = new PDO("mysql:host=$config[host];dbname=$config[dbname]", $config['user'], $config['password'], $config['options']);
    $sql = file_get_contents('data/init.sql');
    $pdo->exec($sql);
    echo "Database `$config[dbname]` & tables created successfully\n";
} catch (PDOException $e) {
    die($sql . "<br>" . $e->getMessage());
}
