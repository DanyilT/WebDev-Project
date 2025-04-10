<?php

// Require necessary files
use Models\User\UserRead;
use Models\Post\Post;
use Models\Post\PostRepository;

// Start session
session_start();

require '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/UserRead.php';
require_once '../../../src/Models/Post.php';
require_once '../../../src/Models/PostRepository.php';

// Check if logged in
if (!isset($_SESSION['username'])) {
    header('Location: /account.php#login');
    exit();
}

// Retrieve form data
$username = $_SESSION['username'];
$title = $_POST['title'];
$content = $_POST['content'];
$media = $_POST['media'] ?? null;

if (savePost($connection, $username, $title, $content, $media)) {
    echo "Post saved successfully.";
    header('Location: /profile.php?username=' . $username);
} else {
    echo "Failed to save post.";
}
exit();

function savePost($connection, $username, $title, $content, $media): ?Post {
    $userId = (new UserRead($connection))->getUserId($username);
    $post = new Post(null, $userId, $title, $content, $media, null, null, null, $username);
    return (new PostRepository())->createPost($connection, $post);
}
