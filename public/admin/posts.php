<?php
/**
 * Admin Panel: Manage Posts
 * This file displays the posts in the admin panel and allows the admin to manage them.
 *
 * @package public/admin
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

require 'lib/auth.php';

use Controllers\Admin\AdminController;

require '../../src/Database/DBconnect.php';
require '../../src/Controllers/Admin/AdminController.php';

$adminController = new AdminController($connection);
$posts = !empty($_GET['user_id']) ? $adminController->getUserPosts($_GET['user_id']) : $adminController->getAllPosts();

require_once '../../src/Views/Admin/posts.php';
