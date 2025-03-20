<?php

class UserCreator {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * @throws Exception if the username already exists
     */
    public function createUser($username, $password, $email, $name, $bio = null, $profile_pic = null) {
        if ($this->usernameExists($username)) {
            throw new Exception("Username already exists.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->connection->prepare("INSERT INTO users (username, password, email, name, bio, profile_pic) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $email, $name, $bio, $profile_pic]);
    }

    private function usernameExists($username) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
