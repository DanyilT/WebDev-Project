<?php

namespace Controllers\User;

use Models\User\UserCreate;
use Models\User\UserRead;
use Models\User\UserUpdate;
use Models\User\UserDelete;
use PDO;

require_once __DIR__ . '/../../Models/User/UserCreate.php';
require_once __DIR__ . '/../../Models/User/UserRead.php';
require_once __DIR__ . '/../../Models/User/UserUpdate.php';
require_once __DIR__ . '/../../Models/User/UserDelete.php';

/**
 * Class UserController
 * Handles user-related operations.
 * This class is responsible for creating, reading, updating, and deleting user data.
 * [UserCreate, UserRead, UserUpdate, UserDelete] = [CRUD]
 *
 * @package Controllers\User
 */
class UserController {
    private PDO $connection;

    /**
     * UserController constructor.
     * Initializes the database connection.
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     * @param string|null $bio
     * @param string|null $profile_pic
     *
     * @return bool
     * @throws \Exception if data validation fails (in $userCreate->createUser())
     */
    public function createUser(string $username, string $password, string $email, string $name, string $bio = null, string $profile_pic = null): bool {
        $userCreate = new UserCreate($this->connection, $username, $password, $email, $name, $bio, $profile_pic);
        return $userCreate->createUser();
    }

    /**
     * Searches for users by username.
     *
     * @var string $search
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function searchUsers(string $search, int $offset = 0, int $limit = null): array {
        $userRead = new UserRead($this->connection);
        return $userRead->searchUsers($search, $offset, $limit);
    }

    /**
     * Gets the user profile by username.
     *
     * @param string $username
     *
     * @return array|false
     */
    public function getUserProfile(string $username): array|false {
        $userRead = new UserRead($this->connection);
        return $userRead->getUserProfile($username);
    }

    /**
     * Updates user information in the database.
     *
     * @param int $userId
     * @param array $fields
     *
     * @return bool
     */
    public function updateUser(int $userId, array $fields): bool {
        $userUpdate = new UserUpdate($this->connection);
        return $userUpdate->updateUser($userId, $fields);
    }

    /**
     * Updates the user password in the database.
     *
     * @param int $userId
     * @param string $password
     *
     * @return bool
     */
    public function updateUserPassword(int $userId, string $password): bool {
        $userUpdate = new UserUpdate($this->connection);
        return $userUpdate->updateUserPassword($userId, $password);
    }

    /**
     * Updates the followers and following relationship in the database.
     *
     * @param int $userId
     * @param int $followerId
     *
     * @return string
     */
    public function updateFollowers(int $userId, int $followerId): string {
        $userUpdate = new UserUpdate($this->connection);
        return $userUpdate->updateFollowers($userId, $followerId);
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function deleteUser(int $userId): bool {
        $userDelete = new UserDelete($this->connection);
        return $userDelete->deleteUser($userId);
    }

    /**
     * Gets the user ID by username.
     *
     * @param string $username
     *
     * @return int
     */
    public function getUserId(string $username): int {
        $userRead = new UserRead($this->connection);
        return $userRead->getUserId($username);
    }

    /**
     * Gets the username by user ID.
     *
     * @param int $userId
     *
     * @return string
     */
    public function getUsername(int $userId): string {
        $userRead = new UserRead($this->connection);
        return $userRead->getUsername($userId);
    }

    /**
     * Gets the password of a user by username.
     *
     * @param string $username
     *
     * @return string
     */
    public function getUserPassword(string $username): string {
        $userRead = new UserRead($this->connection);
        return $userRead->getUserPassword($username);
    }

    /**
     * Gets the followers of a user by user ID.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getFollowers(int $userId): array {
        $userRead = new UserRead($this->connection);
        return $userRead->getFollowers($userId);
    }

    /**
     * Gets the followings of a user by user ID.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getFollowings(int $userId): array {
        $userRead = new UserRead($this->connection);
        return $userRead->getFollowings($userId);
    }

    /**
     * Gets the followers count of a user by user ID.
     *
     * @param int $userId
     *
     * @return int
     */
    public function getFollowersCount(int $userId): int {
        $userRead = new UserRead($this->connection);
        return $userRead->getFollowersCount($userId);
    }

    /**
     * Gets the followings count of a user by user ID.
     *
     * @param int $userId
     *
     * @return int
     */
    public function getFollowingsCount(int $userId): int {
        $userRead = new UserRead($this->connection);
        return $userRead->getFollowingsCount($userId);
    }

    /**
     * Checks if a user is following another user.
     *
     * @param int $followerId
     * @param int $followingId
     *
     * @return bool
     */
    public function isFollowing(int $followerId, int $followingId): bool {
        $userRead = new UserRead($this->connection);
        return $userRead->isFollowing($followerId, $followingId);
    }

    /**
     * Checks if a username already exists in the database.
     *
     * @param string $username
     *
     * @return bool
     */
    public function isUsernameExist(string $username): bool {
        $userRead = new UserRead($this->connection);
        return $userRead->isUsernameExist($username, $this->connection);
    }
}
