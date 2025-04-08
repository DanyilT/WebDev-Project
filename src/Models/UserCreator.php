<?php

namespace Models\Users;

use PDO;

require 'User.php';

class UserCreator extends User {
    private $connection;

    /**
     * @param $connection PDO
     * @param $username
     * @param $email
     * @param $name
     * @param $bio = null
     * @param $profile_pic = null
     */
    public function __construct($connection, $username, $email, $name, $bio = null, $profile_pic = null) {
        parent::__construct($username, $email, $name, $bio, $profile_pic);
        $this->connection = $connection;
    }

    /**
     * @throws Exception if the username already exists
     */
    public function createUser($password) {
        if ($this->isUsernameExist($this->getUsername())) {
            throw new Exception("Username already exists.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->connection->prepare("INSERT INTO users (username, password, email, name, bio, profile_pic) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$this->getUsername(), $hashedPassword, $this->getEmail(), $this->getName(), $this->getBio(), $this->getProfilePic()]);
    }

    public function isUsernameExist($username) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
