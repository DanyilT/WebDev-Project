<?php
/**
 * File: load_more_comments.php
 * This file loads more comments for a specific post.
 *
 * @package public/lib/process
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

header('Content-Type: text/html; charset=UTF-8');

use Models\Post\PostComment;

// Require necessary files
require_once '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/Post/PostComment.php';

// Set up variables
$postId = (int)($_GET['postId'] ?? 0);
$offset = (int)($_GET['offset'] ?? 0);
$limit = (int)($_GET['limit'] ?? 5);

$additionalComments = (new PostComment($postId, 0, '', ''))->getComments($connection);
$commentsSlice = array_slice($additionalComments, $offset, $limit);

// Render the HTML for each comment
foreach ($commentsSlice as $comment) {
    echo '<hr><div class="comment">';
    echo '<h4 class="author"><a class="author" href="/profile.php?username=' . htmlspecialchars($comment['username']). '">' . htmlspecialchars($comment['username']) . '</a></h4>';
    echo '<p class="content">' . htmlspecialchars($comment['content']) . '</p>';
    echo '<p class="date">' . htmlspecialchars($comment['created_at']) . '</p>';
    echo '</div>';
}
