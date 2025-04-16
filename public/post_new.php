<?php
/**
 * Page: Create New Post
 * Description: This file handles the creation of new posts.
 * It checks if the user is authenticated and then includes the post creation view.
 *
 * @package public
 */

session_start();
if (!isset($_SESSION['auth'])) {
    header('Location: /auth.php#login');
    exit();
}

require_once '../src/Views/Post/create.php';
