<?php

namespace Controllers\Auth;

use Controllers\User\UserController;
use Exception;
use PDO;

require_once __DIR__ . '/../../Controllers/User/UserController.php';

/**
 * Class AuthController
 * Handles user authentication, including registration, login, and logout.
 *
 * @package Controllers\Auth
 */
class AuthController {
    private ?PDO $connection;
    private UserController $userController;

    /**
     * AuthController constructor
     * This constructor initializes the database connection and the UserController.
     *
     * @param ?PDO $connection Can be `null` only if you use this constructor only for `logout()` method
     */
    public function __construct(?PDO $connection) {
        $this->connection = $connection;
        if ($this->connection !== null)
            $this->userController = new UserController($connection);
    }

    /**
     * Register a new user
     * This method creates a new user in the database and optionally logs them in.
     * It provides detailed validation feedback for each credential.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     * @param bool $autologin Whether to log the user in after registration (default: true)
     *
     * @return array Return ['status' => 'success'] or ['status' => 'error', 'field' => '...', 'message' => '...']
     */
    public function register(string $username, string $password, string $email, string $name, bool $autologin = true): array {
        // Validate all fields and collect error messages
        $errors = $this->validateRegistrationData($username, $password, $email, $name);

        // If there are validation errors, return the first one
        if (!empty($errors)) {
            return $errors[0];
        }

        try {
            $username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($username)));
            $this->userController->createUser($username, $password, $email, $name);

            if ($autologin) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['auth']['user_id'] = $this->userController->getUserId($username);
                $_SESSION['auth']['username'] = $username;
            }

            return ['status' => 'success', 'message' => 'User registered successfully'];
        } catch (Exception $e) {
            return ['status' => 'error', 'field' => 'general', 'message' => $e->getMessage()];
        }
    }

    /**
     * Validate all registration data and collect specific error messages
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     *
     * @return array Array of error messages, empty if no errors
     */
    private function validateRegistrationData(string $username, string $password, string $email, string $name): array {
        $errors = [];

        // Validate username
        $usernameValidation = $this->userController->isValidUsername($username);
        if ($usernameValidation['status'] === 'error') {
            $errors[] = [
                'status' => 'error',
                'field' => 'username',
                'message' => $usernameValidation['message'] ?? 'Invalid username'
            ];
        }

        // Validate password
        $passwordValidation = $this->userController->isValidPassword($password);
        if ($passwordValidation['status'] === 'error') {
            $errors[] = [
                'status' => 'error',
                'field' => 'password',
                'message' => $passwordValidation['message'] ?? 'Invalid password'
            ];
        }

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = [
                'status' => 'error',
                'field' => 'email',
                'message' => 'Please enter a valid email address'
            ];
        }

        // Validate name
        if (empty($name) || strlen($name) < 2) {
            $errors[] = [
                'status' => 'error',
                'field' => 'name',
                'message' => 'Name must be at least 2 characters long'
            ];
        }

        return $errors;
    }

    /**
     * Login a user
     * This method checks the provided credentials and logs the user in if valid.
     * For security reasons, it provides a generic error message when login fails.
     *
     * @param string $username
     * @param string $password
     *
     * @return array|string[] Return ['status' => 'success'] or ['status' => 'error']
     */
    public function login(string $username, string $password): array {
        $username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($username)));

        try {
            // Check if login is valid without giving specific error messages
            if ($this->validateLogin($username, $password)) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['auth']['user_id'] = $this->userController->getUserId($username);
                $_SESSION['auth']['username'] = $username;
                return ['status' => 'success', 'message' => 'Login successful'];
            } else {
                // Generic error message that doesn't reveal if username exists or password is incorrect
                return ['status' => 'error', 'message' => 'Invalid credentials. Please try again.'];
            }
        } catch (Exception $e) {
            // Log the actual error for debugging but return generic message to user
            error_log("Login error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Invalid credentials. Please try again.'];
        }
    }

    /**
     * Logout a user
     * This method destroys the session and logs the user out.
     *
     * @return array|string[] Return ['status' => 'success', 'message' => 'Logout successful']
     */
    public function logout(): array {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        return ['status' => 'success', 'message' => 'Logout successful'];
    }

    /**
     * Validate login credentials
     * This method checks if the provided username and password are valid.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    private function validateLogin(string $username, string $password): bool {
        if (empty($username) || empty($password)) {
            return false;
        }

        // If username doesn't exist, return false without throwing exception
        if (!$this->userController->isUsernameExist($username)) {
            return false;
        }

        $userProfile = $this->userController->getUserProfile($username);
        if (!$userProfile) {
            return false;
        }

        // Verify password without throwing exception on failure
        return password_verify($password, $this->userController->getUserPassword($username));
    }
}