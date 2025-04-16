<?php

namespace Models\User;

use PDO;

require_once 'User.php';

/**
 * Class UserDelete
 * Handles user deletion operations, including soft deletion and permanent deletion.
 * This class extends the User class and provides methods to validate user data,
 *
 * @package Models\User
 */
class UserDelete extends User {
    /**
     * UserDelete constructor.
     * Sets the database connection.
     *
     * @param $connection PDO
     */
    public function __construct(PDO $connection) {
        parent::__construct($connection);
    }

    /**
     * Soft deletes a user by setting the is_deleted flag to true
     *
     * @param int $userId
     *
     * @return bool
     */
    public function deleteUser(int $userId): bool {
        // Fetch current username
        $stmt = $this->getConnection()->prepare("SELECT username FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $currentUsername = $stmt->fetchColumn();

        // Remove '@' symbol if present and add '-' prefix
        $newUsername = '-' . str_replace('@', '', $currentUsername);

        // Prepare statement for checking duplicate username
        $checkStmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM users WHERE username = ?");

        // Keep adding '-' until unique
        while (true) {
            $checkStmt->execute([$newUsername]);
            if ($checkStmt->fetchColumn() == 0) {
                break;
            }
            $newUsername = '-' . $newUsername;
        }

        // Update user: mark as deleted and update username
        $stmt = $this->getConnection()->prepare("UPDATE users SET is_deleted = TRUE, username = ? WHERE user_id = ?");

        $result = $stmt->execute([$newUsername, $userId]);

        $this->setDataChangesHistory($userId, ['delete' => ['is_deleted' => 1, 'username' => $newUsername, 'timestamp' => date('Y-m-d H:i:s')]]);

        return $result;
    }

    /**
     * Permanently deletes a user from the database
     *
     * @param int $userId
     *
     * @return bool
     */
    public function actuallyDeleteUser(int $userId): bool {
        if ($_SESSION['admin_authenticated']) {
            $stmt = $this->getConnection()->prepare("DELETE FROM users WHERE user_id = ?");
            return $stmt->execute([$userId]);
        }
        return false;
    }

    /**
     * Checks if a username already exists in the database to avoid duplication (error: username must be unique)
     *
     * @param string $username
     * @param PDO $connection
     *
     * @return bool
     */
    public function isUsernameExist(string $username, PDO $connection): bool {
        if (!str_starts_with($username, '@')) {
            $username = '@' . $username;
        }
        // Prepare the SQL statement and execute it
        $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
