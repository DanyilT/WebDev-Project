<?php

use Controllers\Post\PostController;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require_once '../../../src/Controllers/Post/PostController.php';

// Start session
session_start();

// Check if logged in
if (!isset($_SESSION['auth']['user_id']) || !isset($_SESSION['auth']['username'])) {
    header('Location: /auth.php#login');
    exit();
}

$username = $_SESSION['auth']['username'];
$userId = $_SESSION['auth']['user_id'];
$postId = $_POST['post_id'] ?? null;
$title = $_POST['title'];
$content = $_POST['content'];
$media = $_POST['media'] ?? null;

if (empty($postId)) {
    // If post ID is not set, create a new post
    if (savePost($connection, $userId, $title, $content, $media)) {
        echo "Post saved successfully.";
        header('Location: /profile.php?username=' . $username);
    } else {
        echo "Failed to save post.";
        echo "<a href='/'>Go back</a>";
    }
} else {
    // If post ID is set, update the existing post
    if (updatePost($connection, $postId, $userId, $title, $content, $media)) {
        echo "Post updated successfully.";
        header('Location: /profile.php?username=' . $username);
    } else {
        echo "Failed to update post.";
        echo "<a href='/'>Go back</a>";
    }
}
exit();

function savePost($connection, $userId, $title, $content, $media): bool {
    return (new PostController($connection))->create($userId, $title, $content, $media) !== false;
}

function updatePost($connection, $postId, $userId, $title, $content, $media): bool {
    return (new PostController($connection))->update($postId, $userId, $title, $content, $media) !== false;
}
