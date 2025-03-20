<?php

class UserProfile {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getUserProfile($username) {
        $stmt = $this->connection->prepare("SELECT * FROM active_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserProfileEvenIfDeleted($username) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFollowers($userId) {
        $stmt = $this->connection->prepare("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.follower_id = u.user_id WHERE f.following_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFollowings($userId) {
        $stmt = $this->connection->prepare("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.following_id = u.user_id WHERE f.follower_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFollowersCount($userId) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as followers FROM active_followers WHERE following_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getFollowingsCount($userId) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as followings FROM active_followers WHERE follower_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function isFollowing($followerId, $followingId) {
        $stmt = $this->connection->prepare("SELECT is_following FROM followers WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        return $stmt->fetchColumn();
    }

    public function getUserPosts($userId, $limit = null) {
        $stmt = $this->connection->prepare("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC" . ($limit ? " LIMIT $limit;" : ';'));
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
