<?php
/**
 * File: register.php
 * This file handles user registration.
 * Updated with improved validation and UI feedback.
 *
 * @package public/lib/auth
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

use Controllers\Auth\AuthController;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Auth/AuthController.php';

// Validate all required fields are present
$requiredFields = ['username', 'email', 'name', 'password', 'accept-terms'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'register')) ? '&register' : '#register';
    $errorMessage = 'Please fill in all required fields: ' . implode(', ', $missingFields);
    header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
    exit();
}

// Terms must be accepted
if (!isset($_POST['accept-terms'])) {
    $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'register')) ? '&register' : '#register';
    $errorMessage = 'You must accept the Terms and Conditions to register.';
    header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
    exit();
}

// Set up variables
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$name = trim($_POST['name']);
$password = $_POST['password'];

// Process registration
try {
    $registrationResult = (new AuthController($connection))->register($username, $password, $email, $name, true);

    if ($registrationResult['status'] === 'success') {
        // Registration successful - redirect based on auto-login setting
        if (isset($_SESSION['auth']['user_id'])) {
            // Auto-login was successful, redirect to homepage with welcome message
            header('Location: /index.php?success=' . urlencode('Registration successful! Welcome to QWERTY.'));
        } else {
            // No auto-login, redirect to login page
            header('Location: /auth.php?registered=1#login');
        }
    } else {
        // Field-specific error handling
        $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'register')) ? '&register' : '#register';
        $errorField = $registrationResult['field'] ?? 'general';
        $errorMessage = $registrationResult['message'];

        // Store the validation error in session to display it properly
        session_start();
        $_SESSION['register_error'] = [
            'field' => $errorField,
            'message' => $errorMessage
        ];

        // Preserve form data for repopulating the form
        $_SESSION['register_form_data'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name
        ];

        header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
    }
} catch (Exception $e) {
    // Log the exception
    error_log('Registration error: ' . $e->getMessage());

    $page = (str_contains($_SERVER['HTTP_REFERER'] ?? '', 'register')) ? '&register' : '#register';
    $errorMessage = 'An error occurred during registration. Please try again.';
    header('Location: /auth.php?error=' . urlencode($errorMessage) . $page);
}

exit();
