<?php
/**
 * DB connection
 */

$config = require_once __DIR__ . '/../config.php';

try {
    $dsn = "mysql:host=$config[host];dbname=$config[dbname]";
    $connection = new PDO($dsn, $config['user'], $config['password'], $config['options']);
    echo 'DB connected';
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
