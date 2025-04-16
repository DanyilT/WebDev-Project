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
        if (!isset($_SESSION['auth'])) {
            $_GET['page'] = 'explore';
        }
        if (isset($_SESSION['auth']['user_id']) && (!isset($_GET['page']) || $_GET['page'] != 'explore')) {
            echo '<h2>Recent Posts from People You Follow, <span onclick="location.href=\'profile.php?username=' . htmlspecialchars($_SESSION['auth']['username']) . '\'">' . htmlspecialchars($_SESSION['auth']['username']) . '</span></h2>';
            echo '<p>Or <a href="/?page=explore">explore</a> the latest posts from all users.</p>';
            // Get the posts of the users that the logged-in user is following
            $following = array_column($userController->getFollowings($_SESSION['auth']['user_id']), 'user_id');
            if (!empty($following)) {
                $postController->index($following, $offset, $limit);
                $nextPosts = $postController->getUsersPosts($following, $offset + $limit, $limit);
            } else {
                echo '<h3>You are not following anyone yet.</h3>';
                echo '<p>Check out the <a href="search.php">search page</a> to find users to follow.</p>';
                echo '<p>Or <a href="/?page=explore">explore</a> the latest posts from all users.</p>';
            }
        } elseif (isset($_GET['page']) && $_GET['page'] == 'explore') {
            echo '<h2>Explore Newest Posts from All Users</h2>';
            // If not logged in or the user is not following anyone, show all posts
            $postController->index(null, $offset, $limit);
            $nextPosts = $postController->getAllPosts($offset + $limit, $limit);
        } else {
            echo '<a href="/?page=explore">Explore</a>';
        }
        ?>

        <div class="pagination">
            <?php if ($offset > 0): ?>
                <?php $prevQuery = http_build_query(array_merge($_GET, ['offset' => max(0, $offset - $limit), 'limit' => $limit])); ?>
                <a href="?<?= $prevQuery ?>">&larr; Previous</a>
            <?php endif; ?>
            <?php if (!empty($nextPosts)): ?>
                <?php $nextQuery = http_build_query(array_merge($_GET, ['offset' => $offset + $limit, 'limit' => $limit])); ?>
                <a href="?<?= $nextQuery ?>">Next &rarr;</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'layout/footer.php'; ?>
