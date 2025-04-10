<?php

// Require necessary files
use Models\Post\PostComment;
use Models\Post\PostReaction;

// Start session
session_start();

require '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/PostComment.php';
require_once '../../../src/Models/PostReaction.php';

// Check if logged in
if (!isset($_SESSION['username'])) {
    header('Location: /account.php#login');
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
        null,   // userId (not used by the Post constructor directly)
        null,   // title
        null,   // content
        null    // etc.
    );
    $postComment->addComment($connection, (int)$userId, $commentText);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit();
}

// If neither like nor comment was submitted, just redirect
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
exit();
