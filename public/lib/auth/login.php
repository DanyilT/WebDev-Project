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
$loginResult = (new AuthController($connection))->login($username, $password);

if ($loginResult['status'] === 'success') {
    header('Location: /auth.php');
} else {
    $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'login')) ? '&login' : '#login';
    $errorMessage = $loginResult['message'];
    header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
}
exit();
