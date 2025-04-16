<?php
/**
 * File: process_delete_post.php
 * This file processes the deletion of a post.
 *
 * @package public/lib/process
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

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

// Set up variables
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
} else {
    echo "Failed to delete post.";
    echo "<a href='/'>Go back</a>";
}
exit();

/**
 * Delete a post.
 *
 * @param PDO $connection Database connection object.
 * @param int $postId ID of the post to delete.
 * @param int $userId ID of the user who owns the post.
 *
 * @return bool True if the post was deleted successfully, false otherwise.
 */
function deletePost(PDO $connection, int $postId, int $userId): bool {
    return (new PostController($connection))->delete($postId, $userId) !== false;
}
