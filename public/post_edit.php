<?php
session_start();
if (!isset($_SESSION['auth']['user_id'])) {
    header('Location: auth.php#login');
    exit();
}

// Take data from session and GET request
$userId = $_SESSION['auth']['user_id'];
$postId = $_GET['post_id'] ?? null;

// Validate the post ID
if (!$postId) {
    header('Location: /');
    exit();
}

use Controllers\Post\PostController;

// Require necessary files
require '../src/Database/DBconnect.php';
require '../src/Controllers/Post/PostController.php';

$postController = new PostController($connection);
$post = $postController->show($postId);

// Check if the post exists
if (!$post) {
    echo "Post not found.";
    echo "<a href='/'>Go back</a>";
    exit();
}

// Check if the logged-in user is the owner of the post
if ($post->getUserId() !== $userId) {
    echo "You are not authorized to edit this post.";
    echo "<a href='/'>Go back</a>";
    exit();
}

// Include the view for editing the post
require_once '../src/Views/Post/edit.php';
