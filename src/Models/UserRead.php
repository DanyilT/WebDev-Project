<?php

namespace Models\User;

use PDO;

require_once 'User.php';

/**
 * Class UserRead
 * @package Models\User
 *
 * This class is responsible for reading user data from the database.
 * It retrieves user profiles, followers, followings, and other related information.
 */
class UserRead extends User {
    /**
     * @param $connection PDO
     */
    public function __construct($connection) {
        parent::__construct($connection);
    }

    /**
     * Retrieves the user profile by username
     *
     * @param string $username
     *
     * @return array|false
     */
    public function getUserProfile(string $username): array|false {
        $username = !str_starts_with($username, "@") ? '@' . strtolower(trim($username)) : strtolower(trim($username));
        $stmt = $this->getConnection()->prepare("SELECT * FROM active_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the user profile even if the user is deleted
     *
     * @param string $username
     *
     * @return array
     */
    protected function getUserProfileEvenIfDeleted(string $username): array {
        $username = !str_starts_with($username, "@") ? '@' . strtolower(trim($username)) : strtolower(trim($username));
        $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the user ID of a user by their username
     *
     * @param string $username
     *
     * @return int
     */
    public function getUserId(string $username): int {
        $username = !str_starts_with($username, "@") ? '@' . strtolower(trim($username)) : strtolower(trim($username));
        $stmt = $this->getConnection()->prepare("SELECT user_id FROM active_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn();
    }

    /**
     * Retrieves the username of a user by their user ID
     *
     * @param int $userId
     *
     * @return string
     */
    public function getUsername(int $userId): string {
        $stmt = $this->getConnection()->prepare("SELECT username FROM active_users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    /**
     * Retrieves the password of a user by their username (hashed password)
     *
     * @param string $username
     *
     * @return string
     */
    public function getUserPassword(string $username): string {
        $username = !str_starts_with($username, "@") ? '@' . strtolower(trim($username)) : strtolower(trim($username));
        $stmt = $this->getConnection()->prepare("SELECT password FROM active_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn();
    }

    /**
     * Retrieves the followers of a user by their user ID
     *
     * @param int $userId
     *
     * @return array
     */
    public function getFollowers(int $userId): array {
        $stmt = $this->getConnection()->prepare("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.follower_id = u.user_id WHERE f.following_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the followings of a user by their user ID
     *
     * @param int $userId
     *
     * @return array
     */
    public function getFollowings(int $userId): array {
        $stmt = $this->getConnection()->prepare("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.following_id = u.user_id WHERE f.follower_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the count of followers for a user
     *
     * @param int $userId
     *
     * @return int
     */
    public function getFollowersCount(int $userId): int {
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) as followers FROM active_followers WHERE following_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    /**
     * Retrieves the count of followings for a user
     *
     * @param int $userId
     *
     * @return int
     */
    public function getFollowingsCount(int $userId): int {
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) as followings FROM active_followers WHERE follower_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    /**
     * Checks if a user is following another user
     *
     * @param int $followerId
     * @param int $followingId
     *
     * @return bool
     */
    public function isFollowing(int $followerId, int $followingId): bool {
        $stmt = $this->getConnection()->prepare("SELECT * FROM active_followers WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Retrieves the posts of a user by their user ID
     *
     * @param int $userId
     * @param int|null $limit
     *
     * @return array
     */
    public function getUserPosts(int $userId, int $limit = null): array {
        $stmt = $this->getConnection()->prepare("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC" . ($limit ? " LIMIT $limit;" : ';'));
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Checks if a username already exists in the database to avoid duplication (error: username must be unique)
     *
     * @param string $username
     * @param \PDO $connection
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

    protected function setDataChangesHistory(int $userId, array $fields): void {}
}
