<?php

namespace Models\User;

/**
 * Class User
 * @package Models\User
 *
 * This class is responsible for managing user data.
 * It includes methods for creating, updating, deleting, and retrieving user profiles.
 */
abstract class User {
    private $connection;

    /**
     * @param $connection
     */
    public function __construct($connection) {
        $this->connection = $connection;

    }

    /**
     * @return mixed
     */
    public function getConnection() {
        return $this->connection;
    }

    //    // Functional methods
//    public function displayUserInfo() {
//        return "Username: " . $this->username . ", Email: " . $this->email . ", Name: " . $this->name . ", Bio: " . $this->bio . ", Profile Pic: " . $this->profile_pic;
//    }

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
        } catch (\PDOException $e) {
            error_log('Error updating data changes history: ' . $e->getMessage());
        }
    }

    abstract public function isUsernameExist(string $username, \PDO $connection): bool;
}
