<?php

use Models\Post\PostComment;
use Models\Post\PostReaction;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/Post/PostComment.php';
require_once '../../../src/Models/Post/PostReaction.php';

// Start session
session_start();

// Check if logged in
if (!isset($_SESSION['auth']['username'])) {
    header('Location: /auth.php#login');
    exit();
}

// Retrieve form data
$postId = $_POST['post_id'] ?? null;
$userId = $_POST['user_id'] ?? null;
$commentText = $_POST['comment'] ?? null;

// Ensure valid IDs
if (!$postId || !$userId) {
    header('Location: /');
    exit();
}

// Handle toggling like
if (isset($_POST['like'])) {
    $postsReaction = new PostReaction();
    $currentLikes = $postsReaction->getLikes($connection, (int)$postId) ?? [];
    if (in_array((int)$userId, $currentLikes)) {
        // Already liked, so remove
        $postsReaction->dislikePost($connection, (int)$postId, (int)$userId);
    } else {
        // Not liked, so add
        $postsReaction->likePost($connection, (int)$postId, (int)$userId);
    }
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit();
}

// Handle adding comment
if (!empty($commentText)) {
    $postComment = new PostComment(
        $postId,    // postId
        $userId,    // userId
        '',   // title
        ''   // content
    );
    $postComment->addComment($connection, (int)$userId, $commentText);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit();
}

// If neither like nor comment was submitted, just redirect
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
exit();
