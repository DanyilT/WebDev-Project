<?php

namespace Models\User;

use PDO;

require_once 'User.php';

/**
 * Class UserUpdate
 * Handles user data updates in the database.
 * It provides methods to update user information, followers, and following status.
 * It also allows update Followers Table.
 * This class extends the User class and provides methods to validate user data,
 *
 * @package Models\User
 */
class UserUpdate extends User {
    /**
     * UserUpdate constructor.
     * Sets the database connection.
     *
     * @param $connection PDO
     */
    public function __construct(PDO $connection) {
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
        // TODO: Validate the fields
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
            $this->setDataChangesHistory($userId, ['update' => $fields]);
            return true;
        }
        return false;
    }

    /**
     * Updates the user password in the database
     *
     * @param int $userId
     * @param string $password
     *
     * @return bool
     */
    public function updateUserPassword(int $userId, string $password): bool {
        // TODO: Validate the password
        // Hash the password before storing it
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Prepare the SQL statement and execute it
        $stmt = $this->getConnection()->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        return $stmt->execute([$password, $userId]);
    }

    /**
     * Updates the followers and following status in the database
     *
     * @param int $followerId
     * @param int $followingId
     *
     * @return string
     */
    public function updateFollowers(int $followerId, int $followingId): string {
        // Check if record exists for given follower and following
        $record = $this->isIs_FollowingRecordExist($followerId, $followingId);

        if (!$record) {
            // If there's no record, create a new one with a follow action
            $initialHistory = json_encode([["action" => "follow", "timestamp" => date('Y-m-d H:i:s')]]);
            $stmt = $this->getConnection()->prepare("INSERT INTO followers (follower_id, following_id, following_history) VALUES (?, ?, ?)");
            if (!$stmt->execute([$followerId, $followingId, $initialHistory])) {
                return 'error';
            }
            return 'new follow';
        } else {
            // If record exists, toggle is_following to its opposite value
            $newStatus = $record['is_following'] ? 0 : 1;

            // Update following_history by appending the new action
            $history = json_decode($record['following_history'], true);
            if (!is_array($history)) {
                $history = [];
            }
            $action = $newStatus ? "follow" : "unfollow";
            $history[] = ["action" => $action, "timestamp" => date('Y-m-d H:i:s')];
            $newHistory = json_encode($history);

            $stmt = $this->getConnection()->prepare("UPDATE followers SET is_following = ?, following_history = ? WHERE follower_id = ? AND following_id = ?");
            if (!$stmt->execute([$newStatus, $newHistory, $followerId, $followingId])) {
                return 'error';
            }
            return $newStatus ? 'followed' : 'unfollowed';
        }
    }

    /**
     * Checks if a record exists for the given follower and following IDs
     *
     * @param int $followerId
     * @param int $followingId
     *
     * @return array|false
     */
    private function isIs_FollowingRecordExist(int $followerId, int $followingId): array|false {
        $stmt = $this->getConnection()->prepare("SELECT is_following, following_history FROM followers WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
