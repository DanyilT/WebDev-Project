<?php
/**
 * DB connection
 * This file is responsible for establishing a connection to the database.
 * It uses the PDO extension to connect to a MySQL database.
 *
 * @package Database
 *
 * @var array $config Database configuration settings
 * @var PDO $connection Database connection object
 */

$config = require_once __DIR__ . '/../../config.php';

try {
    $dsn = "mysql:host=$config[host];dbname=$config[dbname]";
    $connection = new PDO($dsn, $config['user'], $config['password'], $config['options']);
    // echo 'DB connected';
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
