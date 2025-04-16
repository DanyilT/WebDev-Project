<?php

require 'lib/auth.php';

use Controllers\Admin\AdminController;

require '../../src/Database/DBconnect.php';
require '../../src/Controllers/Admin/AdminController.php';

$adminController = new AdminController($connection);
$posts = !empty($_GET['user_id']) ? $adminController->getUserPosts($_GET['user_id']) : $adminController->getAllPosts();

require_once '../../src/Views/Admin/posts.php';
