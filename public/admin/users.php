<?php

require 'lib/auth.php';

use Controllers\Admin\AdminController;

require '../../src/Database/DBconnect.php';
require '../../src/Controllers/Admin/AdminController.php';

$adminController = new AdminController($connection);
$users = !empty($_GET['search']) ? $adminController->searchUser($_GET['search']) : $adminController->getAllUsers();

require_once '../../src/Views/Admin/users.php';
