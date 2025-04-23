<?php
/**
 * File: login.php
 * This file handles user login.
 * Updated with improved error handling and UI feedback.
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
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($username) || empty($password)) {
    $errorMessage = 'Username and password are required.';
    header('Location: /auth.php?error=' . urlencode($errorMessage) . '#login');
    exit();
}

// Process login
try {
    $loginResult = (new AuthController($connection))->login($username, $password);

    if ($loginResult['status'] === 'success') {
        // Set a success notification
        $redirectUrl = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/index.php';
        unset($_SESSION['redirect_after_login']);

        // Add success parameter for notification
        $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'success=' . urlencode('Welcome back! You are now logged in.');

        header('Location: ' . $redirectUrl);
    } else {
        $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'login')) ? '&login' : '#login';
        $errorMessage = $loginResult['message'];
        header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
    }
} catch (Exception $e) {
    // Log the exception
    error_log('Login error: ' . $e->getMessage());

    // Return generic error message
    $errorMessage = 'An error occurred during login. Please try again.';
    header('Location: /auth.php?error=' . urlencode($errorMessage) . '#login');
}

exit();