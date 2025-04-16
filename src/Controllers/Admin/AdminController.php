<?php

namespace Controllers\Admin;

use Models\User\UserCreate;
use Models\User\UserDelete;
use Models\User\UserRead;
use Models\User\UserUpdate;
use PDO;

require_once __DIR__ . '/../../Models/User/UserCreate.php';
require_once __DIR__ . '/../../Models/User/UserDelete.php';
require_once __DIR__ . '/../../Models/User/UserRead.php';
require_once __DIR__ . '/../../Models/User/UserUpdate.php';

class AdminController
{
    private PDO $connection;
    private UserCreate $userCreate;
    private UserDelete $userDelete;
    private UserRead $userRead;
    private UserUpdate $userUpdate;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userDelete = new UserDelete($connection);
        $this->userRead = new UserRead($connection);
        $this->userUpdate = new UserUpdate($connection);
    }

    /**
     * Returns all users from the database
     *
     * @return array
     */
    public function getAllUsers(): array
    {
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
    public function createUser(string $username, string $password, string $email, string $name, string $bio = null, string $profile_pic = null): bool
    {
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
    public function searchUser(string $username): array
    {
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
    public function updateUser(int $userId, array $fields): bool
    {
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
    public function deleteUser(int $userId): bool
    {
        return $this->userDelete->actuallyDeleteUser($userId);
    }
}
