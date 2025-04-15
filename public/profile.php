<?php

use Controllers\Post\PostController;

// Require the necessary classes
require '../src/Database/DBconnect.php';
require '../src/Controllers/Post/PostController.php';

// Parse offset from GET
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

$title = chr('64') . preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_GET['username']))) . "'s Profile";
$styles = '<link rel="stylesheet" href="css/pages/profile.css">';
include 'layout/header.php';
?>

<main>
    <button onclick="window.location.href='/auth.php?login';" style="position: absolute; top: 10px; right: 10px;">Login</button>

    <?php include '../src/Views/User/profile.php'; ?>

    <?php
    $postController = new PostController($connection);
    $postController->index($userId, $offset, $limit);
    $nextPosts = $postController->getUserPosts($userId, $offset + $limit, $limit);
    ?>

    <div class="pagination">
        <?php if ($offset > 0): ?>
            <a href="?username=<?= htmlspecialchars($_GET['username']) ?>&offset=<?= max(0, $offset - $limit) ?>&limit=<?= $limit ?>">&larr; Previous</a>
        <?php endif; ?>
        <?php if (!empty($nextPosts)): ?>
            <a href="?username=<?= htmlspecialchars($_GET['username']) ?>&offset=<?= $offset + $limit ?>&limit=<?= $limit ?>">Next &rarr;</a>
        <?php endif; ?>
    </div>
</main>

<?php
if (isset($_SESSION['auth']['username']) && $_SESSION['auth']['username'] == preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_GET['username']))))
    include 'assets/modals/profile_edit_modal.php';
?>

<?php include 'layout/footer.php'; ?>
