<?php

namespace Models\User;

use PDO;
use PDOException;

/**
 * Class User
 * Abstract class representing a user in the system.
 * This class provides methods to manage user data and interactions with the database.
 *
 * @package Models\User
 */
abstract class User {
    private PDO $connection;

    /**
     * User constructor.
     * Sets the database connection.
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;

    }

    /**
     * Get the database connection
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Sets the data changes history
     *
     * @param int $userId
     * @param array $fields
     */
    protected function setDataChangesHistory(int $userId, array $fields): void {
        try {
            $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $currentUser = $stmt->fetch();

            if (!$currentUser) {
                return;
            }

            $changes = [];
            foreach ($fields as $column => $newValue) {
                if ($currentUser[$column] !== $newValue) {
                    $changes[$column] = [
                        // TODO: FIX: This should be the old value, but it saves null (probably because of the I update the data first and then I trying to get old data when it is already updated (see UserUpdate->updateUser) or maybe because of the JSON_ARRAY_APPEND)
                        'old' => $currentUser[$column],
                        'new' => $newValue,
                        'timestamp' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (empty($changes)) {
                return;
            }

            $changesJson = json_encode($changes);

            // Initialize the history if empty
            if (empty($currentUser['data_changes_history'])) {
                $origin = json_encode(['origin' => $currentUser]);
                $stmt = $this->getConnection()->prepare("UPDATE users SET data_changes_history = JSON_ARRAY(CAST(? AS JSON), CAST(? AS JSON)) WHERE user_id = ?");
                $stmt->execute([$origin, $changesJson, $userId]);
            } else {
                $stmt = $this->getConnection()->prepare("UPDATE users SET data_changes_history = JSON_ARRAY_APPEND(data_changes_history, '$', CAST(? AS JSON)) WHERE user_id = ?");
                $stmt->execute([$changesJson, $userId]);
            }
        } catch (PDOException $e) {
            error_log('Error updating data changes history: ' . $e->getMessage());
        }
    }

    /**
     * Check if the username already exists in the database
     *
     * @param string $username
     * @param PDO $connection
     * @return bool
     */
    abstract public function isUsernameExist(string $username, PDO $connection): bool;
}
