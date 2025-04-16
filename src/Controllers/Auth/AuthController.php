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
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     * @param bool $autologin Whether to log the user in after registration (default: true)
     *
     * @return array|string[] Return ['status' => 'success'] or ['status' => 'error']
     */
    public function register(string $username, string $password, string $email, string $name, bool $autologin = true): array {
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
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Login a user
     * This method checks the provided credentials and logs the user in if valid.
     *
     * @param string $username
     * @param string $password
     *
     * @return array|string[] Return ['status' => 'success'] or ['status' => 'error']
     */
    public function login(string $username, string $password): array {
        $username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($username)));
        if ($this->validateLogin($username, $password)) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['auth']['user_id'] = $this->userController->getUserId($username);
            $_SESSION['auth']['username'] = $username;
            return ['status' => 'success', 'message' => 'Login successful'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid credentials'];
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
        if (!$this->userController->isUsernameExist($username)) {
            return false;
        }
        $userProfile = $this->userController->getUserProfile($username);
        if (!$userProfile) {
            return false;
        }
        return password_verify($password, $this->userController->getUserPassword($username));
    }
}
