<?php

require 'auth.php';

use Controllers\Admin\AdminController;

require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Admin/AdminController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php?error=Invalid request method');
    exit;
}

if (isset($_POST['create'])) {
    // Handle user creation
    (new AdminController($connection))->createUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $_POST['bio'] ?: null, $_POST['profile_pic'] ?: null);
    header('Location: /admin/users.php');
    exit;
} elseif (isset($_POST['update'])) {
    // Handle user update
    (new AdminController($connection))->updateUser($_POST['user_id'], ['username' => $_POST['username'], 'password' => $_POST['password'], 'email' => $_POST['email'], 'name' => $_POST['name'], 'bio' => $_POST['bio'] ?: null, 'profile_pic' => $_POST['profile_pic'] ?: null, 'created_at' => $_POST['created_at'], 'is_deleted' => $_POST['is_deleted']]);
    header('Location: /admin/users.php');
    exit;
} elseif (isset($_POST['delete'])) {
    // Handle user deletion
    (new AdminController($connection))->deleteUser($_POST['user_id']);
    header('Location: /admin/users.php');
    exit;
}

header('Location: /admin');
exit;
