<?php

// Require necessary files
use Models\User\UserRead;
use Models\User\UserUpdate;

require '../../src/Database/DBconnect.php';
require_once '../../src/Models/UserRead.php';
require_once '../../src/Models/UserUpdate.php';

// Create a new UserRead and UserUpdate instance
$userRead = new UserRead($connection);
$userUpdate = new UserUpdate($connection);

// Start session
session_start();

// Check if logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Decode the JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Check if user ID is provided
if (!isset($data['userId'])) {
    echo json_encode(['error' => 'User ID not provided']);
    exit();
}

// Get IDs of follower and following
$followerId = $userRead->getUserId($_SESSION['username']);
$followingId = $data['userId'];

// Throw error if follower or following user not found
if (!$followerId) {
    echo json_encode(['error' => 'Follower not found']);
    exit();
}

if (!$followingId) {
    echo json_encode(['error' => 'Following user not found']);
    exit();
}

// Follow for the first time (new follow) / Unfollow (unfollowed) / Follow again (followed)
$status = $userUpdate->updateFollowers($followerId, $followingId);
echo json_encode(['status' => $status]);
