<?php
/**
 * View: Posts
 * This file is responsible for displaying the posts and their comments.
 * It includes the necessary HTML and PHP code to render the posts,
 * handle user interactions, and manage comments.
 *
 * @package Views\Post
 *
 * @var PDO $connection Database connection object (assumed to be passed from DBconnect.php - should be required in the parent file)
 * @var Post[] $posts Posts to display (Should be passed from the Post Controller)
 */

use Models\Post\Post;

require_once __DIR__ . '/../../Models/Post/PostReaction.php';
require_once __DIR__ . '/../../Models/Post/PostComment.php';

// Check if the user is logged in
$sessionAuth = isset($_SESSION['auth']) && $_SESSION['auth']['user_id'] && $_SESSION['auth']['username'] ? $_SESSION['auth'] : null;

echo '<div class="posts-container">';
require_once __DIR__ . '/show.php';
echo '</div>';
