<?php

namespace Models\User;

require_once 'User.php';

/**
 * Class UserUpdate
 * @package Models\User
 *
 * This class is responsible for updating user data in the database.
 * It allows for updating user profiles, followers, followings, and other related information.
 */
class UserUpdate extends User {
    /**
     * @param $connection \PDO
     */
    public function __construct($connection) {
        parent::__construct($connection);
    }

    /**
     * Updates user information in the database
     *
     * @param int $userId
     * @param array $fields
     *
     * @return bool
     */
    public function updateUser(int $userId, array $fields): bool {
        // Build a dynamic SQL statement
        $setParts = [];
        $values = [];
        foreach ($fields as $column => $value) {
            $setParts[] = "$column = ?";
            $values[] = $value;
        }
        $sql = "UPDATE users SET " . implode(', ', $setParts) . " WHERE user_id = ?";
        $values[] = $userId;

        $stmt = $this->getConnection()->prepare($sql);
        if ($stmt->execute($values)) {
            $this->setDataChangesHistory($userId, $fields);
            return true;
        }
        return false;
    }

    /**
     * Checks if a username already exists in the database to avoid duplication (error: username must be unique)
     *
     * @param string $username
     * @param \PDO $connection
     *
     * @return bool
     */
    public function isUsernameExist($username, $connection): bool {
        if (!str_starts_with($username, '@')) {
            $username = '@' . $username;
        }
        // Prepare the SQL statement and execute it
        $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
