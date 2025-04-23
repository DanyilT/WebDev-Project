<?php
/**
 * File: register.php
 * This file handles user registration.
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
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

// Process registration
$registrationResult = (new AuthController($connection))->register($username, $password, $email, $name, true);

if ($registrationResult['status'] === 'success') {
    header('Location: /auth.php');
} else {
    $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'register')) ? '&register' : '#register';
    $errorMessage = $registrationResult['message'];
    header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
}
exit();
