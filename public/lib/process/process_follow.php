<?php

use Controllers\User\UserController;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require_once '../../../src/Controllers/User/UserController.php';

// Start session
session_start();

// Create a new UserController instance
$userController = new UserController($connection);

// Check if logged in
if (!isset($_SESSION['auth']['username'])) {
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
$followerId = $userController->getUserId($_SESSION['auth']['username']);
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
$status = $userController->updateFollowers($followerId, $followingId);
echo json_encode(['status' => $status]);
