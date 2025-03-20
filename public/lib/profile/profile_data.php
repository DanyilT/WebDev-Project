<?php
require '../src/DBconnect.php';

// Fetch user profile information
function getUserProfile($connection, $username) {
    $stmt = $connection->prepare("SELECT user_id, username, name, bio, profile_pic, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

// Fetch followers counts
function getFollowersCount($connection, $userId) {
    $stmt = $connection->prepare("SELECT COUNT(*) as followers FROM active_followers WHERE following_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

// Fetch following counts
function getFollowingCount($connection, $userId) {
    $stmt = $connection->prepare("SELECT COUNT(*) as following FROM active_followers WHERE follower_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

// Check if the current user is following this profile
function isFollowing($connection, $currentUsername, $userId) {
    $stmt = $connection->prepare("SELECT is_following FROM followers WHERE follower_id = (SELECT user_id FROM users WHERE username = ?) AND following_id = ?");
    $stmt->execute([$currentUsername, $userId]);
    return $stmt->fetchColumn();
}

// Fetch user posts
function getUserPosts($connection, $userId) {
    $stmt = $connection->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
