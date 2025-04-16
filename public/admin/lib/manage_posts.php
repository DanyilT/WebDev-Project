<?php

require 'auth.php';

use Controllers\Admin\AdminController;

require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Admin/AdminController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/posts.php?error=Invalid request method');
    exit;
}

if (isset($_POST['update'])) {
    // Handle post update
    (new AdminController($connection))->updatePost($_POST['post_id'], $_POST['user_id'], ['title' => $_POST['title'], 'content' => $_POST['content']]);
    header('Location: /admin/posts.php');
    exit;
} elseif (isset($_POST['delete'])) {
    // Handle post deletion
    (new AdminController($connection))->deletePost($_POST['post_id'], $_POST['user_id']);
    header('Location: /admin/posts.php');
    exit;
}

header('Location: /admin');
exit;
