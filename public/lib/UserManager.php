<?php
require 'UserCreator.php';
require 'UserProfile.php';

class UserManager extends User {
    private $connection;
    private $userCreator;
    private $userProfile;

    public function __construct($connection, $username, $email, $name, $bio = null, $profile_pic = null) {
        parent::__construct($username, $email, $name, $bio, $profile_pic);
        $this->connection = $connection;
        $this->userCreator = new UserCreator($connection, $username, $email, $name, $bio, $profile_pic);
        $this->userProfile = new UserProfile($connection);
    }

    public function createUser($password) {
        return $this->userCreator->createUser($password);
    }

    public function updateUser($user_id, $username, $email, $name, $bio, $profile_pic, $created_at, $is_deleted) {
        $stmt = $this->connection->prepare("UPDATE users SET username = ?, email = ?, name = ?, bio = ?, profile_pic = ?, created_at = ?, is_deleted = ? WHERE user_id = ?");
        return $stmt->execute([$username, $email, $name, $bio, $profile_pic, $created_at, $is_deleted, $user_id]);
    }

    public function deleteUser($user_id) {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    public function getAllUsers() {
        $stmt = $this->connection->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserProfile($username) {
        return $this->userProfile->getUserProfile($username);
    }

    public function isUsernameExist($username) {
        return $this->userCreator->isUsernameExist($username);
    }
}
