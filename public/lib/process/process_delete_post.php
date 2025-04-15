<?php

use Controllers\Post\PostController;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Post/PostController.php';

// Start session
session_start();

// Check if logged in
if (!isset($_SESSION['auth']['user_id']) || !isset($_SESSION['auth']['username'])) {
    header('Location: /auth.php#login');
    exit();
}

$userId  = $_SESSION['auth']['user_id'];
$postId  = $_POST['post_id'] ?? null;
$confirm = $_POST['confirm'] ?? null;

if (!$postId || !$confirm) {
    echo "Invalid request.";
    echo "<a href='/'>Go back</a>";
    exit();
}

if (deletePost($connection, $postId, $userId)) {
    header('Location: /profile.php?username=' . $_SESSION['auth']['username']);
    exit();
} else {
    echo "Failed to delete post.";
    echo "<a href='/'>Go back</a>";
    exit();
}

function deletePost($connection, $postId, $userId): bool {
    return (new PostController($connection))->delete($postId, $userId) !== false;
}
