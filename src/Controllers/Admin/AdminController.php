<?php

namespace Controllers\Admin;

use Models\User\UserCreate;
use Models\User\UserDelete;
use Models\User\UserRead;
use Models\User\UserUpdate;
use Models\Post\Post;
use Models\Post\PostRepository;
use PDO;

require_once __DIR__ . '/../../Models/User/UserCreate.php';
require_once __DIR__ . '/../../Models/User/UserDelete.php';
require_once __DIR__ . '/../../Models/User/UserRead.php';
require_once __DIR__ . '/../../Models/User/UserUpdate.php';
require_once __DIR__ . '/../../Models/Post/PostRepository.php';

class AdminController {
    private PDO $connection;
    private UserCreate $userCreate;
    private UserDelete $userDelete;
    private UserRead $userRead;
    private UserUpdate $userUpdate;
    private PostRepository $postRepository;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
        $this->userDelete = new UserDelete($connection);
        $this->userRead = new UserRead($connection);
        $this->userUpdate = new UserUpdate($connection);
        $this->postRepository = new PostRepository();
    }

    /**
     * Returns all users from the database
     *
     * @return array
     */
    public function getAllUsers(): array {
        return $this->userRead->getAllUsersEvenIfDeleted();
    }

    /**
     * Creates a new user in the database if data is valid
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     * @param string|null $bio
     * @param string|null $profile_pic
     *
     * @return bool
     * @throws \Exception if data validation fails
     */
    public function createUser(string $username, string $password, string $email, string $name, string $bio = null, string $profile_pic = null): bool {
        $this->userCreate = new UserCreate($this->connection, $username, $password, $email, $name, $bio, $profile_pic);
        return $this->userCreate->createUser();
    }

    /**
     * Returns user(s) profile data
     *
     * @param string $username
     *
     * @return array
     */
    public function searchUser(string $username): array {
        return $this->userRead->searchUsersEvenIfDeleted($username);
    }

    /**
     * Updates user data in the database
     *
     * @param int $userId
     * @param array $fields
     *
     * @return bool
     */
    public function updateUser(int $userId, array $fields): bool {
        if (isset($fields['password']) && !empty($fields['password'])) {
            $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
        }
        return $this->userUpdate->updateUser($userId, $fields);
    }

    /**
     * Deletes a user from the database
     *
     * @param int $userId
     *
     * @return bool
     */
    public function deleteUser(int $userId): bool {
        return $this->userDelete->actuallyDeleteUser($userId);
    }

    public function getAllPosts(): array {
        return $this->postRepository->getAllPostsEvenIfDeleted($this->connection);
    }

    /**
     * Get all posts of a user by their ID or username
     *
     * @param int|string $userId_or_username
     *
     * @return array
     */
    public function getUserPosts(int|string $userId_or_username): array {
        if (is_numeric($userId_or_username)) {
            return $this->postRepository->getUserPostsEvenIfDeleted($this->connection, $userId_or_username);
        } else {
            return $this->postRepository->getUserPostsEvenIfDeleted($this->connection, $this->userRead->getUserProfileEvenIfDeleted($userId_or_username)['user_id']);
        }
    }

    /**
     * Updates a post in the database
     *
     * @param int $postId
     * @param int $ownerId
     * @param array $fields
     *
     * @return Post|null
     */
    public function updatePost(int $postId, int $ownerId, array $fields): ?Post {
        return $this->postRepository->updatePost($this->connection, $postId, $ownerId, $fields); // Update only `active_posts`
    }

    /**
     * Deletes a post from the database
     *
     * @param int $postId
     * @param int $ownerId
     *
     * @return bool
     */
    public function deletePost(int $postId, int $ownerId): bool {
        return $this->postRepository->actuallyDeletePost($this->connection, $postId, $ownerId);
    }
}
