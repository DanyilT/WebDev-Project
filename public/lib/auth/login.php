<?php
/**
 * File: login.php
 * This file handles user login.
 *
 * @package public/lib/auth
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

use Controllers\Auth\AuthController;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Auth/AuthController.php';

// Set up variables
$username = $_POST['username'];
$password = $_POST['password'];

// Process login
if ((new AuthController($connection))->login($username, $password)['status'] === 'success') {
    header('Location: /auth.php');
} else {
    header('Location: /auth.php?error=invalid_credentials');
}
exit();
