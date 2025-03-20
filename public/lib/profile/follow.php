<?php
session_start();
require '../../../src/DBconnect.php';

// Check if logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Decode the JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Check if user ID is provided
if (!isset($data['user_id'])) {
    echo json_encode(['error' => 'User ID not provided']);
    exit();
}

// Get IDs of follower and following
$followerUsername = $_SESSION['username'];
$followingId = $data['user_id'];

// Get user IDs
$stmt = $connection->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->execute([$followerUsername]);
$followerId = $stmt->fetchColumn();

// Throw error if follower or following user not found
if (!$followerId) {
    echo json_encode(['error' => 'Follower not found']);
    exit();
}

if (!$followingId) {
    echo json_encode(['error' => 'Following user not found']);
    exit();
}

// Check if already following
$stmt = $connection->prepare("SELECT * FROM followers WHERE follower_id = ? AND following_id = ?");
$stmt->execute([$followerId, $followingId]);
$follow = $stmt->fetch();

if ($follow) {
    if ($follow['is_following']) {
        // Unfollow
        $followingHistory = json_decode($follow['following_history'], true);
        $followingHistory[] = ['action' => 'unfollow', 'timestamp' => date('Y-m-d H:i:s')];

        $stmt = $connection->prepare("UPDATE followers SET is_following = FALSE, following_history = ? WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([json_encode($followingHistory), $followerId, $followingId]);
        echo json_encode(['status' => 'unfollowed']);
    } else {
        // Follow again
        $followingHistory = json_decode($follow['following_history'], true);
        $followingHistory[] = ['action' => 'follow', 'timestamp' => date('Y-m-d H:i:s')];

        $stmt = $connection->prepare("UPDATE followers SET is_following = TRUE, following_history = ? WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([json_encode($followingHistory), $followerId, $followingId]);
        echo json_encode(['status' => 'followed']);
    }
} else {
    // Follow for the first time
    $followingHistory = [['action' => 'follow', 'timestamp' => date('Y-m-d H:i:s')]];

    $stmt = $connection->prepare("INSERT INTO followers (follower_id, following_id, is_following, following_history) VALUES (?, ?, ?, ?)");
    $stmt->execute([$followerId, $followingId, TRUE, json_encode($followingHistory)]);
    echo json_encode(['status' => 'followed']);
}
