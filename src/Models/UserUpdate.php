<?php

namespace Models\User;

use PDO;

require_once 'User.php';

/**
 * Class UserUpdate
 * @package Models\User
 *
 * This class is responsible for updating user data in the database.
 * It allows for updating user profiles, followers, followings, and other related information.
 * It also allows update Followers Table.
 */
class UserUpdate extends User {
    /**
     * @param $connection PDO
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
     * Updates the followers and following status in the database
     *
     * @param int $followerId
     * @param int $followingId
     *
     * @return string
     */
    public function updateFollowers(int $followerId, int $followingId): string {
        // Check if record exists for given follower and following
        $stmt = $this->getConnection()->prepare("SELECT is_following, following_history FROM followers WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            // If there's no record, create a new one with a follow action
            $initialHistory = json_encode([["action" => "follow", "timestamp" => date('Y-m-d H:i:s')]]);
            $stmt = $this->getConnection()->prepare("INSERT INTO followers (follower_id, following_id, following_history) VALUES (?, ?, ?)");
            $stmt->execute([$followerId, $followingId, $initialHistory]);
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
            $stmt->execute([$newStatus, $newHistory, $followerId, $followingId]);
            return $newStatus ? 'followed' : 'unfollowed';
        }
    }

    /**
     * Checks if a username already exists in the database to avoid duplication (error: username must be unique)
     *
     * @param string $username
     * @param PDO $connection
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
