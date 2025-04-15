<?php

use Controllers\User\UserController;
use Controllers\Post\PostController;

// Require the necessary classes
require '../src/Database/DBconnect.php';
require '../src/Controllers/User/UserController.php';
require '../src/Controllers/Post/PostController.php';

// Instantiate the UserRead and PostController classes
$userController = new UserController($connection);
$postController = new PostController($connection);

// Parse offset from GET
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

$title = 'Home - ' . (isset($_SESSION['auth']['username']) ? htmlspecialchars($_SESSION['auth']['username']) : 'Guest');
$styles = '<link rel="stylesheet" href="css/pages/index.css">';
include 'layout/header.php';
?>

<main>
    <section class="posts">
        <?php
        // Check if the user is logged in
        if (isset($_SESSION['auth']['user_id'])) {
            echo '<h2>Recent Posts from People You Follow, <span onclick="location.href=\'profile.php?username=' . htmlspecialchars($_SESSION['auth']['username']) . '\'">' . htmlspecialchars($_SESSION['auth']['username']) . '</span></h2>';
            // Get the posts of the users that the logged-in user is following
            $following = array_column($userController->getFollowings($_SESSION['auth']['user_id']), 'user_id');
            $postController->index($following, $offset, $limit);
            $nextPosts = $postController->getUsersPosts($following, $offset + $limit, $limit);
        } else {
            echo '<h2>Newest Posts from All Users</h2>';
            // If not logged in, show all posts
            $postController->index(null, $offset, $limit);
            $nextPosts = $postController->getAllPosts($offset + $limit, $limit);
        }
        ?>

        <div class="pagination">
            <?php if ($offset > 0): ?>
                <a href="?offset=<?= max(0, $offset - $limit) ?>&limit=<?= $limit ?>">&larr; Previous</a>
            <?php endif; ?>
            <?php if (!empty($nextPosts)): ?>
                <a href="?offset=<?= $offset + $limit ?>&limit=<?= $limit ?>">Next &rarr;</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'layout/footer.php'; ?>
